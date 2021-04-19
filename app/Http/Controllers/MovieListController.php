<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\MovieList;

class MovieListController extends Controller
{
    public function createMovieList(Request $request)
    {
        $movieList = new MovieList;

        $movieList->name = $request->name;
        $movieList->isPublic = $request->isPublic;
        $movieList->owner = $request->owner;

        $movieList->save();

        $response = [
            "status" => 200,
            "message" => "Success!"
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
        return response()->json(MovieList::findOrFail($request->id));
    }

    public function updateMovieList()
    {
    }

    public function deleteMovieList(Request $request)
    {
        MovieList::destroy($request->id);

        $response = [
                "status" => 200,
                "message" => "Successfully deleted list."
            ];

        return response()->json($response);
    }
}
