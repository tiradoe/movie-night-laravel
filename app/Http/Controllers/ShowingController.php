<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Showing;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ShowingController extends Controller
{

    public function createShowing(Request $request)
    {
        try {
            $movie = Movie::findOrFail($request->input("movie_id"));
        } catch (ModelNotFoundException $e) {
            return response()->json(["error" => "Couldn't find movie"])->setStatusCode(404);
        }

        $showing = Showing::create([
            "movie_id" => $movie->id,
            "show_time" => $request->input("show_time"),
        ]);

        return response()->json([
            "showing" => $showing
        ]);
    }

    public function getShowings()
    {
        return response()->json([
            "showings" => Showing::all()
        ]);
    }
}
