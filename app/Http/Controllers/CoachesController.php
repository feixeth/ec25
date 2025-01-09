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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(Coaches $coaches)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coaches $coaches)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coaches $coaches)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $user_id)
    {
        $coach = Coaches::findOrFail($user_id);
        $coach->user()->detach($user_id);
        return response()->json(['message' => 'Coach removed successfully'], 200);    
    }
}
