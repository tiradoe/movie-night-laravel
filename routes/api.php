<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\MovieListController;
use App\Http\Controllers\ShowingController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(["auth:sanctum"])->group(function () {
    Route::get("/user", function (Request $request) {
        return $request->user();
    });
    Route::put("/user", [UserController::class, "updateUser"]);

    Route::get("/movies", [MovieController::class, "index"]);
    Route::get("/movies/search", [MovieController::class, "search"]);
    Route::get("/movies/{id}", [MovieController::class, "show"]);
    Route::delete("/movies/{id}", [MovieController::class, "destroy"]);

    Route::get("/lists", [MovieListController::class, "index"]);
    Route::get("/lists/{id}", [MovieListController::class, "show"]);
    Route::post("/lists", [MovieListController::class, "store"]);
    Route::post("/lists/{id}", [MovieListController::class, "addToList"]);
    Route::put("/lists", [MovieListController::class, "update"]);
    Route::delete("/lists/{id}/movie/{movieId}", [MovieListController::class, "removeMovie"]);
    Route::delete("/lists/{id}", [MovieListController::class, "destroy"]);

    Route::get("/showings", [ShowingController::class, "getShowings"]);
    Route::get("/showings/{id}", [ShowingController::class, "getShowing"]);
    Route::put("/showings/{id}", [ShowingController::class, "updateShowing"]);
    Route::post("/showings", [ShowingController::class, "createShowing"]);
    Route::delete("/showings/{id}", [ShowingController::class, "deleteShowing"]);

    Route::post("/schedules", [ScheduleController::class, "createSchedule"]);
    Route::get("/schedules", [ScheduleController::class, "getSchedule"]);
    Route::put("/schedules", [ScheduleController::class, "updateSchedule"]);
});

// Public Routes
Route::get("/lists/{id}", [MovieListController::class, "show"]);
Route::get("/schedules/user/{userIdentifier}/slug/{slug}", [ScheduleController::class, "getSchedule"]);
