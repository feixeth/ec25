<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Conversations;

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
        // Vérifie que l'utilisateur est un participant de la conversation
        abort_if(!$conversation->participants->contains(auth()->id()), 403);

        // Charge les messages et les participants
        $conversation->load(['messages', 'participants']);

        return response()->json($conversation);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string',
            'content' => 'required|string',
            'recipient_id' => 'required|exists:users,id'  // On valide le recipient_id
        ]);

        // Création de la conversation
        $conversation = Conversations::create([
            'subject' => $validated['subject']
        ]);

        // Ajout des deux participants (sender et recipient)
        $conversation->participants()->attach([
            auth()->id(),
            $validated['recipient_id']
        ]);

        // Création du message avec sender_id ET recipient_id
        $conversation->messages()->create([
            'content' => $validated['content'],
            'sender_id' => auth()->id(),
            'recipient_id' => $validated['recipient_id']
        ]);

        $conversation->load(['messages.sender', 'participants']);
        return response()->json($conversation, 201);
    }

}
