<?php

namespace App\Http\Controllers;

use App\Models\Coaches;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CoachesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coaches = Coaches::all();
        return response()->json($coaches, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) : JsonResponse
    {

    $validated = $request->validate([
            'user_id' => ['required','int','unique:coaches'],
            'game_id' => ['required','int'],
            'status' => ['required','in:Available,Not available,N/A'],
            'achievement' => ['required','string'],
        ],
        [
            'user_id.required' => 'L\'identifiant utilisateur est obligatoire.',
            'user_id.unique' => 'Cet utilisateur est déjà enregistré comme coach.',
            'game_id.required' => 'L\'identifiant du jeu est obligatoire.',
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut doit être parmi : Available, Not available, N/A.',
            'achievement.required' => 'L\'accomplissement est obligatoire.',
            'achievement.string' => 'L\'accomplissement doit être une chaîne de caractères.',
        ]
    );

        $coach = Coaches::create($validated);

        if (!$coach) {
            \Log::error('Failed to create coach', ['data' => $validated]);
        }

        return response()->json(['message' => 'Coach succesfully added'], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request) : JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $coach = Coaches::where('user_id', $validated['user_id'])->firstOrFail();

        return response->json([
            'data' => [
                'status' => $coach->status,
                'achievement' => $coach->achievement,
                'user' => $coach->user,
                'game' => $coach->game,
            ]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user_id)
    {
        $validated = $request->validate([
            'status' => ['required','in:Available,Not available,N/A'],
            'achievement' => ['required','string'],
        ]);
        $coach = Coaches::where('user_id', $user_id)->firstOrFail();
        $coach->update($validated);
        return response()->json(['message' => 'Coach updated succesfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user_id)
    {
        $coach = Coaches::where('user_id', $user_id)->firstOrFail();
        $coach->delete();
        return response()->json(['message' => 'Coach succesfully deleted'], 200);
    }
}
