<?php

namespace App\Http\Controllers;

use App\Models\Teams;
use App\Models\TeamsMembers;
use Illuminate\Http\Request;

class TeamsMembersController extends Controller
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
    public function store(Request $request, $teamId)
    {

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $team = Teams::findOrFail($teamId);

        $team->members()->attach($validated['user_id']);

        return response()->json(['message' => 'Member added successfully'], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(TeamsMembers $teamsMembers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeamsMembers $teamsMembers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TeamsMembers $teamsMembers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $teamId)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        $team = Teams::findOrFail($teamId);
        $member = $team->members()->where('user_id', $validated['user_id'])->first();
        if (!$member) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
        $team->members()->detach($validated['user_id']);
        return response()->json(['message' => 'Member removed successfully'], 200);
    }
}
