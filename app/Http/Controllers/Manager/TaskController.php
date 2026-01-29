<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
 
    public function index()
    {
        $tasks = Task::where('department_id', Auth::user()->department_id)->with('assignedUser')->get();
        return view('manager.tasks.index', compact('tasks'));
    }

    public function create()
    {
        $staffs = User::where('role', 'staff')
                        ->where('department_id', Auth::user()->department_id)
                        ->get();
        return view('manager.tasks.create', compact('staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $department_id = Auth::user()->department_id;

        if (is_null($department_id)) {
            return redirect()->back()->withInput()->withErrors(['department_id' => 'You are not assigned to any department.']);
        }

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'department_id' => $department_id,
            'assigned_to' => $request->assigned_to,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('manager.tasks.index')
                         ->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        $staffs = User::where('role', 'staff')
                        ->where('department_id', Auth::user()->department_id)
                        ->get();
        return view('manager.tasks.edit', compact('task', 'staffs'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update($request->all());

        return redirect()->route('manager.tasks.index')
                         ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('manager.tasks.index')
                         ->with('success', 'Task deleted successfully.');
    }

private function authorizeTask(Task $task)
{
    if ($task->department_id !== Auth::user()->department_id) {
        abort(403, 'Unauthorized action.');
    }
}

public function show(Task $task)
{
    $task->load(['comments.user', 'files.user']);
    return view('manager.tasks.show', compact('task'));
}

}
