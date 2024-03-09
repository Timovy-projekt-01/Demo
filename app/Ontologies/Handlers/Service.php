<?php

namespace App\Ontologies\Handlers;

use App\Exceptions\ScriptFailedException;
use App\Ontologies\Helpers\HttpService;
use App\Ontologies\Handlers\Queries;
use App\Ontologies\Handlers\Parser;
use App\Ontologies\Handlers\ServiceInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Service implements InterfaceService
{
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
        $prefixes = implode(" ", $this->fromConfigGet('ontologyPrefix'));
        $prefixedSearchables = $this->prepareSearchables();
        $results = Queries::searchEntities($prefixes, $prefixedSearchables, $searchTerm, $entitiesToExclude);
        return $results;
    }
    public function prepareSearchables()
    {
        $prefixedSearchables = '';
        foreach ($this->fe_config as $ontology => $value) {
            $name = $this->fromConfigGet('name', $ontology);
            $searchables = $this->fromConfigGet('searchable', $ontology);
            if (empty($name) || empty($searchables)) continue;

            $prefixedSearchables .= implode(",\n", array_map(function ($searchable) use ($name) {
                return "{$name}:{$searchable}";
            }, $searchables)) . ",\n";
        }
        $prefixedSearchables = Str::replaceLast(",\n", '', $prefixedSearchables);
        return $prefixedSearchables;
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
        $entity = $this->getDataForObjectProperties($entity);
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

    public function getDataForObjectProperties($entity): array
    {
        $objectProps = $this->fromConfigGet("object_properties");
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
        //todo tutu fetchujeme names a IDcka pre malware ale pre ine ontologie sa budu fetchovat mozno ine veci
        //Treba nejak z configu vybrat ze co. Urcite to bude meno, teda searchables, a ak maju tak ID.
        //malware ma ID ako dataproperty, ale CVE ma ID v about proste.
        //Kazdopadne ani jednu ontologiu nemame prepojenu a neviem ani jak to funguje.
        $namesAndIds = [];
        foreach (array_chunk((array) $entityIds, 100) as $chunk) {
            $preparedIds = implode(" ", array_map(fn ($id) => "<$id>", $chunk));
            $namesAndIds += Queries::getNames($preparedIds);
        }
        return $namesAndIds;
    }
    public function fromConfigGet($attribute, $ontologyName = null)
    {
        if ($ontologyName) {
            return $this->fe_config[$ontologyName][$attribute] ?? [];
        }

        $result = [];
        foreach ($this->fe_config as $config) {
            if (isset($config[$attribute])){
                $result = array_merge($result, (array) $config[$attribute]);
            }
        }
        return $result;
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
