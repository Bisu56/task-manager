<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\Task;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $userCount = User::count();
        $taskCount = Task::count();
        $departmentCount = Department::count();
        $activities = ActivityLog::with('causer')->latest()->take(5)->get();

        return view('admin.dashboard', compact('userCount', 'taskCount', 'departmentCount', 'activities'));
    }
}
