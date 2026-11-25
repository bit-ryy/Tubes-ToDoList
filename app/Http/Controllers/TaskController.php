<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Services\Priority\PriorityFactory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()
            ->tasks()
            ->with(['project', 'labels', 'subtasks']);

        // Secara default tampilkan tugas utama saja agar subtask tampil di dalam relasi subtasks,
        // kecuali saat pengguna sedang melakukan pencarian atau meminta semua tugas.
        if (!$request->filled('search') && !$request->boolean('include_subtasks')) {
            $query->whereNull('parent_id');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('label_id')) {
            $query->whereHas('labels', function ($q) use ($request) {
                $q->where('labels.id', $request->label_id);
            });
        }

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        $tasks = $query->orderBy('deadline', 'asc')->get();

        $tasks->each(function ($task) {
            $this->attachPriorityMeta($task);
        });

        return response()->json(['data' => $tasks], 200);
    }

    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();
        $labelIds = $data['label_ids'] ?? [];
        unset($data['label_ids']);

        if (array_key_exists('status', $data) && is_null($data['status'])) {
            unset($data['status']);
        }

        $task = $request->user()->tasks()->create($data);

        if (!empty($labelIds)) {
            $task->labels()->sync($labelIds);
        }

        $task->load(['project', 'labels', 'subtasks']);
        $this->attachPriorityMeta($task);

        return response()->json([
            'message' => 'Tugas berhasil dibuat',
            'data' => $task
        ], 201);
    }

    public function show(Request $request, string $id)
    {
        $task = $request->user()
            ->tasks()
            ->with(['project', 'labels', 'parent', 'subtasks'])
            ->find($id);

        if (!$task) {
            return response()->json(['message' => 'Tugas tidak ditemukan'], 404);
        }

        $this->attachPriorityMeta($task);

        return response()->json(['data' => $task], 200);
    }

    public function update(StoreTaskRequest $request, string $id)
    {
        $task = $request->user()->tasks()->find($id);

        if (!$task) {
            return response()->json(['message' => 'Tugas tidak ditemukan'], 404);
        }

        $data = $request->validated();
        $hasLabels = array_key_exists('label_ids', $data);
        $labelIds = $data['label_ids'] ?? [];
        unset($data['label_ids']);

        if (array_key_exists('status', $data) && is_null($data['status'])) {
            unset($data['status']);
        }

        $task->update($data);

        if ($hasLabels) {
            $task->labels()->sync($labelIds);
        }

        $task->load(['project', 'labels', 'subtasks']);
        $this->attachPriorityMeta($task);

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

    /**
     * Menambahkan informasi badge_color dan reminder_message menggunakan PriorityFactory.
     */
    private function attachPriorityMeta(Task $task): void
    {
        $priorityLogic = PriorityFactory::make($task->priority);
        $daysLeft = (int) Carbon::now()->diffInDays(Carbon::parse($task->deadline), false);

        $task->badge_color = $priorityLogic->getBadgeColor();
        $task->reminder_message = $priorityLogic->getReminderMessage($daysLeft);

        if ($task->relationLoaded('subtasks')) {
            $task->subtasks->each(function ($subtask) {
                $this->attachPriorityMeta($subtask);
            });
        }
    }
}
