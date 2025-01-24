<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
     public function index()
    {
        // Récup les conv du user
        $user_id = auth()->id();
        
        return Messages::where('sender_id', $user_id)
            ->orWhere('recipient_id', $user_id)
            ->with(['sender', 'recipient'])
            ->latest()
            ->get()
            ->groupBy(function($message) use ($user_id) {
                // Grouper par interlocuteur
                return $message->sender_id == $user_id 
                    ? $message->recipient_id 
                    : $message->sender_id;
            });
    }

    public function getConversation(User $otherUser)
    {
        $user_id = auth()->id();
        
        return Messages::where(function($query) use ($user_id, $otherUser) {
            $query->where('sender_id', $user_id)
                  ->where('recipient_id', $otherUser->id);
        })->orWhere(function($query) use ($user_id, $otherUser) {
            $query->where('sender_id', $otherUser->id)
                  ->where('recipient_id', $user_id);
        })
        ->with(['sender', 'recipient'])
        ->latest()
        ->paginate(50);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'message' => 'required|string|max:10000'
        ]);

        $message = Messages::create([
            'sender_id' => auth()->id(),
            'recipient_id' => $validated['recipient_id'],
            'messages' => $validated['message']
        ]);

        // Optionnel : Notification en temps réel
        // broadcast(new NewMessage($message))->toOthers();

        return $message->load(['sender', 'recipient']);
    }

    public function markAsRead(User $otherUser)
    {
        Messages::where('sender_id', $otherUser->id)
              ->where('recipient_id', auth()->id())
              ->where('is_read', false)
              ->update(['is_read' => true]);

        return response()->json(['message' => 'Messages marked as read']);
    }

    public function getUnreadCount()
    {
        return response()->json([
            'count' => Messages::where('recipient_id', auth()->id())
                            ->where('is_read', false)
                            ->count()
        ]);
    }

}
