<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $departmentId = $user->department_id;

        $totalTasks = Task::where('department_id', $departmentId)->count();
        $pendingTasks = Task::where('department_id', $departmentId)->where('status', 'pending')->count();
        $inProgressTasks = Task::where('department_id', $departmentId)->where('status', 'in_progress')->count();
        $completedTasks = Task::where('department_id', $departmentId)->where('status', 'completed')->count();

        $recentTasks = Task::where('department_id', $departmentId)
                           ->with('assignedUser', 'creator')
                           ->latest()
                           ->take(5)
                           ->get();

        return view('manager.dashboard', compact(
            'totalTasks',
            'pendingTasks',
            'inProgressTasks',
            'completedTasks',
            'recentTasks'
        ));
    }
}
