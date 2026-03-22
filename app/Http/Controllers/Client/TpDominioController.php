<?php

namespace App\Http\Controllers\Client;

use App\Models\Client\TpDominio;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Throwable;

class TpDominioController extends AController
{
    public function index(Request $request)
    {
        try {
            $query = TpDominio::query();

            if ($request->filled('tp_dominio')) {
                $query->where('tp_dominio', 'like', '%' . $request->get('tp_dominio') . '%');
            }

            if ($request->filled('navegacao')) {
                $query->where('navegacao', 'like', '%' . $request->get('navegacao') . '%');
            }

            if ($request->filled('subnavegacao')) {
                $query->where('subnavegacao', 'like', '%' . $request->get('subnavegacao') . '%');
            }

            if ($request->filled('ativo')) {
                $query->where('ativo', $request->boolean('ativo'));
            }

            if ($request->filled('publico')) {
                $query->where('publico', $request->boolean('publico'));
            }

            $data = $request->filled('per_page')
                ? $query->paginate((int) $request->get('per_page'))
                : $query->get();

            return $this->success($data, 'TpDominio list retrieved successfully.');
        } catch (Throwable $e) {
            return $this->error('Failed to retrieve TpDominio list.', 500, $e->getMessage());
        }
    }

    public function show(string $id)
    {
        try {
            $item = TpDominio::findOrFail($id);

            return $this->success($item, 'TpDominio retrieved successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('TpDominio not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to retrieve TpDominio.', 500, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'tp_dominio' => ['required', 'string', 'max:255'],
                'navegacao' => ['nullable', 'string', 'max:255'],
                'subnavegacao' => ['nullable', 'string', 'max:255'],
                'rota' => ['nullable', 'string', 'max:255'],
                'publico' => ['nullable', 'boolean'],
                'datasource' => ['nullable', 'string'],
                'icone' => ['nullable', 'string', 'max:255'],
                'fonte_cor' => ['nullable', 'string', 'max:50'],
                'fundo_cor' => ['nullable', 'string', 'max:50'],
                'ativo' => ['nullable', 'boolean'],
                'subtitulo' => ['nullable', 'string', 'max:255'],
            ]);

            $item = TpDominio::create($data);

            return $this->success($item, 'TpDominio created successfully.', 201);
        } catch (Throwable $e) {
            return $this->error('Failed to create TpDominio.', 500, $e->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $item = TpDominio::findOrFail($id);

            $data = $request->validate([
                'tp_dominio' => ['sometimes', 'string', 'max:255'],
                'navegacao' => ['nullable', 'string', 'max:255'],
                'subnavegacao' => ['nullable', 'string', 'max:255'],
                'rota' => ['nullable', 'string', 'max:255'],
                'publico' => ['nullable', 'boolean'],
                'datasource' => ['nullable', 'string'],
                'icone' => ['nullable', 'string', 'max:255'],
                'fonte_cor' => ['nullable', 'string', 'max:50'],
                'fundo_cor' => ['nullable', 'string', 'max:50'],
                'ativo' => ['nullable', 'boolean'],
                'subtitulo' => ['nullable', 'string', 'max:255'],
            ]);

            $item->update($data);

            return $this->success($item->fresh(), 'TpDominio updated successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('TpDominio not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to update TpDominio.', 500, $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $item = TpDominio::findOrFail($id);
            $item->delete();

            return $this->success(null, 'TpDominio deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('TpDominio not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to delete TpDominio.', 500, $e->getMessage());
        }
    }
}
