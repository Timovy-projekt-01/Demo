<?php

namespace App\Ontologies\Helpers;

use Illuminate\Support\Facades\Http;
class HttpService {
    public function get($query): array {

        $blazegraphEndpoint = 'http://localhost:9999/bigdata/sparql';

        $response = Http::acceptJson()->get($blazegraphEndpoint, [
            'query' => $query,
        ]);
        $results = $response->json();
        $results = $results['results']['bindings'] ?? [];

        return $results['results']['bindings'];
    }

    public function postOwl($file_path)
    {
        $blazegraphEndpoint = 'http://localhost:9999/bigdata/sparql';

        $response = shell_exec('curl -X POST -H "Content-Type: application/rdf+xml" --data-binary @' . $file_path . ' ' . $blazegraphEndpoint);
        if (empty($response)){
            return false;
        }
    }

    public function postOwl($file_path)
    {
        $blazegraphEndpoint = 'http://localhost:9999/bigdata/sparql';

        $response = shell_exec('curl -X POST -H "Content-Type: application/rdf+xml" --data-binary @' . $file_path . ' ' . $blazegraphEndpoint);
        if (empty($response)){
            return false;
        }
    }

}
