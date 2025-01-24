<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConversationsController extends Controller
{
    public function index()
    {
        return auth()->user()->conversations()
            ->with(['latestMessage', 'participants'])
            ->latest()
            ->get();
    }

    public function show(Conversations $conversation)
    {
        // check si user fait partie de la conv
        abort_if(!$conversation->participants->contains(auth()->id()), 403);

        return $conversation->load(['messages.sender', 'participants']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string',
            'message' => 'required|string',
            'participants' => 'required|array',
            'participants.*' => 'exists:users,id'
        ]);

        $conversation = DB::transaction(function () use ($validated) {
            $conversation = Conversations::create([
                'subject' => $validated['subject']
            ]);

            // ajout des acteurs
            $participants = collect($validated['participants'])
                ->push(auth()->id())
                ->unique();
            
            $conversation->participants()->attach($participants);

            // first msg
            $conversation->messages()->create([
                'content' => $validated['message'],
                'user_id' => auth()->id()
            ]);

            return $conversation;
        });

        return $conversation->load(['messages.sender', 'participants']);
    }
}
