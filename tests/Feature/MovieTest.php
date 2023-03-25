<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Movie;
use App\Models\User;

class MovieTest extends TestCase
{
    use WithFaker;

    /**
     * Test GET all endpoint.
     *
     * @return void
     */
    public function testGetAllEndpoint()
    {
        $response = $this->get('/api/movies');

        $response->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    [
                        "id",
                        "title",
                        "author",
                        "genre",
                        "isbn",
                        "description",
                        "publisher",
                        "image",
                        "created_at",
                        "updated_at",
                    ],
                ],
                "links" => [
                    "first",
                    'last',
                    'prev',
                    'next',
                ],
                "meta" => [
                    'total',
                    'last_page',
                    'per_page',
                    'current_page',
                    'from',
                    'to'
                ],
                "status",
                "message"
            ]);
    }

    /**
     * Test GET one endpoint.
     *
     * @return void
     */
    public function testGetOneEndpoint()
    {
        $response = $this->get("/api/movies/tt0000001");

        $response->assertStatus(200)
        ->assertJsonStructure([
            "data" => [
                    "id",
                    "title",
                    "author",
                    "genre",
                    "isbn",
                    "description",
                    "publisher",
                    "image",
                    "created_at",
                    "updated_at",
            ],
            "status",
            "message"
        ]);
    }

    /**
     * Test Search endpoint.
     *
     * @return void
     */
    public function testSeachEndpoint()
    {
        $response = $this->get("/api/search-movies?q=po");
        // dd($response);

        $response->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    [
                        "id",
                        "title",
                        "author",
                        "genre",
                        "isbn",
                        "description",
                        "publisher",
                        "image",
                        "created_at",
                        "updated_at",
                    ],
                ],
                "links" => [
                    "first",
                    'last',
                    'prev',
                    'next',
                ],
                "meta" => [
                    'total',
                    'last_page',
                    'per_page',
                    'current_page',
                    'from',
                    'to'
                ],
                "status",
                "message"
            ]);
    }
}
