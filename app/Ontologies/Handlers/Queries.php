<?php

namespace App\Ontologies\Handlers;

use App\Ontologies\Helpers\HttpService;
use Illuminate\Support\Facades\Cache;

class Queries
{
    private static $feiOntology = 'http://stufei/ontologies/malware#';

    public static function getRelations(string $techniqueId, string $relationType1, string $relationType2)
    {
        $query = 'PREFIX malware: <' . self::$feiOntology . '>
                SELECT
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
                }';

        $result = HttpService::get($query);
        return $result;
    }

    public static function getNames($entityIds): array
    {
        $query = 'PREFIX malware: <' . self::$feiOntology . '>
                SELECT
                    ?entity
                    ?name
                WHERE {
                    VALUES ?entity { ' . $entityIds . ' }
                    ?entity malware:hasName ?name .
                    }';
        $result = HttpService::get($query);
        return $result;
    }


    public static function searchEntities(string $uriPrefixes, string $searchables, string $searchTerm, $entitiesToExclude)
    {
        $query = $uriPrefixes .
        'SELECT
                    ?entity ?property ?value
                WHERE {
                    ?entity ?property ?value .
                    FILTER (regex(?value, "^' . $searchTerm . '", "i")) .
                    FILTER (?property IN (
                        ' . $searchables . '
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
        $query = 'PREFIX malware: <' . self::$feiOntology . '>
                SELECT
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
/* (IF(CONTAINS(STR(?e), "#"), STRAFTER(STR(?e), "#"), STR(?e)) AS ?entity)
                (IF(CONTAINS(STR(?p), "#"), STRAFTER(STR(?p), "#"), STR(?p)) AS ?property)
                (IF(CONTAINS(str(?v), "#"), STRAFTER(str(?v), "#"), str(?v)) AS ?value)
 */
