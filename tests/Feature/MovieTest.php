<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Movie;
use App\Models\MovieList;

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
            ->assertStatus(200)
            ->assertJson($movie->toArray());
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


    /**
     * /api/movies should fetch a list of all movies in the database
     *
     * @return void
     */
    public function test_can_get_all_movies()
    {
        $movies = Movie::factory()->count(4)->create();
        MovieList::factory()->count(3)->hasAttached($movies)->create();

        $this->get('/api/movies')
            ->assertStatus(200)
            ->assertJson($movies->toArray());
    }

    public function test_can_delete_movie()
    {
        $movie = Movie::factory()->create();
        $this->delete("/api/movies/$movie->id")
            ->assertStatus(200)
            ->assertJson($movie->toArray());
    }

    public function test_trying_to_delete_nonexistant_movie_returns_404()
    {
        $this->delete("/api/movies/63536")
            ->assertStatus(404);
    }

    public function test_can_search_for_movie_using_imdb_id()
    {
        $imdb_id = 'tt0093507'; // id for Masters of the Universe (1987)

        $response = $this->get("/api/movies/search?query=$imdb_id");

        $response->assertStatus(200);
        $this->assertSame($response["Title"], "Masters of the Universe");
    }
}
