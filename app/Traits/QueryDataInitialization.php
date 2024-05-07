<?php

namespace App\Traits;

use App\Ontologies\Helpers\ServiceHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait QueryDataInitialization
{
    public static $uriPrefixes;
    public static $searchables;


    /**
     * Get the prepared prefixes as a string.
     *
     * @return string The prepared prefixes.
     */
    public static function getPreparedPrefixes()
    {
        return implode(" ", ServiceHelper::fromConfigGet('ontologyPrefix'));
    }

    /**
     * Get the prepared searchables as a string with a specified delimiter.
     *
     * @param string $delimiter The delimiter to use for separating the searchables.
     * @return string The prepared searchables as a string.
     */
    public static function getPreparedSearchables($delimiter)
    {
        $config = json_decode(Storage::get('ontology/fe_config.json'), true);
        $prefixedSearchables = '';
        foreach ($config as $ontology => $value) {
            $searchables = ServiceHelper::fromConfigGet('searchable', $ontology);
            if (empty($searchables)) continue;

            $prefixedSearchables .= implode($delimiter, array_map(function ($searchable) {
                return "<{$searchable}>";
            }, $searchables)) . $delimiter;
        }
        $prefixedSearchables = Str::replaceLast($delimiter, '', $prefixedSearchables);
        return $prefixedSearchables;
    }
}
