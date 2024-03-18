<?php

namespace App\Ontologies\Handlers;

use App\Ontologies\Helpers\HttpService;
use App\Ontologies\Helpers\RandomHelper;
use App\Ontologies\Traits\QueryDataInitialization;

class Queries
{
    use QueryDataInitialization;
    public static function getRelations(string $techniqueId, string $relationType1, string $relationType2, string $relationType3)
    {
        $query = self::getPreparedPrefixes() .
                'SELECT
                    ?entity
                WHERE {
                    {
                        ?entity malware:' . $relationType1 . ' ?id .
                        FILTER regex(str(?id), "' . $techniqueId . '")
                    }
                    UNION
                    {
                        ?entity malware:' . $relationType2 . ' ?id .
                        FILTER regex(str(?id), "' . $techniqueId . '")
                    }
                    UNION
                    {
                        ?entity malware:' . $relationType3 . ' ?id .
                        FILTER regex(str(?id), "' . $techniqueId . '")
                    }
                }';

        $result = HttpService::get($query);
        return $result;
    }

    public static function getNames($entityIds): array
    {
        $query = self::getPreparedPrefixes() .
            'SELECT
                    ?entity
                    ?name
                WHERE {
                    VALUES ?entity { ' . $entityIds . ' }
                    ?entity (' . self::getPreparedSearchables('|') . ') ?name .
                    }';
        $result = HttpService::get($query);
        return $result;
    }


    public static function searchEntities(string $searchTerm, $entitiesToExclude)
    {
        $query = self::getPreparedPrefixes() .
            'SELECT
                    ?entity ?property ?value
                WHERE {
                    ?entity ?property ?value .
                    FILTER (regex(?value, "^' . $searchTerm . '", "i")) .
                    FILTER (?property IN (
                        ' . self::getPreparedSearchables(',') . '
                    )) .

                    FILTER NOT EXISTS {
                        VALUES ?fetchedEntities {
                             ' . $entitiesToExclude . '
                        }
                        FILTER (?entity IN (?fetchedEntities))
                    }
                }
                LIMIT 3';

        $result = HttpService::get($query);
        return $result;
    }

    public static function getRawEntityProperties($entityId)
    {
        $query = self::getPreparedPrefixes() .
                'SELECT
                    ?entity ?property ?value
                WHERE {
                    BIND(<' . $entityId . '>  AS ?entity)
                    <' . $entityId . '> ?property ?value.
                    }
                ORDER BY (STRLEN(?value))';
        $result = HttpService::get($query);
        return $result;
    }
}

/*
Keby som nahodou potreboval orezavat prefixy
(IF(CONTAINS(STR(?e), "#"), STRAFTER(STR(?e), "#"), STR(?e)) AS ?entity)
(IF(CONTAINS(STR(?p), "#"), STRAFTER(STR(?p), "#"), STR(?p)) AS ?property)
(IF(CONTAINS(str(?v), "#"), STRAFTER(str(?v), "#"), str(?v)) AS ?value)
 */
