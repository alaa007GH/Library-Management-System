<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public function send($userId, $message)
    {
        Notification::create([
            'user_id' => $userId,
            'message' => $message,
            'is_read' => false, // افتراضيًا غير مقروء
        ]);
    }
}
