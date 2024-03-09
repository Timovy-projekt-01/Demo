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
            $relationNames = Queries::getRelations($id, 'mitigates', 'usesTechnique');
            $relationNames = $this->mapTechniqueRelations($relationNames);
            $properties = array_merge($properties, $relationNames);
        }
        $entity = $this->mapExistingData($entity, $properties);
        $entity = $this->getDataForObjectProperties($entity);
        return $entity;
    }

    public function mapExistingData(array $entity, array $properties): array
    {
        if (empty($properties)) {
            throw new ScriptFailedException("No properties found for the given ID", "");
        }

        $entityId = $properties[0]['entity']['value']; //ID of the named individual (not a property)
        $entity[$entityId] = [$entityId];
        foreach ($properties as $prop) {
            $propertyName = $prop['property']['value']; 
            $propertyValue = $prop['value']['value'];

            // Check if the property already exists in $entity
            if (array_key_exists($propertyName, $entity)) {
                // If it exists, append the value to the existing array
                $entity[$propertyName][] = $propertyValue;
            } else {
                // If it doesn't exist, create a new array with the value
                $entity[$propertyName] = [$propertyValue];
            }
        }
        // Map the values into an associative array except arrays with more than one element.
        $entity = array_map(function ($values) {
            return count($values) > 1 ? $values : $values[0] ?? null;
        }, $entity);
        return $entity;
    }

    public function getDataForObjectProperties($entity): array
    {
        $objectProps = RandomHelper::fromConfigGet("object_properties");
        foreach ($objectProps as $objectProp => $value) {
            if (isset($entity[$objectProp])) {
                $entityIds = $entity[$objectProp];
                $namesAndIds = $this->getNamesForIds($entityIds);

                $entity[$objectProp] = array_map(fn ($id, $result) =>
                    ['id' => $id, 'name' => $result['name']['value']],
                    (array) $entityIds, (array) $namesAndIds
                );
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
        return $relations;
    }
}
