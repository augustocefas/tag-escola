<?php

namespace App\Http\Controllers\Client;

use App\Models\Client\Sala;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Throwable;

class SalaController extends AController
{
    public function index(Request $request)
    {
        try {
            $query = Sala::query();

            if ($request->filled('nome')) {
                $query->where('nome', 'like', '%' . $request->get('nome') . '%');
            }

            if ($request->filled('sigla')) {
                $query->where('sigla', 'like', '%' . $request->get('sigla') . '%');
            }

            if ($request->filled('ano')) {
                $query->where('ano', $request->get('ano'));
            }

            if ($request->filled('tp_dominio_turno_id')) {
                $query->where('tp_dominio_turno_id', $request->get('tp_dominio_turno_id'));
            }

            if ($request->filled('tp_dominio_periodo_id')) {
                $query->where('tp_dominio_periodo_id', $request->get('tp_dominio_periodo_id'));
            }

            $data = $request->filled('per_page')
                ? $query->with(['turno', 'periodo'])->paginate((int) $request->get('per_page'))
                : $query->with(['turno', 'periodo'])->get();

            return $this->success($data, 'Sala list retrieved successfully.');
        } catch (Throwable $e) {
            return $this->error('Failed to retrieve Sala list.', 500, $e->getMessage());
        }
    }

    public function show(string $id)
    {
        try {
            $item = Sala::with(['turno', 'periodo', 'alunos', 'usuarios'])->findOrFail($id);

            return $this->success($item, 'Sala retrieved successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Sala not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to retrieve Sala.', 500, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'tp_dominio_turno_id' => ['nullable', 'string', 'exists:dominio,id'],
                'tp_dominio_periodo_id' => ['nullable', 'string', 'exists:dominio,id'],
                'ano' => ['required', 'integer', 'min:2000'],
                'nome' => ['required', 'string', 'max:255'],
                'sigla' => ['nullable', 'string', 'max:50'],
                'dados_adicionais' => ['nullable', 'array'],
            ]);

            $item = Sala::create($data);

            if ($request->filled('alunos')) {
                $item->alunos()->sync($request->get('alunos'));
            }

            if ($request->filled('usuarios')) {
                $item->usuarios()->sync($request->get('usuarios'));
            }

            return $this->success($item->load(['turno', 'periodo', 'alunos', 'usuarios']), 'Sala created successfully.', 201);
        } catch (Throwable $e) {
            return $this->error('Failed to create Sala.', 500, $e->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $item = Sala::findOrFail($id);

            $data = $request->validate([
                'tp_dominio_turno_id' => ['nullable', 'string', 'exists:dominio,id'],
                'tp_dominio_periodo_id' => ['nullable', 'string', 'exists:dominio,id'],
                'ano' => ['sometimes', 'integer', 'min:2000'],
                'nome' => ['sometimes', 'string', 'max:255'],
                'sigla' => ['nullable', 'string', 'max:50'],
                'dados_adicionais' => ['nullable', 'array'],
            ]);

            $item->update($data);

            if ($request->filled('alunos')) {
                $item->alunos()->sync($request->get('alunos'));
            }

            if ($request->filled('usuarios')) {
                $item->usuarios()->sync($request->get('usuarios'));
            }

            return $this->success($item->fresh(['turno', 'periodo', 'alunos', 'usuarios']), 'Sala updated successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Sala not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to update Sala.', 500, $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $item = Sala::findOrFail($id);
            $item->delete();

            return $this->success(null, 'Sala deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Sala not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to delete Sala.', 500, $e->getMessage());
        }
    }
}
