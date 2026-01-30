<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TaskNotification;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('department_id', Auth::user()->department_id)
                     ->with('assignedUser')
                     ->paginate(10);

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
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'priority'    => 'required|in:low,medium,high',
            'due_date'    => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $department_id = Auth::user()->department_id;

        if (!$department_id) {
            return back()
                ->withInput()
                ->withErrors(['department_id' => 'You are not assigned to any department.']);
        }

        $task = Task::create([
            'title'         => $request->title,
            'description'   => $request->description,
            'priority'      => $request->priority,
            'due_date'      => $request->due_date,
            'department_id' => $department_id,
            'assigned_to'   => $request->assigned_to,
            'created_by'    => Auth::id(),
        ]);

        logActivity(
            'task_created',
            'created a task: ' . $task->title,
            $task->id
        );

        // 🔔 Notify assigned staff
        if ($task->assigned_to) {
            $staff = User::find($task->assigned_to);

            if ($staff) {
                $staff->notify(new TaskNotification(
                    'You have been assigned a new task: ' . $task->title,
                    route('staff.tasks.show', $task->id)
                ));
            }
        }

        return redirect()->route('manager.tasks.index')
                         ->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        $this->authorizeTask($task);

        $staffs = User::where('role', 'staff')
                      ->where('department_id', Auth::user()->department_id)
                      ->get();

        return view('manager.tasks.edit', compact('task', 'staffs'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorizeTask($task);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'priority'    => 'required|in:low,medium,high',
            'due_date'    => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
            'status'      => 'required|in:pending,in_progress,completed',
        ]);

        $task->update($request->only([
            'title',
            'description',
            'priority',
            'due_date',
            'assigned_to',
            'status',
        ]));

        logActivity(
            'task_updated',
            'updated task details',
            $task->id
        );

        // 🔔 Notify staff if reassigned
        if ($request->assigned_to && $request->assigned_to != $task->getOriginal('assigned_to')) {
            $staff = User::find($request->assigned_to);

            if ($staff) {
                $staff->notify(new TaskNotification(
                    'You have been assigned a task: ' . $task->title,
                    route('staff.tasks.show', $task->id)
                ));
            }
        }

        return redirect()->route('manager.tasks.index')
                         ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorizeTask($task);

        logActivity(
            'task_deleted',
            'deleted a task',
            $task->id
        );

        $task->delete();

        return redirect()->route('manager.tasks.index')
                         ->with('success', 'Task deleted successfully.');
    }

    public function show(Task $task)
    {
        $this->authorizeTask($task);

        $task->load([
            'comments.user',
            'files.user',
            'activities.user',
        ]);

        return view('manager.tasks.show', compact('task'));
    }

    private function authorizeTask(Task $task)
    {
        if ($task->department_id !== Auth::user()->department_id) {
            abort(403, 'Unauthorized action.');
        }
    }
}
