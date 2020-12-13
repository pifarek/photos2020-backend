<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiGetCategoriesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->json('GET', 'api/categories/list', ['published' => 'true']);

        $response->assertStatus(200);

        $response->assertJsonStructure([[
            'id', 'name', 'created_at', 'updated_at', 'taken_at', 'media_count', 'children'
        ]]);
    }
}
