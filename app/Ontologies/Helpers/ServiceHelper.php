<?php

namespace App\Ontologies\Helpers;

use App\Models\OntologyConfig;
use Illuminate\Support\Str;

class ServiceHelper
{
    public static function fromConfigGet($attribute, $ontologyName = null)
    {
        $qb = OntologyConfig::select('content');
        if ($ontologyName) {
            $content = $qb->where('name', $ontologyName)->limit(1)->pluck('content')->toArray()[0];
            return json_decode($content, true)[$attribute] ?? [];
        }

        $result = [];
        foreach ($qb->pluck('content') as $content) {
            $setting = json_decode($content, true);
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
