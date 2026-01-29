<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TaskFileController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'file' => 'required|file|max:5120'
        ]);

        $file = $request->file('file');
        $path = $file->store('task_files');

        $task->files()->create([
            'user_id' => Auth::id(),
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path
        ]);

        return back()->with('success', 'File uploaded');
    }

    public function destroy(TaskFile $file)
    {
        if (
            Auth::user()->role !== 'admin' &&
            $file->user_id !== Auth::id()
        ) {
            abort(403);
        }

        Storage::delete($file->file_path);
        $file->delete();

        return back()->with('success', 'File deleted');
    }
}
