<?php

namespace App\Http\Controllers\Client;

use App\Models\Client\TagAluno;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Throwable;

class TagAlunoController extends AController
{
    public function index(Request $request)
    {
        try {
            $query = TagAluno::query();

            if ($request->filled('aluno_id')) {
                $query->where('aluno_id', $request->get('aluno_id'));
            }

            if ($request->filled('tag_id')) {
                $query->where('tag_id', $request->get('tag_id'));
            }

            $data = $request->filled('per_page')
                ? $query->with(['aluno', 'tag'])->paginate((int) $request->get('per_page'))
                : $query->with(['aluno', 'tag'])->get();

            return $this->success($data, 'TagAluno list retrieved successfully.');
        } catch (Throwable $e) {
            return $this->error('Failed to retrieve TagAluno list.', 500, $e->getMessage());
        }
    }

    public function show(string $id)
    {
        try {
            $item = TagAluno::with(['aluno', 'tag'])->findOrFail($id);

            return $this->success($item, 'TagAluno retrieved successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('TagAluno not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to retrieve TagAluno.', 500, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'aluno_id' => ['required', 'string', 'exists:aluno,id'],
                'tag_id' => ['required', 'string', 'exists:tag,id'],
            ]);

            $item = TagAluno::create($data);

            return $this->success($item->load(['aluno', 'tag']), 'TagAluno created successfully.', 201);
        } catch (Throwable $e) {
            return $this->error('Failed to create TagAluno.', 500, $e->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $item = TagAluno::findOrFail($id);

            $data = $request->validate([
                'aluno_id' => ['sometimes', 'string', 'exists:aluno,id'],
                'tag_id' => ['sometimes', 'string', 'exists:tag,id'],
            ]);

            $item->update($data);

            return $this->success($item->fresh(['aluno', 'tag']), 'TagAluno updated successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('TagAluno not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to update TagAluno.', 500, $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $item = TagAluno::findOrFail($id);
            $item->delete();

            return $this->success(null, 'TagAluno deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('TagAluno not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to delete TagAluno.', 500, $e->getMessage());
        }
    }
}
