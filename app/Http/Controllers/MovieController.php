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
        $movies = Movie::all();

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
     * Delete a movie from the database
     * @author Ed Tirado
     */
    public function deleteMovie($id): JsonResponse
    {
        try {
            $movie = Movie::findOrFail($id);
            $movie->movieLists()->detach();
            $movie->delete();

            return response()->json($movie);
        } catch (ModelNotFoundException $e) {
            return response()
                ->json(["data"=>"Could not find movie to delete"])
                ->setStatusCode(404);
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
        ])->json();

        return response()->json(array_change_key_case($response, CASE_LOWER));
    }
}
