<?php

namespace App\Ontologies\Handlers;

use App\Exceptions\ScriptFailedException;
use App\Ontologies\Helpers\HttpService;
use App\Ontologies\Handlers\Queries;
use App\Ontologies\Handlers\Parser;
use App\Ontologies\Handlers\ServiceInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Ontologies\Helpers\RandomHelper;
use SebastianBergmann\Type\VoidType;
class Service implements InterfaceService
{
    private $entity = [
            "uri" => null,
            "displayId" => null,
            "title" => null,
            "data_properties" => [],
            "object_properties" => [],
            "builtin_object_properties" => [],
        ];

    public function updateMalware(): string
    {
        return HttpService::postOwl(Parser::parseMalware());
    }

    public function searchEntities(string $searchTerm, $entitiesToExclude)
    {
        $results = Queries::searchEntities($searchTerm, $entitiesToExclude);
        $entities = array_map(function ($result) {
            return [
                "uri" => $result['entity']['value'],
                "displayId" => RandomHelper::getSubstrAfterLastSpecialChar($result['entity']['value']),
                "title" => $result['value']['value'],
            ];
        }, $results);
        return $entities;
    }

    public function getCleanEntityProperties($id): array
    {

        $properties = Queries::getRawEntityProperties($id);
        if (RandomHelper::isTechnique($properties)) {
            $relationNames = Queries::getRelations($id, 'mitigates', 'usesTechnique', "hasAttckTechnique");
            $relationNames = $this->mapTechniqueRelations($relationNames);
            $properties = array_merge($properties, $relationNames);
        }
        $this->mapExistingData($properties);
        return $this->entity;
    }

    public function mapExistingData(array $properties): void
    {
        if (!isset($properties)) return;

        $entityId = $properties[0]['entity']['value']; //ID of the named individual (not a property)
        $this->entity["uri"] = $entityId;
        $this->entity["displayId"] = RandomHelper::getSubstrAfterLastSpecialChar($entityId);
        $builtinProperties = RandomHelper::fromConfigGet('object_properties', "builtin");
        $searchables = RandomHelper::fromConfigGet('searchable');
        foreach ($properties as $prop) {
            $propertyValue = $prop['property']['value'];
            $valueValue = $prop['value']['value'];
            $valueType = $prop['value']['type'];

            if ($valueType === "literal") {
                if (in_array($propertyValue, $searchables)) {
                    $this->entity['title'] = $valueValue;
                }
                $strippedPropValue = $this->mapToConfigName($propertyValue, 'data_properties');
                $this->entity['data_properties'][$strippedPropValue] = $valueValue;
            }
            elseif(array_key_exists($propertyValue, $builtinProperties)){
                $valueValue = RandomHelper::getSubstrAfterLastSpecialChar($valueValue);
                $strippedPropValue = $this->mapToConfigName($propertyValue, 'object_properties');
                $this->entity['builtin_object_properties'][$strippedPropValue][] = $valueValue;
            }
            else{
                $strippedId = RandomHelper::getSubstrAfterLastSpecialChar($valueValue);
                $objectEntityData = [
                    'uri' => $valueValue,
                    'id' => $strippedId,
                    'name' => $prop['name']['value'] ?? $strippedId
                ];
                $strippedPropValue = $this->mapToConfigName($propertyValue, 'object_properties');
                $this->entity['object_properties'][$strippedPropValue][] = $objectEntityData;
            }
        }
        if (!isset($this->entity['title'])) {
            $this->entity['title'] = $this->entity["displayId"];
        }
    }
    private function mapToConfigName($propValue, $configProperty)
    {
        return RandomHelper::fromConfigGet($configProperty)[$propValue] ?? "usedBy";
    }
    private function mapTechniqueRelations($relations)
    {
        foreach ($relations as $key => $relation) {
            $entityId = $relation['entity']['value'];
            $propertyVal = 'usedIn';
            $relations[$key] = [
                "property" => ["type" => "uri", "value" => $propertyVal],
                "value" => ["type" => "uri", "value" => $entityId],
            ];
        }
        return $relations;
    }
}
