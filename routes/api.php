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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/movies', [MovieController::class, 'getMovies']);
Route::get('/movies/search', [MovieController::class, 'search']);
Route::get('/movies/{id}', [MovieController::class, 'getMovie']);
Route::post('/movies', [MovieController::class, 'createMovie']);
Route::delete('/movies/{id}', [MovieController::class, 'deleteMovie']);

Route::get('/lists', [MovieListController::class, 'getMovieLists']);
Route::get('/lists/{id}', [MovieListController::class, 'getMovieList']);
Route::post('/lists', [MovieListController::class, 'createMovieList']);
Route::post('/lists/{id}', [MovieListController::class, 'addToList']);
Route::delete('/lists/{id}/movie/{movieId}', [MovieListController::class, 'removeMovie']);
Route::delete('/lists/{id}', [MovieListController::class, 'deleteMovieList']);

Route::get('/showings', [ShowingController::class, 'getShowings']);
Route::post('/showings', [ShowingController::class, 'createShowing']);
Route::delete('/showings/{id}', [ShowingController::class, 'deleteShowing']);
