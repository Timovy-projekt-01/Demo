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
        $response->assertSee('About');

        // Set the language to Spanish
        $this->followingRedirects()->get(route('lang.switch', 'sk'));

        // Get the about page
        $response = $this->get('/about');

        // Assert that the about page contains Spanish text
        $response->assertSee('O nás');
    }
}
