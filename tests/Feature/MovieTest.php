<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Movie;

class MovieTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A request for a movie that exists should have a response status of 200
     *
     * @return void
     */
    public function test_can_find_existing_movie()
    {
        $movie = Movie::factory()->create();

        $this->get("/api/movies/$movie->id")
            ->assertStatus(200);
    }

    /**
     * If the movie is not found, the response status should be 404
     *
     * @return void
     */
    public function test_returns_404_if_movie_doesnt_exist()
    {
        $this->get('/api/movies/8405')
            ->assertStatus(404);
    }
}
