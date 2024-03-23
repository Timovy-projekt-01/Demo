<?php

namespace App\Ontologies\Handlers;
use App\Exceptions\ScriptFailedException;
use App\Ontologies\Helpers\HttpService;
interface InterfaceService {
    /**
     * Updates the malware using the provided parser.
     *
     * @return true If the malware was successfully updated. Otherwise, false.
     * @throws ScriptFailedException If the script fails to update the malware ontology.
     */
    public function updateMalware(): string;
    /**
     * Parses all entity properties to remove things like uri, literal, and
     * returns an associative array with only the property and its value.
     *
     * @param int $id The ID of the malware entity.
     * @return array The cleaned malware properties.
     */
    public function getCleanEntityProperties($id): array;

    /**
     * Maps fetched properties from DB to malware associative array.
     * We basically want to create a simple assoc array with all the properties to easily display them.
     * in views. We put the entityID ALWAYS as first element in the array.
     * @param array $malware The malware array.
     * @param array $properties The properties array.
     * @return array The updated malware array.
     */
    public function mapExistingData(array $properties): void;
}
