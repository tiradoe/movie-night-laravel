<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\MovieList;
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
        } catch (ModelNotFoundException $e) {
            return response()->json(["data" => []])->setStatusCode(404);
        }

        $movie = Movie::find($request->input('id'));

        if (empty($movie)) {
            $movie = Movie::create([
                'title' => $request->input('title'),
                'year' => $request->input('year'),
                'rated' => $request->input('rated'),
                'genre' => $request->input('genre'),
                'director' => $request->input('director'),
                'actors' => $request->input('actors'),
                'plot' => $request->input('plot'),
                //'ratings' => $request->input('ratings'),
                'poster' => $request->input('poster'),
            ]);
        }

        //add this movie to list
        $movieList->movies()->attach($movie->id);

        return response()->json([
            'list' => $movieList,
            'movies' => $movieList->movies
        ]);
    }

    public function createMovieList(Request $request)
    {
        $movieList = new MovieList();

        $movieList->name = $request->name;
        $movieList->isPublic = $request->isPublic;
        $movieList->owner = $request->owner;

        $movieList->save();

        return response()->json(['lists' => MovieList::withCount('movies')->get()]);
    }

    public function getMovieLists()
    {
        $movieLists = MovieList::withCount('movies')->get();

        return response()->json($movieLists);
    }

    public function getMovielist(Request $request, $list_id)
    {
        $list = MovieList::find($list_id);
        if (empty($list)) {
            return response()->json(['list' => null, 'movies' => null])->setStatusCode(404);
        }

        return response()->json(['status' => 200, 'list' => $list, 'movies' => $list->movies]);
    }

    public function deleteMovieList(Request $request, $list_id)
    {
        try {
            $movieList = MovieList::findOrFail($list_id);
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
            $movie = Movie::findOrFail($movie_id);
            $movie->movieLists()->detach($list_id);
            $movieList = MovieList::findOrFail($list_id);

            return response()->json(["list" => $movieList, "movies" => $movieList->movies]);
        } catch (ModelNotFoundException $e) {
            return response()
                ->json(['data' => 'Could not find movie.'])
                ->setStatusCode(404);
        }
    }
}
