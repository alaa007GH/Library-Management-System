<?php

namespace App\Helpers;

use App\Models\Notification;

function sendNotification($userId, $message)
{
    Notification::create([
        'user_id' => $userId,
        'message' => $message,
        'is_read' => false, // افتراضيًا غير مقروء
    ]);
}
