<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function getNotifications(Request $request)
    {
        $user = auth('sanctum')->user(); 
        
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['notifications' => $notifications]);
    }

    public function deleteNotification($id)
    {
        $user = auth('sanctum')->user(); 
        
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $notification->delete();

        return response()->json(['message' => 'Notification deleted successfully']);
    }
}
