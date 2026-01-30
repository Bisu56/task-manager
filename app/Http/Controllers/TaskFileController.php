<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\TaskNotification;

class TaskFileController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'file' => 'required|file|max:5120', // 5MB
        ]);

        // Staff can upload files only to their assigned task
        if (
            Auth::user()->role === 'staff' &&
            $task->assigned_to !== Auth::id()
        ) {
            abort(403, 'Unauthorized action.');
        }

        $file = $request->file('file');
        $path = $file->store('task_files');

        $taskFile = $task->files()->create([
            'user_id'   => Auth::id(),
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
        ]);

        logActivity(
            'file_uploaded',
            'uploaded file: ' . $taskFile->file_name,
            $task->id
        );

        // 🔔 Notify task creator & assigned user (except uploader)
        $users = collect([
            $task->creator ?? null,
            $task->assignedUser ?? null,
        ])->filter()->unique('id');

        foreach ($users as $user) {
            if ($user->id !== Auth::id()) {
                $user->notify(new TaskNotification(
                    Auth::user()->name . ' uploaded a file to task: ' . $task->title,
                    route('manager.tasks.show', $task->id)
                ));
            }
        }

        return back()->with('success', 'File uploaded successfully.');
    }

    public function destroy(TaskFile $file)
    {
        // Only admin or file owner can delete
        if (
            Auth::user()->role !== 'admin' &&
            $file->user_id !== Auth::id()
        ) {
            abort(403, 'Unauthorized action.');
        }

        if (Storage::exists($file->file_path)) {
            Storage::delete($file->file_path);
        }

        logActivity(
            'file_deleted',
            'deleted file: ' . $file->file_name,
            $file->task_id
        );

        $file->delete();

        return back()->with('success', 'File deleted successfully.');
    }
}
