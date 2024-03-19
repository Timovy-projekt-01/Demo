<?php

namespace App\Ontologies\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RandomHelper
{
    public static function fromConfigGet($attribute, $ontologyName = null)
    {
        $config = json_decode(Storage::get('ontology/fe_config.json'), true);
        if ($ontologyName) {
            return $config[$ontologyName][$attribute] ?? [];
        }

        $result = [];
        foreach ($config as $config) {
            if (isset($config[$attribute])) {
                $result = array_merge($result, (array) $config[$attribute]);
            }
        }
        return $result;
    }

    public static function isTechnique(array $result)
    {
        foreach ($result as $item) {
            $propertyValues = array_column($item, 'value', 'property');
            if (strcmp($propertyValues[2], "http://stufei/ontologies/malware#Technique") == 0) {
                return true; // Is technique
            }
        }
        return false; // Is not technique
    }

    public static function getSubstrAfterLastSpecialChar($uri)
    {
        try {
            $delimiters = array('/', '?', '#', ':', ';', '=', '&');
            $reversed = strrev($uri);
            $uriAsArray = str_split($reversed);
            foreach ($uriAsArray as $char) {
                if (in_array($char, $delimiters)) {
                    $literal = Str::afterLast($uri, $char);
                    return $literal;
                }
            }
        } catch (\Exception $e) {
            return $uri;
        }
    }
}
