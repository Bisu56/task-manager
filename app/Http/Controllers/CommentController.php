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

    public function update(Request $request, Comment $comment)
{
    if (
        Auth::user()->role !== 'admin' &&
        $comment->user_id !== Auth::id()
    ) {
        abort(403);
    }

    $request->validate([
        'comment' => 'required|string'
    ]);

    $comment->update([
        'comment' => $request->comment
    ]);

    return response()->json(['success' => true]);
}

public function destroy(Comment $comment)
{
    if (
        Auth::user()->role !== 'admin' &&
        $comment->user_id !== Auth::id()
    ) {
        abort(403);
    }

    $comment->delete();

    return response()->json(['success' => true]);
}


}
