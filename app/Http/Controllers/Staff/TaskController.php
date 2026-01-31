<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

use Illuminate\Validation\Rule; // Add this for validation

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

    public function updateStatus(Request $request, Task $task)
    {
        // Staff can only update status of tasks assigned to them
        if ($task->assigned_to !== Auth::user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(['pending', 'in progress', 'completed', 'on hold'])],
        ]);

        $oldStatus = $task->status;
        $task->status = $validated['status'];
        $task->save();

        // Log activity (optional, but good practice)
        logActivity(
            'status_updated',
            'updated task status from ' . $oldStatus . ' to ' . $task->status,
            $task->id
        );

        return back()->with('success', 'Task status updated successfully.');
    }
}
