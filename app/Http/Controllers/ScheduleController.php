<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function getSchedules()
    {
        return response()->json(["message" => "woot! get schedules"]);
    }
    public function createSchedule()
    {
        return response()->json(["message" => "woot! create schedule"]);
    }
}
