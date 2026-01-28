<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        if (
            Auth::user()->role === 'staff' &&
            $task->assigned_to !== Auth::id()
        ) {
            abort(403, 'Unauthorized action.');
        }
        $task->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return response()->json([
            'user_id' => Auth::user()->name,
            'comment' => $request->comment,
            'time' => now()->diffForHumans(),
        ]);
    }
}
