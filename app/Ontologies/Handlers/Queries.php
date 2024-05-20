<?php

namespace App\Ontologies\Handlers;

use App\Ontologies\Helpers\HttpService;
use App\Traits\QueryDataInitialization;

class Queries
{
    use QueryDataInitialization;


    public static function searchEntities(string $searchTerm, $entitiesToExclude)
    {
        $query =
            'SELECT ?entity ?property ?value
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
            LIMIT 5';
        $result = HttpService::get($query);
        return $result;
    }


    public static function getRawEntityProperties($entityId)
    {
        $query =
            'SELECT ?entity ?property ?value ?name
                WHERE {
                    VALUES ?entity { <' . $entityId . '> }
                    ?entity ?property ?value .
                    OPTIONAL {
                        ?value ?dataProperty ?name .
                        FILTER(isLiteral(?name)) .
                        FILTER(?dataProperty IN (' . self::getPreparedSearchables(',') . ')) .
                    }
                }
            ORDER BY (STRLEN(?value))
        ';
        $result = HttpService::get($query);
        return $result;
    }
}
