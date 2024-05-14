<?php

namespace Tests\Unit;

use Tests\TestCase;

class BasicTest extends TestCase
{
    public function test_home_page_accessibility(): void
    {
        $this->get('/')->assertStatus(200);
    }

    public function test_about_page_accessibility(): void
    {
        $this->get('/about')->assertStatus(200);
    }

    public function test_update_page_accessibility(): void
    {
        $this->get('/update')->assertStatus(200);
    }

    public function test_login_page_accessibility(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_non_existing_page_accessibility(): void
    {
        $this->get('/non-existing-page')->assertStatus(404);
    }

    public function test_language_correspondence_on_home_page()
    {
        $this->assertLanguageCorrespondence('Search History', 'História vyhľadávania', '/');
    }
    
    public function test_language_correspondence_on_about_page()
    {
        $this->assertLanguageCorrespondence('About project', 'O projekte', '/about');
    }

    public function test_language_correspondence_on_update_page()
    {
        $this->assertLanguageCorrespondence('Update', 'Aktualizovať', '/update');
    }
    
    private function assertLanguageCorrespondence($englishText, $slovakText, $page)
    {
        $this->followingRedirects()->get(route('lang.switch', 'en'));
        $response = $this->get($page);
        $response->assertSee($englishText);
        $this->followingRedirects()->get(route('lang.switch', 'sk'));
        $response = $this->get($page);
        $response->assertSee($slovakText);
    }

    public function test_api_response_with_existing_searched_term()
    {
        $client = new \GuzzleHttp\Client();
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

    public function test_api_response_with_non_existing_searched_term()
    {
        $client = new \GuzzleHttp\Client();
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
