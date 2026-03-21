<?php

namespace App\Http\Controllers\Helium;

use App\Models\{Subdomain, Domain};

class SubdomainController extends HeliumController
{
    public function index(string $domainId)
    {
        $subdomains = Subdomain::all();
        $domain = Domain::where('id', $domainId)->first();
        return view('helium.subdomain.index', compact('subdomains', 'domain'));
    }

    public function store(){

        $validated = request()->validate([
            'subdomain' => 'required|string|max:255|unique:subdomain,subdomain',
            'domain_id' => 'required|exists:domain,id',
            'icon' => 'nullable|string|max:32',
            'font_cor' => 'nullable|string|max:32',
            'bg_cor' => 'nullable|string|max:32',
            'ativo' => 'boolean',
        ]);
        $validated['ativo'] = $validated['ativo'] ?? false;
        Subdomain::create($validated);

        return redirect()->route('helium.subdomain.index', $validated['domain_id'])->with('success', 'Subdomínio criado com sucesso!');
    }

    public function update($id){
        $subdomain = Subdomain::findOrFail($id);
        
        $validated = request()->validate([
            'subdomain' => 'required|string|max:255|unique:subdomain,subdomain,' . $id,
            'domain_id' => 'required|exists:domain,id',
            'icon' => 'nullable|string|max:32',
            'font_cor' => 'nullable|string|max:32',
            'bg_cor' => 'nullable|string|max:32',
            'ativo' => 'boolean',
        ]);
        $validated['ativo'] = $validated['ativo'] ?? false;
        $subdomain->update($validated);

        return redirect()->route('helium.subdomain.index', $validated['domain_id'])->with('success', 'Subdomínio atualizado com sucesso!');
    }

    public function destroy($id){
        $subdomain = Subdomain::findOrFail($id);
        $subdomain->delete();

        return redirect()->route('helium.subdomain.index', $subdomain->domain_id)->with('success', 'Subdomínio excluído com sucesso!');
    }
}