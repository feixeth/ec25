<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GamesController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\CoachesController;
use App\Http\Controllers\TeamsMembersController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/game', [GamesController::class, 'store']);
Route::put('/game/{game_id}', [GamesController::class, 'update']);
Route::delete('/game/{game_id}', [GamesController::class, 'destroy']);

Route::post('/coach', [CoachesController::class, 'store']);
Route::put('/coach/{user_id}', [CoachesController::class, 'update']);
Route::delete('/coach/{user_id}', [CoachesController::class, 'destroy']);

Route::post('/team', [TeamsController::class, 'store']);

Route::post('/teams/{teamId}/members', [TeamsMembersController::class, 'store']);
Route::delete('/teams/{teamId}/members', [TeamsMembersController::class, 'destroy']);
