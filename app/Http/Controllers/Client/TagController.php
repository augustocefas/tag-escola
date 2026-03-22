<?php

namespace App\Http\Controllers\Client;

use App\Models\Client\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Throwable;

class TagController extends AController
{
    public function index(Request $request)
    {
        try {
            $query = Tag::query();

            if ($request->filled('apelido')) {
                $query->where('apelido', 'like', '%' . $request->get('apelido') . '%');
            }

            if ($request->filled('mac_address')) {
                $query->where('mac_address', $request->get('mac_address'));
            }

            if ($request->filled('responsavel')) {
                $query->where('responsavel', 'like', '%' . $request->get('responsavel') . '%');
            }

            $data = $request->filled('per_page')
                ? $query->paginate((int) $request->get('per_page'))
                : $query->get();

            return $this->success($data, 'Tag list retrieved successfully.');
        } catch (Throwable $e) {
            return $this->error('Failed to retrieve Tag list.', 500, $e->getMessage());
        }
    }

    public function show(string $id)
    {
        try {
            $item = Tag::with(['alunos'])->findOrFail($id);

            return $this->success($item, 'Tag retrieved successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Tag not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to retrieve Tag.', 500, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'apelido' => ['required', 'string', 'max:255'],
                'mac_address' => ['required', 'string', 'max:255'],
                'key' => ['nullable', 'string', 'max:255'],
                'passkey' => ['nullable', 'string', 'max:255'],
                'responsavel' => ['nullable', 'string', 'max:255'],
                'dados_adicionais' => ['nullable', 'array'],
            ]);

            $item = Tag::create($data);

            if ($request->filled('alunos')) {
                $item->alunos()->sync($request->get('alunos'));
            }

            return $this->success($item->load(['alunos']), 'Tag created successfully.', 201);
        } catch (Throwable $e) {
            return $this->error('Failed to create Tag.', 500, $e->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $item = Tag::findOrFail($id);

            $data = $request->validate([
                'apelido' => ['sometimes', 'string', 'max:255'],
                'mac_address' => ['sometimes', 'string', 'max:255'],
                'key' => ['nullable', 'string', 'max:255'],
                'passkey' => ['nullable', 'string', 'max:255'],
                'responsavel' => ['nullable', 'string', 'max:255'],
                'dados_adicionais' => ['nullable', 'array'],
            ]);

            $item->update($data);

            if ($request->filled('alunos')) {
                $item->alunos()->sync($request->get('alunos'));
            }

            return $this->success($item->fresh(['alunos']), 'Tag updated successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Tag not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to update Tag.', 500, $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $item = Tag::findOrFail($id);
            $item->delete();

            return $this->success(null, 'Tag deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Tag not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to delete Tag.', 500, $e->getMessage());
        }
    }
}
