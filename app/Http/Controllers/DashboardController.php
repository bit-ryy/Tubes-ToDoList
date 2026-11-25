<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $baseQuery = $user->tasks();

        $totalTasks = (clone $baseQuery)->count();
        $completedTasks = (clone $baseQuery)->where('status', 'completed')->count();
        $pendingTasks = (clone $baseQuery)->where('status', '!=', 'completed')->count();
        $overdueTasks = (clone $baseQuery)
            ->where('status', '!=', 'completed')
            ->where('deadline', '<', Carbon::now())
            ->count();

        $completionRate = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100, 1)
            : 0;

        $byPriority = [
            'high' => (clone $baseQuery)->where('priority', 'high')->count(),
            'medium' => (clone $baseQuery)->where('priority', 'medium')->count(),
            'low' => (clone $baseQuery)->where('priority', 'low')->count(),
        ];

        $tasksPerProject = $user->projects()
            ->withCount('tasks')
            ->get(['id', 'name', 'color']);

        return response()->json([
            'summary' => [
                'total_tasks' => $totalTasks,
                'completed_tasks' => $completedTasks,
                'pending_tasks' => $pendingTasks,
                'overdue_tasks' => $overdueTasks,
                'completion_rate' => $completionRate,
                'total_projects' => $user->projects()->count(),
                'total_labels' => $user->labels()->count(),
            ],
            'by_priority' => $byPriority,
            'tasks_per_project' => $tasksPerProject,
        ], 200);
    }
}
