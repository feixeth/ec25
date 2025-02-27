<?php

namespace App\Http\Controllers;

use App\Models\Notifications;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource for the user asking .
     */
    public function index()
    {
        $user = auth()->user();
        if(!$user){
            return response()->json([
                'error' => 'invalid credentials'
            ], 401);
        } else {
            $notifications = Notifications::where('user_id', $user->id)->get();
            return response()->json([
                'data' => $notifications,
            ]);
        }
    }



    /**
     * Display unread notif.
     */
    public function getUnreadCount(Notifications $notifications)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'error' => 'Invalid credentials'
            ], 401);
        }

        // Compter uniquement les notifications non lues de l'utilisateur connecté
        $unreadCount = Notifications::where('user_id', $user->id)
                                    ->where('is_read', false)
                                    ->count();

        return response()->json([
            'count' => $unreadCount
        ], 200);
    }



    /**
     * MArk as read a notifications.
     */
    public function markAsRead(Notifications $notification)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($notification->user_id !== $user->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $notification->update(['is_read' => true]);

        return response()->json([
            'message' => 'Notification marked as read',
            'data' => $notification
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteNotifications(Notifications $notifications)
    {
        //
    }
}
