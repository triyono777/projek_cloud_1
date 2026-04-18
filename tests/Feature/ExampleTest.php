<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Projek Cloud 1');
    }

    public function test_health_endpoint_returns_json_response(): void
    {
        $response = $this->getJson('/health');

        $response->assertJsonStructure([
            'status',
            'app',
            'environment',
            'database' => [
                'connection',
                'status',
            ],
        ]);
    }
}
