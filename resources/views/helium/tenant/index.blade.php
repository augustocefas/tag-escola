@extends('helium.layout.app')
@section('title', 'Dashboard')
@section('content')

<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">Lista de Tenants</h1>

    @if(!isset($tenants) || $tenants->isEmpty())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <p class="text-yellow-700">Nenhum tenant encontrado.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Domínios</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Criado em</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tenants as $tenant)
                        <tr class="border-t">
                            
                            <td class="px-4 py-2 align-top text-sm text-gray-800">
                                @if(method_exists($tenant, 'domains') || isset($tenant->domains))
                                    @php
                                        // Tentativa segura de obter domínios relacionados
                                        $domains = null;
                                        try {
                                            $domains = $tenant->domains ?? null;
                                        } catch (Throwable $e) {
                                            $domains = null;
                                        }
                                    @endphp

                                    @if($domains && count($domains) > 0)
                                        {{ $domains->pluck('domain')->join(', ') }}
                                    @else
                                        <span class="text-gray-500">—</span>
                                    @endif
                                @else
                                    <span class="text-gray-500">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 align-top text-sm text-gray-800">{{ optional($tenant->created_at)->format('d/m/Y H:i') ?? '—' }}</td>
                            <td class="px-4 py-2 align-top text-sm text-gray-800">
                                {{-- Coloque aqui links/ações conforme suas rotas (ex.: route('tenants.show', $tenant)) --}}
                                <a href="#" class="text-blue-600 hover:underline mr-2">Ver</a>
                                <a href="#" class="text-blue-600 hover:underline mr-2">Selecionar</a>
                                <a href="#" class="text-green-600 hover:underline">Editar</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginação, se disponível --}}
        @if(method_exists($tenants, 'links'))
            <div class="mt-4">
                {{ $tenants->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
