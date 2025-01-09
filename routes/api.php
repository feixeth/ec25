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



Route::post('/addGame', [GamesController::class, 'store']);

Route::post('/addCoach', [CoachesController::class, 'store']);
Route::post('/deleteCoach/{user_id}', [CoachesController::class, 'destroy']);

Route::post('/addTeam', [TeamsController::class, 'store']);

Route::post('/teams/{teamId}/members', [TeamsMembersController::class, 'store']);
Route::delete('/teams/{teamId}/members', [TeamsMembersController::class, 'destroy']);
