<?php

namespace App\Ontologies\Handlers;

use App\Ontologies\Helpers\HttpService;
use App\Ontologies\Handlers\Queries;
use App\Ontologies\Handlers\Parser;
use App\Ontologies\Helpers\ServiceHelper;
class Service
{
    private $entity = [
                "uri" => null,
                "displayId" => null,
                "title" => null,
                "data_properties" => [],
                "object_properties" => [],
                "builtin_object_properties" => [],
                ];

    /**
     * Updates the malware using the HttpService class.
     *
     * @return string The response from the HttpService postOwl method.
     */
    public function updateMalware(): string
    {
        return HttpService::postOwl(Parser::parseMalware());
    }

    /**
     * Searches for entities based on a search term, excluding specified entities.
     *
     * @param string $searchTerm The search term to match against entity titles.
     * @param mixed $entitiesToExclude The entities to exclude from the search results.
     * @return array The array of entities matching the search term, with their URIs, display IDs, and titles.
     */
    public function searchEntities(string $searchTerm, $entitiesToExclude)
    {
        $results = Queries::searchEntities($searchTerm, $entitiesToExclude);
        $entities = array_map(function ($result) {
            return [
                "uri" => $result['entity']['value'],
                "displayId" => ServiceHelper::getSubstrAfterLastSpecialChar($result['entity']['value']),
                "title" => $result['value']['value'],
            ];
        }, $results);
        return $entities;
    }

    /**
     * Retrieves the clean properties of an entity.
     *
     * @param int $id The ID of the entity.
     * @return array The clean properties of the entity.
     */
    public function getCleanEntityProperties($id): array
    {
        $properties = Queries::getRawEntityProperties($id);
        $this->mapExistingData($properties);
        return $this->entity;
    }

    /**
     * Maps existing data to the entity object based on the provided properties.
     *
     * @param array $properties The array of properties containing the data to be mapped.
     * @return void
     */
    public function mapExistingData(array $properties): void
    {
        if (!isset($properties)) return;

        $entityId = $properties[0]['entity']['value']; //ID of the named individual (not a property)
        $this->entity["uri"] = $entityId;
        $this->entity["displayId"] = ServiceHelper::getSubstrAfterLastSpecialChar($entityId);

        $builtinProperties = ServiceHelper::fromConfigGet('object_properties', "builtin");
        $searchables = ServiceHelper::fromConfigGet('searchable');

        foreach ($properties as $prop) {
            $propertyValue = $prop['property']['value'];
            $valueValue = $prop['value']['value'];
            $valueType = $prop['value']['type'];

            if ($valueType === "literal") {
                $this->processLiteral($propertyValue, $valueValue, $searchables);
            }
            elseif(array_key_exists($propertyValue, $builtinProperties)){
                $this->processBuiltinObjectProperty($propertyValue, $valueValue);
            }
            else{
                $this->processObjectProperty($propertyValue, $valueValue, $prop);
            }
        }

        if (!isset($this->entity['title'])) {
            $this->entity['title'] = $this->entity["displayId"];
        }
    }

    /**
     * Process a literal property value and update the entity accordingly.
     *
     * @param string $propertyValue The property value to process.
     * @param string $valueValue The value of the property.
     * @param array $searchables An array of searchable property values.
     * @return void
     */
    private function processLiteral($propertyValue, $valueValue, $searchables)
    {
        // If the literal is in searchables, we want to display it as the title on frontend.
        if (in_array($propertyValue, $searchables)) {
            $this->entity['title'] = $valueValue;
        }
        $strippedPropValue = $this->mapToConfigName($propertyValue, 'data_properties');
        $this->entity['data_properties'][$strippedPropValue] = $valueValue;
    }

    /**
     * Process a built-in object property.
     *
     * This method processes a built-in object property by extracting the value after the last special character,
     * mapping the property value to a configuration name, and adding the value to the entity's built-in object properties array.
     *
     * @param string $propertyValue The property value to process.
     * @param string $valueValue The value value to process.
     * @return void
     */
    private function processBuiltinObjectProperty($propertyValue, $valueValue)
    {
        $valueValue = ServiceHelper::getSubstrAfterLastSpecialChar($valueValue);
        $strippedPropValue = $this->mapToConfigName($propertyValue, 'object_properties');
        $this->entity['builtin_object_properties'][$strippedPropValue][] = $valueValue;
    }

    /**
     * Process the object property.
     *
     * @param string $propertyValue The value of the property.
     * @param string $valueValue The value of the object.
     * @param array $prop The property details.
     * @return void
     */
    private function processObjectProperty($propertyValue, $valueValue, $prop)
    {
        $strippedId = ServiceHelper::getSubstrAfterLastSpecialChar($valueValue);
        $objectEntityData = [
            'uri' => $valueValue,
            'id' => $strippedId,
            'name' => $prop['name']['value'] ?? $strippedId
        ];
        $strippedPropValue = $this->mapToConfigName($propertyValue, 'object_properties');
        $this->entity['object_properties'][$strippedPropValue][] = $objectEntityData;
    }
    /**
     * Maps the given property value to a configuration name.
     *
     * @param mixed $propValue The property value to map.
     * @param string $configProperty The configuration property to retrieve the mapping from.
     * @return mixed The mapped configuration name, or the original property value if no mapping is found.
     */
    private function mapToConfigName($propValue, $configProperty)
    {
        return ServiceHelper::fromConfigGet($configProperty)[$propValue] ?? $propValue;
    }
}
