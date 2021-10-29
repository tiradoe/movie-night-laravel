<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShowingCollection;
use App\Http\Resources\ShowingResource;
use App\Models\Movie;
use App\Models\Schedule;
use App\Models\Showing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\ItemNotFoundException;

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

        $schedule = Schedule::where('owner', $request->user()->id)
            ->firstOrFail();


        $showing = Showing::create([
            "movie_id" => $movie->id,
            "show_time" => Carbon::parse($request->input("show_time"))->setTimezone('UTC'),
            "owner" => $request->user()->id,
        ]);

        $showing->schedule_id = $schedule->id;
        $showing->save();


        return new ShowingResource($showing);
    }

    public function updateShowing(Request $request, int $showing_id)
    {
        try {
            $showing = Showing::where('owner', $request->user()->id)
                ->where('id', $showing_id)
                ->firstOrFail();
        } catch (ItemNotFoundException $e) {
            return response()->json([
                "error" => "Could not find showing."
            ])->setStatusCode(404);
        }

        $showing->isPublic = $request->input('isPublic');
        $showing->save();
    }

    public function getShowings(Request $request, String $uuid = "", $username = "")
    {
        // Public showings
        if (!$request->user()) {
            try {
                $user = User::where('uuid', $uuid)
                    ->where('username', $username)
                    ->firstOrFail();

                $showings = Showing::where('owner', $user->id)
                    ->orderBy('show_time')
                    ->get();

                if (count($showings) === 0) {
                    throw new ModelNotFoundException;
                }

                foreach ($showings as $showing) {
                    $showing->movie;
                }

                return new ShowingCollection($showings);
            } catch (ModelNotFoundException $e) {
                return response()
                    ->json(["data" => "Could not find showing."])
                    ->setStatusCode(404);
            }
        }

        // Next showing
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

        return new ShowingCollection($showings);
    }

    public function deleteShowing(Request $request, int $showing_id)
    {
        try {
            $showing = Showing::where("owner", $request->user()->id)
                ->where('id', $showing_id)
                ->firstOrFail();

            $showing->delete();

            return new ShowingResource($showing);
        } catch (ModelNotFoundException $e) {
            return response()
                ->json(["data" => "Could not find movie to delete"])
                ->setStatusCode(404);
        }
    }
}
