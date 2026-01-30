<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalTasks = Task::where('assigned_to', $user->id)->count();
        $pendingTasks = Task::where('assigned_to', $user->id)->where('status', 'pending')->count();
        $inProgressTasks = Task::where('assigned_to', $user->id)->where('status', 'in_progress')->count();
        $completedTasks = Task::where('assigned_to', $user->id)->where('status', 'completed')->count();

        $recentTasks = Task::where('assigned_to', $user->id)
                           ->with('creator')
                           ->latest()
                           ->take(5)
                           ->get();

        return view('staff.dashboard', compact(
            'totalTasks',
            'pendingTasks',
            'inProgressTasks',
            'completedTasks',
            'recentTasks'
        ));
    }
}
