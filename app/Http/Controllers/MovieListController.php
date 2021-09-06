<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\MovieList;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MovieListController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/lists/{listId}",
     *     tags={"lists"},
     *     summary="Adds a movie to a list",
     *     operationId="addToList",
     *     @OA\Parameter(
     *         name="listId",
     *         in="path",
     *         description="ID of list in which to place the movie",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Added to list"
     *     ),
     *     @OA\RequestBody(
     *         description="Movie to be added",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     description="Title of the movie",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="year",
     *                     description="Year the movie was made",
     *                     type="integer"
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function addToList(Request $request, $list_id)
    {
        try {
            $movieList = MovieList::findOrFail($list_id);
            if ($movieList->owner != $request->user()->id) {
                throw new ModelNotFoundException;
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(["data" => []])->setStatusCode(404);
        }

        $imdb_id = $request->input("imdbid");

        $movie = Movie::where('imdb_id', $imdb_id)->first();

        if (empty($movie)) {
            try {
                $movie = Movie::create([
                    'added_by' => $request->user()->id,
                    'title' => $request->input('title'),
                    'imdb_id' => $imdb_id,
                    'year' => intval($request->input('year')),
                    'rated' => $request->input('rated'),
                    'genre' => $request->input('genre'),
                    'director' => $request->input('director'),
                    'actors' => $request->input('actors'),
                    'plot' => $request->input('plot'),
                    //'ratings' => $request->input('ratings'),
                    'poster' => $request->input('poster'),
                ]);
            } catch (Exception $e) {
                return response()->json(["error" => $e]);
            }
        }

        //add this movie to list if it's not already on it
        if (empty($movieList->movies()->find($movie->id))) {
            $movieList->movies()->attach($movie->id);

            $movies = $movieList->movies;
            foreach ($movies as $movie) {
                $movie->nextShowing;
            }
            return response()->json([
                'list' => $movieList,
            ]);
        } else {
            return response()->json([
                'list' => $movieList,
            ])->setStatusCode(304);
        }
    }

    public function createMovieList(Request $request)
    {
        $movieList = new MovieList();

        $movieList->name = $request->name;
        $movieList->isPublic = $request->isPublic;
        $movieList->owner = $request->user()->id;

        $movieList->save();

        return response()->json([
            'lists' => MovieList::withCount('movies')
                ->where('owner', $request->user()->id)
                ->get()
        ]);
    }

    public function getMovieLists(Request $request)
    {
        $movieLists = MovieList::withCount('movies')
            ->where("owner", $request->user()->id)
            ->get();

        return response()->json($movieLists);
    }

    public function getMovieList(Request $request, $list_id)
    {
        try {
            $list = MovieList::where('id', $list_id)
                ->where('owner', $request->user()->id)
                ->firstOrFail();

            $list->movies;

            foreach ($list->movies as $movie) {
                $movie->showings;
            }
        } catch (ModelNotFoundException $e) {
            return response()
                ->json(["data" => "Could not find list"])
                ->setStatusCode(404);
        }

        if (empty($list)) {
            return response()->json([
                'list' => null,
            ])->setStatusCode(404);
        }

        return response()->json([
            'status' => 200,
            'list' => $list,
        ]);
    }

    public function deleteMovieList(Request $request, $list_id)
    {
        try {
            $movieList = MovieList::where("id", $list_id)
                ->where("owner", $request->user()->id)
                ->firstOrFail();
            $movieList->movies()->detach();
            $movieList->delete();

            return response()->json($movieList);
        } catch (ModelNotFoundException $e) {
            return response()
                ->json(["data" => "Could not find movie to delete"])
                ->setStatusCode(404);
        }
    }

    public function removeMovie(Request $request, $list_id, $movie_id)
    {
        try {
            $movieList = MovieList::where("id", $list_id)
                ->where("owner", $request->user()->id)
                ->firstOrFail();

            $deleteMovie = Movie::findOrFail($movie_id);
            $deleteMovie->movieLists()->detach($list_id);

            $movies = $movieList->movies;
            foreach ($movies as $movie) {
                $movie->nextShowing;
            }

            return response()->json(["list" => $movieList, "movies" => $movies]);
        } catch (ModelNotFoundException $e) {
            return response()
                ->json(['data' => 'Could not find movie on list.'])
                ->setStatusCode(404);
        }
    }
}
