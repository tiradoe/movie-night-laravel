<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function index(): MovieResource
    {
        $movies = Movie::all();

        return new MovieResource($movies);
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
    public function show($id): MovieResource
    {
        try {
            $movie = Movie::findOrFail($id);
            $movie->nextShowing;

            return new MovieResource(($movie));
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
    public function destroy($id): MovieResource
    {
        try {
            $movie = Movie::findOrFail($id);
            $movie->movieLists()->detach();
            $movie->delete();

            return new MovieResource($movie);
        } catch (ModelNotFoundException $e) {
            return response()
                ->json(["data" => "Could not find movie to delete"])
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
        $queryType = "t";

        $query = $request->input('query');
        $year = $request->input('year');
        $type = $request->input('type');

        // If it's an IMDb id, update the query type
        preg_match($imdbIdRegex, $query, $matches);

        if (count($matches) > 0) {
            $queryType = "i";
        }

        $response = Http::get("http://www.omdbapi.com/", [
            "apikey" => config("app.omdb_key"),
            "plot" => "full",
            "type" => $type,
            "y" => $year,
            $queryType => $query
        ])->json();

        return response()->json(array_change_key_case($response, CASE_LOWER));
    }
}
