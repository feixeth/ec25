<?php
namespace App\Http\Controllers;
use App\Models\Games;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GamesController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'code' => ['required', 'string', 'max:20'],
            'logo' => ['nullable', 'string', 'max:255']
        ]);
        
        $game = Games::create($validated);
        
        if (!$game) {
            \Log::error('Failed to create game', ['data' => $validated]);
        }
        
        return response()->json(['message' => 'Game created successfully'], 201);
    }
}