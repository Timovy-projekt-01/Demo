<?php

namespace Tests\Unit;

use Tests\TestCase;

class BasicTest extends TestCase
{
    public function test_user_can_view_the_home_page(): void
    {
        $this->get('/')->assertStatus(200);
    }

    public function test_user_can_view_the_aboug_page(): void
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

        // Set the language to Spanish
        $this->followingRedirects()->get(route('lang.switch', 'sk'));

        // Get the about page
        $response = $this->get('/');

        // Assert that the about page contains Spanish text
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
}
