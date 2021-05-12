<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\MovieListController;
use App\Http\Controllers\ShowingController;

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

Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    return $request->user();
});

Route::middleware("auth:sanctum")->get("/movies", [MovieController::class, "getMovies"]);
Route::middleware("auth:sanctum")->get("/movies/search", [MovieController::class, "search"]);
Route::middleware("auth:sanctum")->get("/movies/{id}", [MovieController::class, "getMovie"]);
Route::middleware("auth:sanctum")->post("/movies", [MovieController::class, "createMovie"]);
Route::middleware("auth:sanctum")->delete("/movies/{id}", [MovieController::class, "deleteMovie"]);

Route::middleware("auth:sanctum")->get("/lists", [MovieListController::class, "getMovieLists"]);
Route::middleware("auth:sanctum")->get("/lists/{id}", [MovieListController::class, "getMovieList"]);
Route::middleware("auth:sanctum")->post("/lists", [MovieListController::class, "createMovieList"]);
Route::middleware("auth:sanctum")->post("/lists/{id}", [MovieListController::class, "addToList"]);
Route::middleware("auth:sanctum")->delete("/lists/{id}/movie/{movieId}", [MovieListController::class, "removeMovie"]);
Route::middleware("auth:sanctum")->delete("/lists/{id}", [MovieListController::class, "deleteMovieList"]);

Route::middleware("auth:sanctum")->get("/showings", [ShowingController::class, "getShowings"]);
Route::middleware("auth:sanctum")->post("/showings", [ShowingController::class, "createShowing"]);
Route::middleware("auth:sanctum")->delete("/showings/{id}", [ShowingController::class, "deleteShowing"]);
