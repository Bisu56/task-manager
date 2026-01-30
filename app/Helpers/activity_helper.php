<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

if (!function_exists('logActivity')) {
    function logActivity($action, $description, $taskId = null)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'task_id' => $taskId,
            'action' => $action,
            'description' => $description
        ]);
    }
}
