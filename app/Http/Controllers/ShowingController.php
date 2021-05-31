<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Showing;
use Carbon\Carbon;
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
            "show_time" => Carbon::parse($request->input("show_time"))->setTimezone('UTC'),
            "owner" => $request->user()->id,
        ]);

        return response()->json([
            "showing" => $showing
        ]);
    }

    public function getShowings(Request $request)
    {
        if ($request->input('next') == 1) {
            try {
                $showing = Showing::where("owner", $request->user()->id)
                    // @TODO: Pull the timezone from a user setting
                    ->where('show_time', '>=', Carbon::today("America/Denver"))
                    ->orderBy('show_time')
                    ->firstOrFail();

                $movie = Movie::findOrFail($showing->movie_id);
                return response()->json([
                    "showing" => $showing,
                    "movie" => $movie
                ]);
            } catch (ModelNotFoundException $e) {
                return response()->json(["showing" => [], "movie" => []])->setStatusCode(404);
            }
        }

        $showings = Showing::where('owner', $request->user()->id)
            ->orderBy('show_time')
            ->get();

        foreach ($showings as $showing) {
            $showing->movie;
        }

        return response()->json([
            "showings" => $showings
        ]);
    }

    public function deleteShowing(Request $request, int $showing_id)
    {
        try {
            $showing = Showing::where("owner", $request->user()->id)
                ->where('id', $showing_id)
                ->firstOrFail();

            $showing->delete();

            return response()->json($showing);
        } catch (ModelNotFoundException $e) {
            return response()
                ->json(["data" => "Could not find movie to delete"])
                ->setStatusCode(404);
        }
    }
}
