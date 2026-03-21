<?php

namespace App\Http\Controllers\Helium;

use App\Models\Domain;

class DomainController extends HeliumController
{

    public function index(){
        $domains = Domain::all();
        return view('helium.domain.index', compact('domains'));
    }

    public function store(){
        $validated = request()->validate([
            'domain' => 'required|string|max:255|unique:domain,domain',
            'navigatio_opc' => 'nullable|string|max:64',
            'navigation_subopc' => 'nullable|string|max:64',
            'datasource' => 'boolean',
            'icon' => 'nullable|string|max:32',
            'font_cor' => 'nullable|string|max:32',
            'bg_cor' => 'nullable|string|max:32',
            'ativo' => 'boolean',
        ]);
        $validated['datasource'] = $validated['datasource'] ?? false;
        $validated['ativo'] = $validated['ativo'] ?? false;
        Domain::create($validated);

        return redirect()->route('helium.domain.index')->with('success', 'Domínio criado com sucesso!');
    }

    public function update($id){
        $domain = Domain::findOrFail($id);
        
        $validated = request()->validate([
            'domain' => 'required|string|max:255|unique:domain,domain,' . $id,
            'navigatio_opc' => 'nullable|string|max:64',
            'navigation_subopc' => 'nullable|string|max:64',
            'datasource' => 'boolean',
            'icon' => 'nullable|string|max:32',
            'font_cor' => 'nullable|string|max:32',
            'bg_cor' => 'nullable|string|max:32',
            'ativo' => 'boolean',
        ]);
        $validated['datasource'] = $validated['datasource'] ?? false;
        $validated['ativo'] = $validated['ativo'] ?? false;
        $domain->update($validated);

        return redirect()->route('helium.domain.index')->with('success', 'Domínio atualizado com sucesso!');
    }

}
