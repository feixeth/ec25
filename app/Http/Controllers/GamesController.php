<?php
namespace App\Http\Controllers;
use App\Models\Games;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GamesController extends Controller
{

        /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
       $games = Games::all();
       return response()->json($games, 200);
    }

    /**
     * Store a newly created resource in storage.
     */

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

    /**
     * Display the specified resource.
     */
    public function show(Games $messages)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $game_id)
    {
        $validated = $request->validate([
            'name' => ['required','string'],
            'code' => ['required','string'],
        ]);
        $game = Games::where('id', $game_id)->firstOrFail();
        $game->update($validated);
        return response()->json(['message' => 'Game updated succesfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($game_id)
    {
        $game = Games::where('id', $game_id)->firstOrFail();
        $game->delete();
        return response()->json([
            'message' => 'Game successfully deleted'
        ], 200);
    }
}