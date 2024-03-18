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
        return Queries::searchEntities($searchTerm, $entitiesToExclude);
    }
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
        $entity = $this->getDataForObjectProperties($entity);
        $entity = $this->mapToReadableNames($entity);
        return $entity;
    }

    public function mapToReadableNames($entity): array
    {
        $entity = $this->parseAndMapProperties($entity, "data_properties");
        foreach($entity['object_properties'] as $entityObjecyProp => $value){
            $entity = $this->parseAndMapProperties($entity, "object_properties");
        }
        //dd($entity);
        return $entity;
    }
    public function parseAndMapProperties($entity, $propertyType)
    {
        $configProperties = RandomHelper::fromConfigGet($propertyType);

        foreach($entity[$propertyType] as $entityProp => $value){
            foreach($configProperties as $configProp => $configPropValue){
                if($entityProp === $configProp){
                    $tmpValue = $entity[$propertyType][$entityProp];
                    unset($entity[$propertyType][$entityProp]);
                    $entity[$propertyType][$configPropValue] = $tmpValue;
                }
            }
        }
        return $entity;
    }

    public function mapExistingData(array $entity, array $properties): array
    {
        $entityId = $properties[0]['entity']['value']; //ID of the named individual (not a property)
        $entity[$entityId] = $entityId;
        foreach ($properties as $prop) {
            $propertyName = $prop['property']['value'];
            $propertyValue = $prop['value']['value'];
            $valueType = $prop['value']['type'];

            if ($valueType === "uri") {
                $entity['object_properties'][$propertyName][] = $propertyValue;
            }
            else{
                $entity['data_properties'][$propertyName] = $propertyValue;
            }
        }
        return $entity;
    }

    public function getDataForObjectProperties($entity): array
    {
        $objectProps = RandomHelper::fromConfigGet("object_properties");
        foreach ($objectProps as $objectProp => $value) {
            if (isset($entity['object_properties'][$objectProp])) {
                $entityIds = $entity['object_properties'][$objectProp];
                $namesAndIds = $this->getNamesForIds($entityIds);

                if (!empty($namesAndIds)) {
                    $entity['object_properties'][$objectProp] = array_map(function ($id, $result) {
                        return ['uriId' => $id, 'id' => $id, 'name' => $result['name']['value']];
                    }, (array) $entityIds, (array) $namesAndIds);
                }
            }
        }
        return $entity;
    }

    private function getNamesForIds($entityIds)
    {
        $namesAndIds = [];
        foreach (array_chunk((array) $entityIds, 100) as $chunk) {
            $preparedIds = implode(" ", array_map(fn ($id) => "<$id>", $chunk));
            $namesAndIds += Queries::getNames($preparedIds);
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
