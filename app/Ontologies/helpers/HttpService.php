<?php

namespace App\Ontologies\Helpers;

use Illuminate\Support\Facades\Http;
class HttpService {
    public function get($query) {

        $blazegraphEndpoint = 'http://localhost:9999/bigdata/sparql';

        $response = Http::acceptJson()->get($blazegraphEndpoint, [
            'query' => $query,
        ]);
        $results = $response->json();

        if(!isset($results['results']['bindings'])) {
            return false;
        }

        return $results['results']['bindings'];
    }

}
