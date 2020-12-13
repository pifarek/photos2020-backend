<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiGetMediaTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->json('GET', 'api/media/list', ['category_id' => 1, 'order' => 'asc', 'order_by' => 'date']);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                ['id', 'filename', 'category', 'name', 'description', 'type', 'photo', 'youtube']
            ]
        ]);
    }
}
