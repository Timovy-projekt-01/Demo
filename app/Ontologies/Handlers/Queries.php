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
                    (IF(CONTAINS(STR(?e), "#"), STRAFTER(STR(?e), "#"), STR(?e)) AS ?entity)
                WHERE {
                    {
                        ?e malware:' . $relationType1 . ' ?id .
                        FILTER regex(str(?id), "' . $techniqueId . '")
                    }
                    UNION
                    {
                        ?e malware:' . $relationType2 . ' ?id .
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
                    (IF(CONTAINS(STR(?e), "#"), STRAFTER(STR(?e), "#"), STR(?e)) AS ?entity)
                    ?name
                WHERE {
                    VALUES ?e { ' . $entityIds . ' }
                    ?e malware:hasName ?name .
                    }';

        $result = HttpService::get($query);
        return $result;
    }

    public static function searchEntities(string $searchTerm, $entitiesToExclude)
    {
        $query = 'PREFIX malware: <' . self::$feiOntology . '>
                SELECT
                    (IF(CONTAINS(STR(?e), "#"), STRAFTER(STR(?e), "#"), STR(?e)) AS ?entity)
                    (IF(CONTAINS(STR(?p), "#"), STRAFTER(STR(?p), "#"), STR(?p)) AS ?property)
                    (IF(CONTAINS(str(?v), "#"), STRAFTER(str(?v), "#"), str(?v)) AS ?value)
                WHERE {
                    ?e ?p ?v .
                    FILTER (regex(?v, "^' . $searchTerm . '", "i")) .
                    FILTER (?p IN (
                        malware:hasName,
                        malware:name,
                        malware:hasSubmitName
                    )) .

                    FILTER NOT EXISTS {
                        VALUES ?fetchedEntities {
                             ' . $entitiesToExclude . '
                        }
                        FILTER (?e IN (?fetchedEntities))
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
                (IF(CONTAINS(STR(?e), "#"), STRAFTER(STR(?e), "#"), STR(?e)) AS ?entity)
                (IF(CONTAINS(STR(?p), "#"), STRAFTER(STR(?p), "#"), STR(?p)) AS ?property)
                (IF(CONTAINS(str(?v), "#"), STRAFTER(str(?v), "#"), str(?v)) AS ?value)
                WHERE {
                BIND(malware:' . $entityId . '  AS ?e)
                malware:' . $entityId . ' ?p ?v.
                }
                ORDER BY (STRLEN(?value))';

        $result = HttpService::get($query);
        return $result;
    }
}

