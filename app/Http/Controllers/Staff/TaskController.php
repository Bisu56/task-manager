<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('assigned_to', Auth::user()->id)
                     ->with('creator')
                     ->latest()
                     ->paginate(10);

        return view('staff.tasks.index', compact('tasks'));
    }

    public function show(Task $task)
    {
        // Staff can only see tasks assigned to them
        if ($task->assigned_to !== Auth::user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $task->load([
            'comments.user',
            'files.user',
            'activities.user',
        ]);

        return view('staff.tasks.show', compact('task'));
    }
}
