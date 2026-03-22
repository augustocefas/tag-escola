<?php

namespace App\Http\Controllers\Client;

use App\Models\Client\AlunoSala;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Throwable;

class AlunoSalaController extends AController
{
    public function index(Request $request)
    {
        try {
            $query = AlunoSala::query();

            if ($request->filled('aluno_id')) {
                $query->where('aluno_id', $request->get('aluno_id'));
            }

            if ($request->filled('sala_id')) {
                $query->where('sala_id', $request->get('sala_id'));
            }

            $data = $request->filled('per_page')
                ? $query->with(['aluno', 'sala'])->paginate((int) $request->get('per_page'))
                : $query->with(['aluno', 'sala'])->get();

            return $this->success($data, 'AlunoSala list retrieved successfully.');
        } catch (Throwable $e) {
            return $this->error('Failed to retrieve AlunoSala list.', 500, $e->getMessage());
        }
    }

    public function show(string $id)
    {
        try {
            $item = AlunoSala::with(['aluno', 'sala'])->findOrFail($id);

            return $this->success($item, 'AlunoSala retrieved successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('AlunoSala not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to retrieve AlunoSala.', 500, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'aluno_id' => ['required', 'string', 'exists:aluno,id'],
                'sala_id' => ['required', 'string', 'exists:sala,id'],
            ]);

            $item = AlunoSala::create($data);

            return $this->success($item->load(['aluno', 'sala']), 'AlunoSala created successfully.', 201);
        } catch (Throwable $e) {
            return $this->error('Failed to create AlunoSala.', 500, $e->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $item = AlunoSala::findOrFail($id);

            $data = $request->validate([
                'aluno_id' => ['sometimes', 'string', 'exists:aluno,id'],
                'sala_id' => ['sometimes', 'string', 'exists:sala,id'],
            ]);

            $item->update($data);

            return $this->success($item->fresh(['aluno', 'sala']), 'AlunoSala updated successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('AlunoSala not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to update AlunoSala.', 500, $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $item = AlunoSala::findOrFail($id);
            $item->delete();

            return $this->success(null, 'AlunoSala deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('AlunoSala not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to delete AlunoSala.', 500, $e->getMessage());
        }
    }
}
