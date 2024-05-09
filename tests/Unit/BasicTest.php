<?php

namespace Tests\Unit;

use Tests\TestCase;

class BasicTest extends TestCase
{
    public function test_user_can_view_the_home_page(): void
    {
        $this->get('/')->assertStatus(200);
    }

    public function test_user_can_view_the_about_page(): void
    {
        $this->get('/about')->assertStatus(200);
    }

    public function test_user_can_view_the_update_page(): void
    {
        $this->get('/update')->assertStatus(200);
    }

    public function test_chosen_language_corresponds_with_about_home_text()
    {
        // Set the language to English
        $this->followingRedirects()->get(route('lang.switch', 'en'));

        // Get the about page
        $response = $this->get('/');

        // Assert that the about page contains English text
        $response->assertSee('Search History');

        // Set the language to Slovak
        $this->followingRedirects()->get(route('lang.switch', 'sk'));

        // Get the about page
        $response = $this->get('/');

        // Assert that the about page contains Slovak text
        $response->assertSee('História vyhľadávania');
    }

    public function test_chosen_language_corresponds_with_about_page_text()
    {
        // Set the language to English
        $this->followingRedirects()->get(route('lang.switch', 'en'));

        // Get the about page
        $response = $this->get('/about');

        // Assert that the about page contains English text
        $response->assertSee('About project');

        // Set the language to Spanish
        $this->followingRedirects()->get(route('lang.switch', 'sk'));

        // Get the about page
        $response = $this->get('/about');

        // Assert that the about page contains Spanish text
        $response->assertSee('O projekte');
    }

    public function test_client_gets_correct_response_for_api_endpoint()
    {
        // Create a Guzzle HTTP client
        $client = new \GuzzleHttp\Client();

        // Send a GET request to the API endpoint
        $response = $client->get('http://localhost:9999/bigdata/sparql', [
            'query' => [
                'query' => 'SELECT * WHERE {?s ?p ?o} LIMIT 1',
            ],
        ]);

        // Assert the response status is 200 (OK)
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_client_gets_correct_response_for_api_endpoint_with_existing_searched_term(){
        // Create a Guzzle HTTP client
        $client = new \GuzzleHttp\Client();

        // Send a GET request to the API endpoint
        $response = $client->get('http://localhost:9999/bigdata/sparql', [
            'query' => [
                'query' => 'SELECT ?entity ?property ?value
                WHERE { 
                  ?entity ?property ?value .
                  FILTER (regex(?value, "^Linux", "i")) .
                  FILTER (?property IN (<http://stufei/ontologies/malware#hasName>, 
                  <http://stufei/ontologies/malware#name>, 
                  <http://stufei/ontologies/malware#hasSubmitName>)) .
                }
                LIMIT 3',
            ],
            'headers' => [
                'Accept' => 'application/sparql-results+json',
            ],
        ]);

        $shouldBeEqualTo = '{
            "head": {
                "vars": [
                    "entity",
                    "property",
                    "value"
                ]
            },
            "results": {
                "bindings": [
                    {
                        "entity": {
                            "type": "uri",
                            "value": "http://stufei/ontologies/malware#S0362"
                        },
                        "property": {
                            "type": "uri",
                            "value": "http://stufei/ontologies/malware#hasName"
                        },
                        "value": {
                            "type": "literal",
                            "value": "Linux Rabbit"
                        }
                    }
                ]
            }
        }';
  
        $this->assertEquals(json_decode($shouldBeEqualTo, true), 
        json_decode($response->getBody()->getContents(), true));

    }

    public function test_client_gets_correct_response_for_api_endpoint_with_non_existing_searched_term(){
        // Create a Guzzle HTTP client
        $client = new \GuzzleHttp\Client();

        // Send a GET request to the API endpoint
        $response = $client->get('http://localhost:9999/bigdata/sparql', [
            'query' => [
                'query' => 'SELECT ?entity ?property ?value
                WHERE { 
                  ?entity ?property ?value .
                  FILTER (regex(?value, "^NotExisting", "i")) .
                  FILTER (?property IN (<http://stufei/ontologies/malware#hasName>, 
                  <http://stufei/ontologies/malware#name>, 
                  <http://stufei/ontologies/malware#hasSubmitName>)) .
                }
                LIMIT 3',
            ],
            'headers' => [
                'Accept' => 'application/sparql-results+json',
            ],
        ]);

        $shouldBeEqualTo = '{
            "head": {
                "vars": [
                    "entity",
                    "property",
                    "value"
                ]
            },
            "results": {
                "bindings": []
            }
        }';
  
        $this->assertEquals(json_decode($shouldBeEqualTo, true), 
        json_decode($response->getBody()->getContents(), true));

    }
}
