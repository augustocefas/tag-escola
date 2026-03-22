<?php

namespace App\Http\Controllers\Client;

use App\Models\Client\Aluno;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Throwable;

class AlunoController extends AController
{
    public function index(Request $request)
    {
        try {
            $query = Aluno::query();

            if ($request->filled('nome')) {
                $query->where('nome', 'like', '%' . $request->get('nome') . '%');
            }

            if ($request->filled('matricula')) {
                $query->where('matricula', $request->get('matricula'));
            }

            $data = $request->filled('per_page')
                ? $query->paginate((int) $request->get('per_page'))
                : $query->get();

            return $this->success($data, 'Aluno list retrieved successfully.');
        } catch (Throwable $e) {
            return $this->error('Failed to retrieve Aluno list.', 500, $e->getMessage());
        }
    }

    public function show(string $id)
    {
        try {
            $item = Aluno::with(['anexo', 'tags', 'salas'])->findOrFail($id);

            return $this->success($item, 'Aluno retrieved successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Aluno not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to retrieve Aluno.', 500, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'nome' => ['required', 'string', 'max:255'],
                'nascimento' => ['required', 'date'],
                'matricula' => ['required', 'string', 'max:255', 'unique:aluno,matricula'],
                'anexo_id' => ['nullable', 'string', 'exists:anexo,id'],
                'dados_adicionais' => ['nullable', 'array'],
            ]);

            $item = Aluno::create($data);

            if ($request->filled('tags')) {
                $item->tags()->sync($request->get('tags'));
            }

            if ($request->filled('salas')) {
                $item->salas()->sync($request->get('salas'));
            }

            return $this->success($item->load(['anexo', 'tags', 'salas']), 'Aluno created successfully.', 201);
        } catch (Throwable $e) {
            return $this->error('Failed to create Aluno.', 500, $e->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $item = Aluno::findOrFail($id);

            $data = $request->validate([
                'nome' => ['sometimes', 'string', 'max:255'],
                'nascimento' => ['sometimes', 'date'],
                'matricula' => ['sometimes', 'string', 'max:255', 'unique:aluno,matricula,' . $id],
                'anexo_id' => ['nullable', 'string', 'exists:anexo,id'],
                'dados_adicionais' => ['nullable', 'array'],
            ]);

            $item->update($data);

            if ($request->filled('tags')) {
                $item->tags()->sync($request->get('tags'));
            }

            if ($request->filled('salas')) {
                $item->salas()->sync($request->get('salas'));
            }

            return $this->success($item->fresh(['anexo', 'tags', 'salas']), 'Aluno updated successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Aluno not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to update Aluno.', 500, $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $item = Aluno::findOrFail($id);
            $item->delete();

            return $this->success(null, 'Aluno deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Aluno not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to delete Aluno.', 500, $e->getMessage());
        }
    }
}
