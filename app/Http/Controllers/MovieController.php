<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    /**
     * Get a list of movies
     * @param Request $request
     * @author Ed Tirado
     */

    public function getMovies(Request $request)
    {
        $movies = [
            "id" => 1,
            "Title" => "Masters of the Universe",
            "Year" => 1987,
            "Rated" => "PG",
            "Genre" => "Action, Adventure, Fantasy, Sci-Fi",
            "Director" => "Gary Goddard",
            "Actors" => "Dolph Lundgren, Frank Langella, Meg Foster, Billy Barty",
            "Plot" => "The heroic warrior He-Man battles against the evil lord Skeletor and his armies of darkness for control of Castle Grayskull.",
            "Ratings" => [
                [
                    "Source" => "Internet Movie Database",
                    "Value" => "5.4/10"
                ],
                [
                    "Source" => "Rotten Tomatoes",
                    "Value" => "13%"
                ]
            ],
            "Poster" => "https://m.media-amazon.com/images/M/MV5BYzRlMzQzNDEtYTg5My00NTFjLWFiYzYtMjJkMzUyYzJjMzgyXkEyXkFqcGdeQXVyMTQxNzMzNDI@._V1_SX300.jpg",
        ];

        return response()->json($movies);
    }

    /**
     * Fetch a single movie
     * @param int $id
     * @author Ed Tirado
     */
    public function getMovie(int $id)
    {
        $movie = [
            "id" => $id,
            "title" => "The Shawshank Redemption"
        ];

        return response()->json($movie);
    }

    /**
     * Add a new movie to the database
     * @param Array $movie
     * @author Ed Tirado
     */
    public function createMovie(array $movie)
    {
        $movie = [
            "id" => $id,
            "title" => "The Shawshank Redemption"
        ];

        return response()->json($movie);
    }

    /**
     * Update information for a movie
     * @param Request $request
     * @author Ed Tirado
     */
    public function updateMovie()
    {
        $movie = [
            "id" => 6,
            "title" => "Who Killed Captain Alex?"
        ];

        return response()->json($movie);
    }

    /**
     * Delete a movie from the database
     * @author Ed Tirado
     */
    public function deleteMovie()
    {
        $movie = [
            "id" => 7,
            "title" => "Rambo"
        ];

        return response()->json($movie);
    }

    /**
     * Find a movie using the OMDb API (http://www.omdbapi.com/)
     * @param Request $request
     * @var $queryType - Find by IMDB id ("i") or by Movie Title ("t")
     * @author Ed Tirado
     */

    public function search(Request $request)
    {
        $imdbIdRegex = "/tt\d+/";

        $params = $request->all();
        $query = $params["query"];
        $queryType = "t";

        preg_match($imdbIdRegex, $params["query"], $matches);

        if (count($matches) > 0) {
            $queryType = "i";
        }

        $response = Http::get("http://www.omdbapi.com/", [
            "apikey" => config("app.omdb_key"),
            "plot" => "full",
            $queryType => $query
        ]);

        return $response->json();
    }
}
