<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\MovieList;
use Illuminate\Http\Request;

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
    public function addToList(Request $request)
    {
        $list = MovieList::find($request->id);
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
        $list->movies()->attach($movie->id);

        if (empty($list)) {
            return response()->json(['status' => 404, 'data' => $list]);
        } else {
            return response()->json(['status' => 200, 'data' => $list->movies]);
        }
    }

    public function createMovieList(Request $request)
    {
        $movieList = new MovieList();

        $movieList->name = $request->name;
        $movieList->isPublic = $request->isPublic;
        $movieList->owner = $request->owner;

        $movieList->save();

        $response = [
            'status' => 200,
            'message' => 'Success!',
        ];

        return response()->json($response);
    }

    public function getMovieLists()
    {
        $movieLists = MovieList::all();

        return response()->json($movieLists);
    }

    public function getMovielist(Request $request)
    {
        $list = MovieList::find($request->id);
        if (empty($list)) {
            return response()->json(['status' => 404, 'list' => null, 'movies' => null]);
        }

        return response()->json(['status' => 200, 'list' => $list, 'movies' => $list->movies]);
    }

    public function updateMovieList()
    {
    }

    public function deleteMovieList(Request $request)
    {
        MovieList::destroy($request->id);

        $response = [
                'status' => 200,
                'message' => 'Successfully deleted list.',
            ];

        return response()->json($response);
    }
}
