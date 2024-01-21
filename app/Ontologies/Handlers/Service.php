<?php

namespace App\Ontologies\Handlers;

use App\Exceptions\ScriptFailedException;
use App\Ontologies\Helpers\HttpService;
use App\Ontologies\Handlers\Queries;
use App\Ontologies\Handlers\Parser;
use App\Ontologies\Handlers\ServiceInterface;

class Service implements InterfaceService
{
    private $objectProperties;
    private $fe_config;

    public function __construct()
    {
        $this->objectProperties = json_decode(file_get_contents(base_path("\\app\\bin\\malware\\output\\object_properties.json")), true);
        $this->fe_config = json_decode(file_get_contents(base_path("\\app\\bin\\malware\\output\\fe_config.json")), true);

    }

    public function updateMalware(): string
    {
        return HttpService::postOwl(Parser::parseMalware());
    }

    public function getCleanEntityProperties($id): array
    {
        $entity = [];
        $properties = Queries::getRawEntityProperties($id);

        if ($this->isTechnique($properties)) {
            $relationNames = Queries::getRelations($id, 'mitigates', 'usesTechnique');
            $relationNames = $this->mapTechniqueRelations($relationNames);
            $properties = array_merge($properties, $relationNames);
        }
        $entity = $this->mapExistingData($entity, $properties);
        $entity = $this->getNamesToIds($entity, $this->objectProperties);
        return $entity;
    }

    public function isTechnique(array $result)
    {
        foreach ($result as $item) {
            $propertyValues = array_column($item, 'value', 'property');
            if (strcmp($propertyValues[1], "Technique") == 0) {
                return true; // Is technique
            }
        }
        return false; // Is not technique
    }

    public function mapExistingData(array $entity, array $properties): array
    {
        if (empty($properties)) {
            throw new ScriptFailedException("No properties found for the given ID", "");
        }

        $entityId = $properties[0]['entity']['value']; //ID of the named individual (not a property)
        $entity[$entityId] = [$entityId];
        foreach ($properties as $prop) {
            $propertyName = $prop['property']['value']; //hasAliases, hasName, hasDescription
            $propertyValue = $prop['value']['value'];   //Wcr, Wnncr, Wnncr

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

    public function getNamesToIds($entity, array $colapsProps): array
    {
        foreach ($colapsProps as $colapsProp) {
            if (isset($entity[$colapsProp])) {
                $entityIds = $entity[$colapsProp];
                $results = [];

                foreach (array_chunk((array) $entityIds, 100) as $chunk) {
                    $ids = implode(" ", array_map(fn ($id) => "<http://stufei/ontologies/malware#{$id}>", $chunk));
                    $chunkResult = Queries::getNames($ids);
                    $results = array_merge($results, $chunkResult);
                }

                $names = [];
                foreach ($results as $result) {
                    $names[] = $result['name']['value'];
                }
                $entity[$colapsProp] = array_map(fn ($id, $name) => ['id' => $id, 'name' => $name], (array) $entityIds, (array) $names);
            }
        }

        return $entity;
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
