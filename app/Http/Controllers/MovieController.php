<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Movie;
use App\Models\MovieMovieList;

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

    public function getMovies(Request $request): JsonResponse
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
    public function getMovie($id): JsonResponse
    {
        try {
            $movie = Movie::findOrFail($id);
            return response()->json($movie);
        } catch (ModelNotFoundException $e) {
            return response()
                ->json(["data" => "Movie not Found"])
                ->setStatusCode(404);
        }
    }

    /**
     * Update information for a movie
     * @param Request $request
     * @author Ed Tirado
     */
    public function updateMovie(): JsonResponse
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
    public function deleteMovie($id): JsonResponse
    {
        try {
            $movie = MovieMovieList::find($id);
            $movie->delete();
            return response()->json($movie);
        } catch (ModelNotFoundException $e) {
            return response()->json(["data"=>"Could not find movie to delete"]);
        }
    }

    /**
     * Find a movie using the OMDb API (http://www.omdbapi.com/)
     * @param Request $request
     * @var $queryType - Find by IMDB id ("i") or by Movie Title ("t")
     * @author Ed Tirado
     */

    public function search(Request $request): JsonResponse
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
        ])->collect();

        return response()->json($response);
    }
}
