<?php

namespace App\Ontologies\Helpers;

use App\Exceptions\HttpException;
use Illuminate\Support\Facades\Http;
class HttpService {
    public static function get($query): array {

        $blazegraphEndpoint = 'http://localhost:9999/bigdata/sparql';

        $response = Http::acceptJson()->get($blazegraphEndpoint, [
            'query' => $query,
        ]);
        $results = $response->json();
        $results = $results['results']['bindings'] ?? [];

        return $results;
    }

    /**
     * Function to post an OWL file to BlazeGraph
     * Performs a POST request to BlazeGraph with the given file
     * https://github.com/blazegraph/database/wiki/REST_API
     *
     * @param string $file_path
     * @return bool
     */
    public static function postOwl(string $file_path): bool
    {
        $blazegraphEndpoint = getenv('CLIENT_REST_BLAZEGRAPH_UPLOAD_URL');

        $response = shell_exec('curl -X POST -H "Content-Type: application/rdf+xml" --data-binary @' . $file_path . ' ' . $blazegraphEndpoint);
        if (empty($response)){
            throw new HttpException('Failed to upload file', [
                'response' => [
                    'error' => true,
                    'url' => $blazegraphEndpoint,
                ],
            ]);
        }

        return true;
    }

}
