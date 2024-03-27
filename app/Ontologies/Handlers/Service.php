<?php

namespace App\Ontologies\Handlers;

use App\Exceptions\ScriptFailedException;
use App\Ontologies\Helpers\HttpService;
use App\Ontologies\Handlers\Queries;
use App\Ontologies\Handlers\Parser;
use App\Ontologies\Handlers\ServiceInterface;
use Illuminate\Support\Facades\Storage;

class Service implements InterfaceService
{
    private $objectProperties;
    private $fe_config;

    public function __construct()
    {
        $this->fe_config = json_decode(Storage::get('ontology/fe_config.json'), true);

    }

    public function updateMalware(): string
    {
        return HttpService::postOwl(Parser::parseMalware());
    }

    public function searchEntities(string $searchTerm, $entitiesToExclude)
    {
        $prefixes = implode(" ", array_column($this->fe_config, 'ontologyPrefix'));
        $searchables = '';
        // Create a single string with all the searchable properties
        foreach ($this->fe_config as $config) {
            $searchables .= implode(",\n", array_map(function ($searchable) use ($config) {
                return "{$config['name']}:$searchable";
            }, $config['searchable'])) . ",\n";
        }
        // Remove the trailing comma and newline, if any (because it brakes SPARQL queries)
        $searchables = rtrim($searchables, ",\n");
        //dd($searchables);
        $results = Queries::searchEntities($prefixes, $searchables, $searchTerm, $entitiesToExclude);
        return $results;
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
        $entity = $this->getNamesToIds($entity);
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

    public function getNamesToIds($entity): array
    {
        $objectProps = [];
        foreach($this->fe_config as $config){
            $objectProps += $config['object_properties'];
        }
        foreach ($objectProps as $objectProp => $value) {
            if (isset($entity[$objectProp])) {
                $entityIds = $entity[$objectProp];
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
                $entity[$objectProp] = array_map(fn ($id, $name) => ['id' => $id, 'name' => $name], (array) $entityIds, (array) $names);
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
