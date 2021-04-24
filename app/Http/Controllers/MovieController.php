<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Movie;

class MovieController extends Controller
{
    /**
     *
    * @OA\Info(title="Movie Night", version="0.1")
    */

    /**
    * @OA\Get(
    *     path="/api/movies",
    *     tags={"movies"},
    *     summary="Find all movies",
    *     description="Returns a list of movies",
    *     @OA\Response(response="200", description="A list of movies")
    * )
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
     * @OA\Get(
     *     path="/api/movies/{id}",
    *     tags={"movies"},
     *     summary="Find movie by ID",
     *     description="Returns a single movie",
     *     operationId="getMovie",
     *     @OA\Parameter(
     *         description="Movie ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *           format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Movie not found"
     *     ),
     * )
     */
    public function getMovie(int $id)
    {
        try {
            $movie = Movie::findOrFail($id);
        } catch (Exception $e) {
            return response()->json(["status" => 404, data => "Movie not Found"]);
        }
        return response()->json($movie);
    }

    /**
    * @OA\Post(
    *     path="/api/movies/{id}",
    *     tags={"movies"},
    *     @OA\Property(
    *       property="id",
    *       type="integer",
    *       description="The movie's ID"
    *     ),
    *     @OA\Response(response="200", description="Movie added to database")
    * )
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
