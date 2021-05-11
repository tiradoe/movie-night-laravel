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
            return response()->json([
                "error" => "Couldn't find movie"
            ])->setStatusCode(404);
        }

        $showing = Showing::create([
            "movie_id" => $movie->id,
            "show_time" => $request->input("show_time"),
        ]);

        return response()->json([
            "showing" => $showing
        ]);
    }

    public function getShowings(Request $request)
    {
        if ($request->input('next') == 1) {
            try {
                $showing = Showing::orderBy('show_time')->first();
                if ($showing) {
                    $movie = Movie::findOrFail($showing->movie_id);
                    return response()->json([
                        "showing" => $showing,
                        "movie" => $movie
                    ]);
                } else {
                    return response()->json(["showing" => $showing, "movie" => []])->setStatusCode(404);
                }
            } catch (ModelNotFoundException $e) {
                return response()->json([
                    "error" => "Could not find any showings"
                ]);
            }
        }

        $showings = Showing::orderBy('show_time')->get();
        foreach ($showings as $showing) {
            $showing->movie;
        }

        return response()->json([
            "showings" => $showings
        ]);
    }
}
