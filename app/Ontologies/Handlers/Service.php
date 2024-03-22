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
class Service implements InterfaceService
{

    public function updateMalware(): string
    {
        return HttpService::postOwl(Parser::parseMalware());
    }

    public function searchEntities(string $searchTerm, $entitiesToExclude)
    {
        $results = Queries::searchEntities($searchTerm, $entitiesToExclude);
        $results = array_map(function ($result) {
            $result['displayId'] = RandomHelper::getSubstrAfterLastSpecialChar($result['entity']['value']);
            return $result;
        }, $results);
        return $results;
    }

    # Order of methods is important
    public function getCleanEntityProperties($id): array
    {
        $entity = [];

        $properties = Queries::getRawEntityProperties($id);
        if (RandomHelper::isTechnique($properties)) {
            $relationNames = Queries::getRelations($id, 'mitigates', 'usesTechnique', "hasAttckTechnique");
            $relationNames = $this->mapTechniqueRelations($relationNames);
            $properties = array_merge($properties, $relationNames);
        }
        $entity = $this->mapExistingData($entity, $properties);
        $entity = $this->getNameForObjectProperties($entity);
        $entity = $this->addTitle($entity);
        $entity = $this->mapToConfigNames($entity);
        return $entity;
    }

    public function addTitle($entity): array
    {
        $searchables = RandomHelper::fromConfigGet('searchable');
        foreach($entity['data_properties'] as $key => $value){
            if(in_array($key, $searchables)){
                $entity['title'] = $value;
                break;
            }
        }
        return $entity;
    }
    public function mapToConfigNames($entity): array
    {
        $entity = $this->parseAndMapProperties($entity, "data_properties", "data_properties");
        $entity = $this->parseAndMapProperties($entity, "object_properties", "object_properties");
        $entity = $this->parseAndMapProperties($entity, "builtin_object_properties", "object_properties", "builtin");

        return $entity;
    }
    public function parseAndMapProperties($entity, $entityPropType, $configPropType, $ontologyName = null)
    {
        $configProperties = RandomHelper::fromConfigGet($configPropType, $ontologyName);
        foreach($entity[$entityPropType] as $entityProp => $value){
            foreach($configProperties as $configProp => $configPropValue){
                if($entityProp === $configProp){
                    $literal = $entity[$entityPropType][$entityProp];
                    unset($entity[$entityPropType][$entityProp]);
                    $entity[$entityPropType][$configPropValue] = $literal;
                    unset($configProperties[$configProp]);
                    break;
                }
            }
        }
        return $entity;
    }

    public function mapExistingData(array $entity, array $properties): array
    {
        $entityId = $properties[0]['entity']['value']; //ID of the named individual (not a property)
        $entity["uri"] = $entityId;
        $entity["displayId"] = RandomHelper::getSubstrAfterLastSpecialChar($entityId);
        $builtinProperties = RandomHelper::fromConfigGet('object_properties', "builtin");
        foreach ($properties as $prop) {
            $propertyName = $prop['property']['value'];
            $propertyValue = $prop['value']['value'];
            $valueType = $prop['value']['type'];

            if ($valueType === "literal") {
                $entity['data_properties'][$propertyName] = $propertyValue;
            }
            elseif(array_key_exists($propertyName, $builtinProperties)){
                $propertyValue = RandomHelper::getSubstrAfterLastSpecialChar($propertyValue);
                $entity['builtin_object_properties'][$propertyName][] = $propertyValue;
            }
            else{
                $entity['object_properties'][$propertyName][] = $propertyValue;
            }
        }
        return $entity;
    }

    public function getNameForObjectProperties($entity): array
    {
        foreach ($entity['object_properties'] as $objectPropKey => $objectPropArray) {
            $entityIds = $entity['object_properties'][$objectPropKey];
            $namesAndIds = $this->getNamesForIds($entityIds);

            $entity['object_properties'][$objectPropKey] = array_map(function ($namesAndIds) {
                $strippedId = RandomHelper::getSubstrAfterLastSpecialChar($namesAndIds['entity']['value']);
                return ['uri' => $namesAndIds['entity']['value'],
                        'id' => $strippedId,
                        'name' => $namesAndIds['name']['value'] ?? $strippedId];
            }, (array) $namesAndIds);

        }
        return $entity;
    }

    private function getNamesForIds($entityIds)
    {
        $namesAndIds = [];
        foreach (array_chunk((array) $entityIds, 100) as $chunk) {
            $preparedIds = implode(" ", array_map(fn ($id) => "<$id>", $chunk));
            $namesAndIds = array_merge($namesAndIds, Queries::getNames($preparedIds));
        }
        return $namesAndIds;
    }

    public function mapTechniqueRelations($relations)
    {
        foreach ($relations as $key => $relation) {
            $entityId = $relation['entity']['value'];
            $propertyVal = 'usedIn';
            $relations[$key] = [
                "property" => ["type" => "uri", "value" => $propertyVal],
                "value" => ["type" => "uri", "value" => $entityId],
            ];
        }
        #dd($relations);
        return $relations;
    }
}
