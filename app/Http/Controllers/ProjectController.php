<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = $request->user()
            ->projects()
            ->withCount('tasks')
            ->latest()
            ->get();

        return response()->json(['data' => $projects], 200);
    }

    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();
        if (empty($data['color'])) {
            $data['color'] = '#808080';
        }

        $project = $request->user()->projects()->create($data);

        return response()->json([
            'message' => 'Proyek berhasil dibuat',
            'data' => $project
        ], 201);
    }

    public function show(Request $request, string $id)
    {
        $project = $request->user()
            ->projects()
            ->with('tasks')
            ->find($id);

        if (!$project) {
            return response()->json(['message' => 'Proyek tidak ditemukan'], 404);
        }

        return response()->json(['data' => $project], 200);
    }

    public function update(StoreProjectRequest $request, string $id)
    {
        $project = $request->user()->projects()->find($id);

        if (!$project) {
            return response()->json(['message' => 'Proyek tidak ditemukan'], 404);
        }

        $data = $request->validated();
        if (array_key_exists('color', $data) && empty($data['color'])) {
            unset($data['color']);
        }

        $project->update($data);

        return response()->json([
            'message' => 'Proyek berhasil diperbarui',
            'data' => $project
        ], 200);
    }

    public function destroy(Request $request, string $id)
    {
        $project = $request->user()->projects()->find($id);

        if (!$project) {
            return response()->json(['message' => 'Proyek tidak ditemukan'], 404);
        }

        $project->delete();

        return response()->json(['message' => 'Proyek berhasil dihapus'], 200);
    }
}
