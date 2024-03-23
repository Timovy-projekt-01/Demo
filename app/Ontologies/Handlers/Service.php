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

    # Order of methods is important
    public function getCleanEntityProperties($id): array
    {

        $properties = Queries::getRawEntityProperties($id);
        if (RandomHelper::isTechnique($properties)) {
            $relationNames = Queries::getRelations($id, 'mitigates', 'usesTechnique', "hasAttckTechnique");
            $relationNames = $this->mapTechniqueRelations($relationNames);
            $properties = array_merge($properties, $relationNames);
        }
        $this->mapExistingData($properties);
        $this->addTitle();
        $this->mapToConfigNames();
        return $this->entity;
    }
    public function addTitle(): void
    {
        if (!isset($this->entity['data_properties'])) return;

        $searchables = RandomHelper::fromConfigGet('searchable');
        foreach($this->entity['data_properties'] as $key => $value){
            if(in_array($key, $searchables)){
                $this->entity['title'] = $value;
                break;
            }
        }
    }

    public function mapToConfigNames(): void
    {
        $this->parseAndMapProperties("data_properties", "data_properties");
        $this->parseAndMapProperties("object_properties", "object_properties");
        $this->parseAndMapProperties("builtin_object_properties", "object_properties", "builtin");
    }

    public function parseAndMapProperties($entityPropType, $configPropType, $ontologyName = null): void
    {
        if (!isset($this->entity[$entityPropType])) return;

        $configProperties = RandomHelper::fromConfigGet($configPropType, $ontologyName);
        foreach($this->entity[$entityPropType] as $entityProp => $value){
            foreach($configProperties as $configProp => $configPropValue){
                if($entityProp === $configProp){
                    $literal = $this->entity[$entityPropType][$entityProp];
                    unset($this->entity[$entityPropType][$entityProp]);
                    $this->entity[$entityPropType][$configPropValue] = $literal;
                    unset($configProperties[$configProp]);
                    break;
                }
            }
        }
    }

    public function mapExistingData(array $properties): void
    {
        if (!isset($properties)) return;

        $entityId = $properties[0]['entity']['value']; //ID of the named individual (not a property)
        $this->entity["uri"] = $entityId;
        $this->entity["displayId"] = RandomHelper::getSubstrAfterLastSpecialChar($entityId);
        $builtinProperties = RandomHelper::fromConfigGet('object_properties', "builtin");
        foreach ($properties as $prop) {
            $propertyValue = $prop['property']['value'];
            $valueValue = $prop['value']['value'];
            $valueType = $prop['value']['type'];

            if ($valueType === "literal") {
                $this->entity['data_properties'][$propertyValue] = $valueValue;
            }
            elseif(array_key_exists($propertyValue, $builtinProperties)){
                $propertyValue = RandomHelper::getSubstrAfterLastSpecialChar($valueValue);
                $this->entity['builtin_object_properties'][$propertyValue][] = $propertyValue;
            }
            else{
                $strippedId = RandomHelper::getSubstrAfterLastSpecialChar($valueValue);
                $objectEntityData = [
                    'uri' => $valueValue,
                    'id' => $strippedId,
                    'name' => $prop['name']['value'] ?? $strippedId
                ];
                $this->entity['object_properties'][$propertyValue][] = $objectEntityData;
            }
        }
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
