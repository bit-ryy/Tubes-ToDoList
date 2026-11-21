<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Services\Priority\PriorityFactory;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->tasks();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $tasks = $query->orderBy('deadline', 'asc')->get();

        $tasks->map(function ($task) {
            $priorityLogic = PriorityFactory::make($task->priority);
            $daysLeft = (int) Carbon::now()->diffInDays(Carbon::parse($task->deadline), false);

            $task->badge_color = $priorityLogic->getBadgeColor();
            $task->reminder_message = $priorityLogic->getReminderMessage($daysLeft);

            return $task;
        });

        return response()->json(['data' => $tasks], 200);
    }

    public function store(StoreTaskRequest $request)
    {
        $task = $request->user()->tasks()->create($request->validated());

        return response()->json([
            'message' => 'Tugas berhasil dibuat',
            'data' => $task
        ], 201);
    }

    public function show(Request $request, string $id)
    {
        $task = $request->user()->tasks()->find($id);

        if (!$task) {
            return response()->json(['message' => 'Tugas tidak ditemukan'], 404);
        }

        $priorityLogic = PriorityFactory::make($task->priority);
        $daysLeft = (int) Carbon::now()->diffInDays(Carbon::parse($task->deadline), false);

        $task->badge_color = $priorityLogic->getBadgeColor();
        $task->reminder_message = $priorityLogic->getReminderMessage($daysLeft);

        return response()->json(['data' => $task], 200);
    }

    public function update(StoreTaskRequest $request, string $id)
    {
        $task = $request->user()->tasks()->find($id);

        if (!$task) {
            return response()->json(['message' => 'Tugas tidak ditemukan'], 404);
        }

        $task->update($request->validated());

        return response()->json([
            'message' => 'Tugas berhasil diperbarui',
            'data' => $task
        ], 200);
    }

    public function destroy(Request $request, string $id)
    {
        $task = $request->user()->tasks()->find($id);

        if (!$task) {
            return response()->json(['message' => 'Tugas tidak ditemukan'], 404);
        }

        $task->delete();

        return response()->json(['message' => 'Tugas berhasil dihapus'], 200);
    }
}
