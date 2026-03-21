<?php

namespace App\Http\Controllers\Helium;

use App\Models\{Cidade, Estado};


class CidadeController extends HeliumController
{
    public function index(?string $estado_id=null)
    {
        // Aceita estado_id tanto da rota quanto da query string
        $estado_id = $estado_id ?? request('estado_id');
        
        $query = Cidade::query();
        $estado = null;
        if($estado_id){
            $query->where('estado_id', $estado_id);
            $estado = Estado::with('pais')->findOrFail($estado_id);
        }
        $cidades = $query->with('estado.pais')->orderBy('nome')->paginate(15)->appends(['estado_id' => $estado_id]);
        $estados = Estado::with('pais')->orderBy('nome')->get();

        return view('helium.cidade.index', compact('cidades', 'estado', 'estados'));
    }

    public function store()
    {
        $validated = request()->validate([
            'nome' => 'required|string|max:128',
            'estado_id' => 'required|exists:estado,id',
            'ibge' => 'nullable|integer',
        ]);

        Cidade::create($validated);

        return redirect()->route('helium.cidade.index', $validated['estado_id'])->with('success', 'Cidade criada com sucesso!');
    }

    public function update($id)
    {
        $cidade = Cidade::findOrFail($id);

        $validated = request()->validate([
            'nome' => 'required|string|max:128',
            'estado_id' => 'required|exists:estado,id',
            'ibge' => 'nullable|integer',
        ]);

        $cidade->update($validated);

        return redirect()->route('helium.cidade.index', $validated['estado_id'])->with('success', 'Cidade atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $cidade = Cidade::findOrFail($id);
        $estado_id = $cidade->estado_id;
        $cidade->delete();

        return redirect()->route('helium.cidade.index', $estado_id)->with('success', 'Cidade excluída com sucesso!');
    }
}
