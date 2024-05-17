<?php

namespace App\Ontologies\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceHelper
{
    public static function fromConfigGet($attribute, $ontologyName = null)
    {
        $config = json_decode(Storage::get('ontology/fe_config.json'), true);
        if ($ontologyName) {
            return $config[$ontologyName][$attribute] ?? [];
        }

        $result = [];
        foreach ($config as $setting) {
            if (isset($setting[$attribute])) {
                $result = array_merge($result, (array) $setting[$attribute]);
            }
        }
        return $result;
    }

    public static function getSubstrAfterLastSpecialChar($uris)
    {
        if (!is_array($uris)) {
            $uris = array($uris);
        }
        $delimiters = array('/', '?', '#', ':', ';', '=', '&');
        $result = array();
        foreach ($uris as $uri) {
            try {
                $reversed = strrev($uri);
                $uriAsArray = str_split($reversed);
                foreach ($uriAsArray as $char) {
                    if (in_array($char, $delimiters)) {
                        $literal = Str::afterLast($uri, $char);
                        $result[] = $literal;
                        break;
                    }
                }
            } catch (\Exception $e) {
                $result[] = $uri;
            }
        }
        return count($result) === 1 ? $result[0] : $result;
    }
}


# http://www.w3.org/2002/07/owl#NamedIndividual
