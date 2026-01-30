<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TaskNotification;

class CommentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        // Staff can only comment on their assigned task
        if (
            Auth::user()->role === 'staff' &&
            $task->assigned_to !== Auth::id()
        ) {
            abort(403, 'Unauthorized action.');
        }

        $comment = $task->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        logActivity(
            'comment_added',
            'added a comment',
            $task->id
        );

        // 🔔 Notify assigned staff & task creator (except self)
        $users = collect([
            $task->assignedUser ?? null,
            $task->creator ?? null,
        ])->filter()->unique('id');

        foreach ($users as $user) {
            if ($user->id !== Auth::id()) {
                $user->notify(new TaskNotification(
                    Auth::user()->name . ' commented on task: ' . $task->title,
                    route('manager.tasks.show', $task->id)
                ));
            }
        }

        return response()->json([
            'user'    => Auth::user()->name,
            'comment' => $comment->comment,
            'time'    => $comment->created_at->diffForHumans(),
        ]);
    }

    public function update(Request $request, Comment $comment)
    {
        // Only admin or comment owner can update
        if (
            Auth::user()->role !== 'admin' &&
            $comment->user_id !== Auth::id()
        ) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'comment' => 'required|string',
        ]);

        $comment->update([
            'comment' => $request->comment,
        ]);

        logActivity(
            'comment_updated',
            'updated a comment',
            $comment->task_id
        );

        return response()->json(['success' => true]);
    }

    public function destroy(Comment $comment)
    {
        // Only admin or comment owner can delete
        if (
            Auth::user()->role !== 'admin' &&
            $comment->user_id !== Auth::id()
        ) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        logActivity(
            'comment_deleted',
            'deleted a comment',
            $comment->task_id
        );

        return response()->json(['success' => true]);
    }
}
