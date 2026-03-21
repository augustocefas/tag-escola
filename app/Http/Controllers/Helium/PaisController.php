<?php

namespace App\Http\Controllers\Helium;

use App\Models\Pais;

class PaisController extends HeliumController
{
    public function index()
    {
        $paises = Pais::orderBy('nome_pt')->get();
        return view('helium.pais.index', compact('paises'));
    }

    public function store()
    {
        $validated = request()->validate([
            'nome' => 'required|string|max:64',
            'nome_pt' => 'nullable|string|max:64',
            'sigla' => 'nullable|string|max:2|unique:pais,sigla',
            'bacen' => 'nullable|integer',
        ]);

        Pais::create($validated);

        return redirect()->route('helium.pais.index')->with('success', 'País criado com sucesso!');
    }

    public function update($id)
    {
        $pais = Pais::findOrFail($id);
        
        $validated = request()->validate([
            'nome' => 'required|string|max:64',
            'nome_pt' => 'nullable|string|max:64',
            'sigla' => 'nullable|string|max:2|unique:pais,sigla,' . $id,
            'bacen' => 'nullable|integer',
        ]);

        $pais->update($validated);

        return redirect()->route('helium.pais.index')->with('success', 'País atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $pais = Pais::findOrFail($id);
        
        // Check if país has estados
        $estadosCount = \App\Models\Estado::where('pais_id', $id)->count();
        if ($estadosCount > 0) {
            return redirect()->route('helium.pais.index')->with('error', 'Não é possível excluir este país pois existem ' . $estadosCount . ' estado(s) vinculado(s).');
        }
        
        $pais->delete();

        return redirect()->route('helium.pais.index')->with('success', 'País excluído com sucesso!');
    }
}