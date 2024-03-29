<?php

namespace App\Ontologies\Traits;

use App\Ontologies\Helpers\RandomHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
trait QueryDataInitialization
{
    public static $uriPrefixes;
    public static $searchables;


    public static function getPreparedPrefixes()
    {
        return implode(" ", RandomHelper::fromConfigGet('ontologyPrefix'));
    }

    public static function getPreparedSearchables($delimiter)
    {
        $config = json_decode(Storage::get('ontology/fe_config.json'), true);
        $prefixedSearchables = '';
        foreach ($config as $ontology => $value) {
            $searchables = RandomHelper::fromConfigGet('searchable', $ontology);
            if (empty($searchables)) continue;

            $prefixedSearchables .= implode($delimiter, array_map(function ($searchable) {
                return "<{$searchable}>";
            }, $searchables)) . $delimiter;
        }
        $prefixedSearchables = Str::replaceLast($delimiter, '', $prefixedSearchables);
        return $prefixedSearchables;
    }
}
