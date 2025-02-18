<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GamesController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\CoachesController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\TeamsMembersController;
use App\Http\Controllers\ConversationsController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/conversations/{conversation}', [ConversationsController::class, 'show']);
Route::post('conversations', [ConversationsController::class, 'store']);
Route::post('conversations/{conversation}/messages', [MessagesController::class, 'store']);
Route::post('conversations/{conversation}/read', [MessagesController::class, 'markAsRead']);




Route::get('messages', [MessagesController::class, 'index']);
Route::get('messages/unread', [MessagesController::class, 'getUnreadCount']);
Route::get('messages/conversation/{otherUser}', [MessagesController::class, 'getConversation']);
Route::post('messages', [MessagesController::class, 'store']);
Route::post('/messages/read/{message}', [MessagesController::class, 'markMessageAsRead']);




Route::post('/game', [GamesController::class, 'store']);
Route::put('/game/{game_id}', [GamesController::class, 'update']);
Route::delete('/game/{game_id}', [GamesController::class, 'destroy']);

Route::post('/coach', [CoachesController::class, 'store']);
Route::put('/coach/{user_id}', [CoachesController::class, 'update']);
Route::delete('/coach/{user_id}', [CoachesController::class, 'destroy']);

Route::post('/team', [TeamsController::class, 'store']);

Route::post('/teams/{teamId}/members', [TeamsMembersController::class, 'store']);
Route::delete('/teams/{teamId}/members', [TeamsMembersController::class, 'destroy']);
