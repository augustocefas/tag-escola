<?php

namespace App\Http\Controllers\Helium;

use App\Models\{Estado, Pais};

class EstadoController extends HeliumController
{
    public function index(?string $pais_id=null)
    {
        $pais_id = $pais_id ?? 1;
        $estados = Estado::where('pais_id', $pais_id)->with('pais')->orderBy('nome')->get();
        $pais = Pais::findOrFail($pais_id);
        $paises = Pais::orderBy('nome_pt')->get();

        return view('helium.estado.index', compact('estados', 'pais', 'paises'));
    }

    public function store()
    {
        $validated = request()->validate([
            'nome' => 'required|string|max:128',
            'uf' => 'nullable|string|max:2|unique:estado,uf',
            'ibge' => 'nullable|integer',
            'pais_id' => 'required|exists:pais,id',
            'ddd' => 'nullable|string|max:64',
        ]);

        Estado::create($validated);

        return redirect()->route('helium.estado.index', $validated['pais_id'])->with('success', 'Estado criado com sucesso!');
    }

    public function update($id)
    {
        $estado = Estado::findOrFail($id);

        $validated = request()->validate([
            'nome' => 'required|string|max:128',
            'uf' => 'nullable|string|max:2|unique:estado,uf,' . $id,
            'ibge' => 'nullable|integer',
            'pais_id' => 'required|exists:pais,id',
            'ddd' => 'nullable|string|max:64',
        ]);

        $estado->update($validated);

        return redirect()->route('helium.estado.index', $validated['pais_id'])->with('success', 'Estado atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $estado = Estado::findOrFail($id);
        $pais_id = $estado->pais_id;

        // Check if estado has cidades
        $cidadesCount = \App\Models\Cidade::where('estado_id', $id)->count();
        if ($cidadesCount > 0) {
            return redirect()->route('helium.estado.index', $pais_id)->with('error', 'Não é possível excluir este estado pois existem ' . $cidadesCount . ' cidade(s) vinculada(s).');
        }

        $estado->delete();

        return redirect()->route('helium.estado.index', $pais_id)->with('success', 'Estado excluído com sucesso!');
    }
}
