<?php

namespace App\Ontologies\Helpers;

use App\Exceptions\HttpException;
use App\Exceptions\ScriptFailedException;
use App\Traits\Script;
use Illuminate\Support\Facades\Http;
class HttpService {

    use Script;

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
     * @throws ScriptFailedException
     */
    public static function postOwl(string $file_path): bool
    {
        self::runCurlScript([
            '-X',
            'POST',
            '-H',
            '"Content-Type: application/rdf+xml"',
            '--data-binary',
            '@' . $file_path . ' ',
            getenv('CLIENT_REST_BLAZEGRAPH_UPLOAD_URL'),
        ]);

        return true;
    }

}
