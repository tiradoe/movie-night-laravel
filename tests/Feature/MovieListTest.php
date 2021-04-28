<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Movie;
use App\Models\MovieList;

class MovieListTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_get_all_lists()
    {
        $movieList = MovieList::factory()->count(5)->create();

        $this->get('/api/lists')
            ->assertStatus(200)
            ->assertJson($movieList->toArray());
    }

    public function test_can_add_movie_to_list()
    {
        $movie = Movie::factory()->create();
        $movieList = MovieList::factory()->create();
        $url = "/api/lists/$movieList->id";

        $this->post($url, $movie->toArray())
            ->assertStatus(200)
            ->assertJson(['movies' => [$movie->toArray()]]);
    }

    public function test_can_add_movie_to_list_when_not_already_in_database()
    {
        $movie = Movie::factory()->make();
        $movieList = MovieList::factory()->create();
        $url = "/api/lists/$movieList->id";

        $response = $this->post($url, $movie->toArray());

        $response->assertStatus(200);
        $this->assertSame($response["movies"][0]["title"], $movie->title);
    }

    public function test_add_to_list_returns_404_if_list_doesnt_exist()
    {
        $movie = Movie::factory()->make();
        $response = $this->post("/api/lists/35353", $movie->toArray());
        $response->assertStatus(404);
    }

    public function test_can_create_movie_list()
    {
        $movieList = MovieList::factory()->create();
        $this->post("/api/lists/", $movieList->toArray())
                ->assertStatus(200);
    }

    public function test_can_find_existing_list()
    {
        $movieList = MovieList::factory()->create();

        $this->get("/api/lists/$movieList->id")
            ->assertStatus(200)
            ->assertJson(["list" => $movieList->toArray()]);
    }

    public function test_can_list_not_found_returns_404()
    {
        $this->get("/api/lists/3535265")
            ->assertStatus(404);
    }

    public function test_can_delete_movie_list()
    {
        $movieList = MovieList::factory()->create();
        $this->delete("/api/lists/$movieList->id")
            ->assertStatus(200)
            ->assertJson($movieList->toArray());
    }

    public function test_movie_list_not_found_when_deleting_returns_404()
    {
        $this->delete("/api/lists/63536")
            ->assertStatus(404);
    }

    public function test_can_remove_movie_from_movie_list()
    {
        $movies = Movie::factory()->count(3)->create();

        $movieList = MovieList::factory()->hasAttached($movies)->create();
        $deleteMovieId = $movies[0]["id"];

        $this->delete("/api/lists/$movieList->id/movie/$deleteMovieId")
            ->assertStatus(200)
            ->assertJson(["list" => $movieList->toArray()])
            ->assertJson(["movies" => $movieList->movies->toArray()]);
    }

    public function test_movie_not_found_when_removing_movie_from_list_returns_404()
    {
        $this->delete("/api/lists/63536/movie/3452352")
            ->assertStatus(404);
    }
}
