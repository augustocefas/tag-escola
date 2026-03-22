<?php

namespace App\Http\Controllers\Client;


use App\Models\Client\Dominio;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Throwable;

class DominioController extends AController
{
    public function index(Request $request)
    {
        try {
            $query = Dominio::query();

            if ($request->filled('dominio')) {
                $query->where('dominio', 'like', '%' . $request->get('dominio') . '%');
            }

            if ($request->filled('tp_dominio_id')) {
                $query->where('tp_dominio_id', $request->get('tp_dominio_id'));
            }

            if ($request->filled('ativo')) {
                $query->where('ativo', $request->boolean('ativo'));
            }

            if ($request->filled('publico')) {
                $query->where('publico', $request->boolean('publico'));
            }

            $data = $request->filled('per_page')
                ? $query->with(['tipoDominio', 'anexo'])->paginate((int) $request->get('per_page'))
                : $query->with(['tipoDominio', 'anexo'])->get();

            return $this->success($data, 'Dominio list retrieved successfully.');
        } catch (Throwable $e) {
            return $this->error('Failed to retrieve Dominio list.', 500, $e->getMessage());
        }
    }

    public function show(string $id)
    {
        try {
            $item = Dominio::with(['tipoDominio', 'anexo'])->findOrFail($id);

            return $this->success($item, 'Dominio retrieved successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Dominio not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to retrieve Dominio.', 500, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'tp_dominio_id' => ['required', 'string', 'exists:tp_dominio,id'],
                'anexo_id' => ['nullable', 'string', 'exists:anexo,id'],
                'dominio' => ['required', 'string', 'max:255'],
                'navegacao' => ['nullable', 'string', 'max:255'],
                'subnavegacao' => ['nullable', 'string', 'max:255'],
                'rota' => ['nullable', 'string', 'max:255'],
                'publico' => ['nullable', 'boolean'],
                'datasource' => ['nullable', 'string'],
                'icone' => ['nullable', 'string', 'max:255'],
                'fonte_cor' => ['nullable', 'string', 'max:50'],
                'fundo_cor' => ['nullable', 'string', 'max:50'],
                'ativo' => ['nullable', 'boolean'],
            ]);

            $item = Dominio::create($data);

            return $this->success($item->load(['tipoDominio', 'anexo']), 'Dominio created successfully.', 201);
        } catch (Throwable $e) {
            return $this->error('Failed to create Dominio.', 500, $e->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $item = Dominio::findOrFail($id);

            $data = $request->validate([
                'tp_dominio_id' => ['sometimes', 'string', 'exists:tp_dominio,id'],
                'anexo_id' => ['nullable', 'string', 'exists:anexo,id'],
                'dominio' => ['sometimes', 'string', 'max:255'],
                'navegacao' => ['nullable', 'string', 'max:255'],
                'subnavegacao' => ['nullable', 'string', 'max:255'],
                'rota' => ['nullable', 'string', 'max:255'],
                'publico' => ['nullable', 'boolean'],
                'datasource' => ['nullable', 'string'],
                'icone' => ['nullable', 'string', 'max:255'],
                'fonte_cor' => ['nullable', 'string', 'max:50'],
                'fundo_cor' => ['nullable', 'string', 'max:50'],
                'ativo' => ['nullable', 'boolean'],
            ]);

            $item->update($data);

            return $this->success($item->fresh(['tipoDominio', 'anexo']), 'Dominio updated successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Dominio not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to update Dominio.', 500, $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $item = Dominio::findOrFail($id);
            $item->delete();

            return $this->success(null, 'Dominio deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Dominio not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to delete Dominio.', 500, $e->getMessage());
        }
    }
}
