<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLabelRequest;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function index(Request $request)
    {
        $labels = $request->user()
            ->labels()
            ->withCount('tasks')
            ->latest()
            ->get();

        return response()->json(['data' => $labels], 200);
    }

    public function store(StoreLabelRequest $request)
    {
        $data = $request->validated();
        if (empty($data['color'])) {
            $data['color'] = '#808080';
        }

        $label = $request->user()->labels()->create($data);

        return response()->json([
            'message' => 'Label berhasil dibuat',
            'data' => $label
        ], 201);
    }

    public function show(Request $request, string $id)
    {
        $label = $request->user()
            ->labels()
            ->with('tasks')
            ->find($id);

        if (!$label) {
            return response()->json(['message' => 'Label tidak ditemukan'], 404);
        }

        return response()->json(['data' => $label], 200);
    }

    public function update(StoreLabelRequest $request, string $id)
    {
        $label = $request->user()->labels()->find($id);

        if (!$label) {
            return response()->json(['message' => 'Label tidak ditemukan'], 404);
        }

        $data = $request->validated();
        if (array_key_exists('color', $data) && empty($data['color'])) {
            unset($data['color']);
        }

        $label->update($data);

        return response()->json([
            'message' => 'Label berhasil diperbarui',
            'data' => $label
        ], 200);
    }

    public function destroy(Request $request, string $id)
    {
        $label = $request->user()->labels()->find($id);

        if (!$label) {
            return response()->json(['message' => 'Label tidak ditemukan'], 404);
        }

        $label->delete();

        return response()->json(['message' => 'Label berhasil dihapus'], 200);
    }
}
