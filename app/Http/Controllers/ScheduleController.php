<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\User;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\ItemNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class ScheduleController extends Controller
{
    public function createSchedule(Request $request)
    {
        $schedule = new Schedule();
        $uuid = Str::uuid();

        try {
            $schedule->uuid = $uuid;
            $schedule->name = $request->input('name');
            $schedule->slug = $request->input('slug') ?? $uuid;
            $schedule->isPublic = $request->input('isPublic');
            $schedule->owner = $request->user()->id;

            $schedule->save();
        } catch (Exception $e) {
            var_dump($e->getMessage());
            return response()->json(['error' => 'Could not create schedule.']);
        }

        return response()->json($schedule);
    }

    public function getSchedule(Request $request, String $userIdentifier = "", String $slug = "")
    {
        if (!$request->user()) {
            try {
                $schedule = $this->getPublicSchedule($userIdentifier, $slug);
                return response()->json(['schedule' => $schedule]);
            } catch (ItemNotFoundException $e) {
                return response()
                    ->json(["data" => "Could not find schedule."])
                    ->setStatusCode(404);
            }
        }

        try {
            $schedule = Schedule::where('owner', $request->user()->id)
                ->firstOrFail();

            $schedule->showings;
            foreach ($schedule->showings as $showing) {
                $showing->movie;
            }
            return response()->json($schedule);
        } catch (ItemNotFoundException $e) {
            return response()->json(['error' => "Could not find schedule."])->setStatusCode(404);
        }
    }

    public function updateSchedule(Request $request)
    {
        try {
            $schedule = Schedule::where('owner', $request->user()->id)
                ->where('uuid', $request->input('uuid'))
                ->firstOrFail();

            $schedule->update([
                'name' => $request->input('name'),
                'isPublic' => $request->input('isPublic'),
                'slug' => $request->input('slug'),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Could not update schedule.']);
        }
    }

    private function getPublicSchedule($userIdentifier, $slug)
    {
        $user = User::where(function ($query) use ($userIdentifier) {
            if (Uuid::isValid($userIdentifier)) {
                $query->where('uuid', $userIdentifier);
            } else {
                $query->where('username', $userIdentifier);
            }
        })
            ->firstOrFail();

        $schedule = Schedule::where(function ($query) use ($slug) {
            if (Uuid::isValid($slug)) {
                $query->where('uuid', $slug);
            } else {
                $query->where('slug', $slug);
            }
        })
            ->where('owner', $user->id)
            ->where('isPublic', true)
            ->firstOrFail();

        $schedule->public_showings;

        foreach ($schedule->public_showings as $showing) {
            $showing->movie;
        }

        return $schedule;
    }
}
