<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
        }

        .header {
            background-color: #1a3a5c;
            color: white;
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .header p {
            font-size: 10px;
            opacity: 0.8;
            margin-top: 3px;
        }

        .escritorio-info {
            font-size: 10px;
            text-align: right;
            color: rgba(255,255,255,0.9);
        }

        .header-inner {
            display: flex; /* DomPDF suporta limitado, use table se necessário */
        }

        .section {
            margin: 0 20px 15px 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }

        .section-title {
            background-color: #2c5f8a;
            color: white;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .section-body {
            padding: 10px 12px;
        }

        table.fields {
            width: 100%;
            border-collapse: collapse;
        }

        table.fields td {
            padding: 5px 8px;
            vertical-align: top;
            border-bottom: 1px solid #f0f0f0;
        }

        table.fields tr:last-child td {
            border-bottom: none;
        }

        .field-label {
            font-weight: bold;
            color: #555;
            width: 35%;
            font-size: 10px;
            text-transform: uppercase;
        }

        .field-value {
            color: #222;
            width: 65%;
        }

        .field-value.empty {
            color: #aaa;
            font-style: italic;
        }

        .two-col table.fields .field-label {
            width: 40%;
        }

        .grid {
            width: 100%;
            border-collapse: collapse;
        }

        .grid td {
            vertical-align: top;
            padding: 0 5px;
        }

        .grid td:first-child {
            padding-left: 0;
        }

        .grid td:last-child {
            padding-right: 0;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            background-color: #e8f4e8;
            color: #2d7a2d;
            border: 1px solid #b8ddb8;
        }

        .footer {
            margin: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 9px;
            color: #aaa;
            text-align: center;
        }

        .obs-box {
            background-color: #fffbf0;
            border-left: 3px solid #f0c040;
            padding: 8px 12px;
            font-size: 11px;
            color: #555;
        }
    </style>
</head>
<body>

{{-- HEADER --}}
<table style="width:100%; background-color:#1a3a5c; color:white; padding:15px 20px; margin-bottom:20px;">
    <tr>
        <td style="padding: 15px 20px;">
            <div style="font-size:18px; font-weight:bold; color:white;">FICHA CADASTRAL</div>
            <div style="font-size:10px; color:rgba(255,255,255,0.8); margin-top:3px;">
                Gerado em {{ \Carbon\Carbon::now()->format('d/m/Y \à\s H:i') }}
            </div>
        </td>
        <td style="padding: 15px 20px; text-align:right; font-size:10px; color:rgba(255,255,255,0.9);">
            <strong>{{ $pessoa->escritorio->nome ?? '' }}</strong><br>
            {{ $pessoa->escritorio->cidade ?? '' }} / {{ $pessoa->escritorio->estado ?? '' }}<br>
            {{ $pessoa->escritorio->telefone ?? '' }}<br>
            {{ $pessoa->escritorio->email ?? '' }}
        </td>
    </tr>
</table>

{{-- IDENTIFICAÇÃO --}}
<div class="section">
    <div class="section-title">Identificação</div>
    <div class="section-body">
        <table class="fields">
            <tr>
                <td class="field-label">Nome</td>
                <td class="field-value">{{ $pessoa->nome }}</td>
                <td class="field-label">Apelido</td>
                <td class="field-value">{{ $pessoa->apelido ?? '—' }}</td>
            </tr>
            <tr>
                <td class="field-label">Tipo</td>
                <td class="field-value">
                    <span class="badge">{{ $pessoa->tipo_pessoa_dominio->dominio ?? '—' }}</span>
                </td>
                <td class="field-label">PF / PJ</td>
                <td class="field-value">{{ strtoupper($pessoa->pfpj) == 'PF' ? 'Pessoa Física' : 'Pessoa Jurídica' }}</td>
            </tr>
            <tr>
                <td class="field-label">CPF / CNPJ</td>
                <td class="field-value">{{ $pessoa->cpf_cnpj ?? '—' }}</td>
                <td class="field-label">Código</td>
                <td class="field-value">{{ $pessoa->codigo ?? '—' }}</td>
            </tr>
        </table>
    </div>
</div>

{{-- DADOS PESSOAIS --}}
@if($pessoa->pfpj == 'pf')
    <div class="section">
        <div class="section-title">Dados Pessoais</div>
        <div class="section-body">
            <table class="fields">
                <tr>
                    <td class="field-label">Nome do Pai</td>
                    <td class="field-value">{{ $pessoa->nome_pai ?? '—' }}</td>
                    <td class="field-label">Nome da Mãe</td>
                    <td class="field-value">{{ $pessoa->nome_mae ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="field-label">Data de Nascimento</td>
                    <td class="field-value">
                        {{ $pessoa->nascimento ? \Carbon\Carbon::parse($pessoa->nascimento)->format('d/m/Y') : '—' }}
                    </td>
                    <td class="field-label">Naturalidade</td>
                    <td class="field-value">{{ $pessoa->naturalidade ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="field-label">Sexo</td>
                    <td class="field-value">{{ $pessoa->sexo_dominio->dominio ?? '—' }}</td>
                    <td class="field-label">Estado Civil</td>
                    <td class="field-value">{{ $pessoa->estado_civil_dominio->dominio ?? '—' }}</td>
                </tr>
                @if(!empty($pessoa->conjuge_nome))
                    <tr>
                        <td class="field-label">Cônjuge</td>
                        <td class="field-value" colspan="3">{{ $pessoa->conjuge_nome }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="field-label">Profissão</td>
                    <td class="field-value">{{ $pessoa->profissao ?? '—' }}</td>
                    <td class="field-label">Nacionalidade</td>
                    <td class="field-value">{{ $pessoa->naturalidade ?? '—' }}</td>
                </tr>
            </table>
        </div>
    </div>
@endif

{{-- DOCUMENTOS --}}
<div class="section">
    <div class="section-title">Documentos</div>
    <div class="section-body">
        <table class="fields">
            <tr>
                <td class="field-label">RG / IE</td>
                <td class="field-value">{{ $pessoa->rg_ie ?? '—' }}</td>
                <td class="field-label">Órgão Emissor</td>
                <td class="field-value">{{ $pessoa->orgao_emissor ?? '—' }} {{ $pessoa->orgao_emissor_uf ? '/ ' . $pessoa->orgao_emissor_uf : '' }}</td>
            </tr>
            @if(!empty($pessoa->oab))
                <tr>
                    <td class="field-label">OAB</td>
                    <td class="field-value">{{ $pessoa->oab }} {{ $pessoa->oab_uf ? '/ ' . $pessoa->oab_uf : '' }}</td>
                    <td class="field-label">IM</td>
                    <td class="field-value">{{ $pessoa->im ?? '—' }}</td>
                </tr>
            @endif
        </table>
    </div>
</div>

{{-- ENDEREÇO --}}
<div class="section">
    <div class="section-title">Endereço</div>
    <div class="section-body">
        <table class="fields">
            <tr>
                <td class="field-label">Logradouro</td>
                <td class="field-value">{{ $pessoa->logradouro ?? '—' }}, {{ $pessoa->numero ?? 'S/N' }}</td>
                <td class="field-label">Complemento</td>
                <td class="field-value">{{ $pessoa->complemento ?? '—' }}</td>
            </tr>
            <tr>
                <td class="field-label">Bairro</td>
                <td class="field-value">{{ $pessoa->bairro ?? '—' }}</td>
                <td class="field-label">CEP</td>
                <td class="field-value">{{ $pessoa->cep ?? '—' }}</td>
            </tr>
            <tr>
                <td class="field-label">Cidade</td>
                <td class="field-value">{{ $pessoa->cidade ?? '—' }}</td>
                <td class="field-label">Estado</td>
                <td class="field-value">{{ $pessoa->estado ?? '—' }}</td>
            </tr>
        </table>
    </div>
</div>

{{-- CONTATO --}}
<div class="section">
    <div class="section-title">Contato</div>
    <div class="section-body">
        <table class="fields">
            <tr>
                <td class="field-label">Telefone</td>
                <td class="field-value">{{ $pessoa->telefone ?? '—' }}</td>
                <td class="field-label">Celular</td>
                <td class="field-value">{{ $pessoa->celular ?? '—' }}</td>
            </tr>
            <tr>
                <td class="field-label">E-mail</td>
                <td class="field-value" colspan="3">{{ $pessoa->email ?? '—' }}</td>
            </tr>
        </table>
    </div>
</div>

{{-- OBSERVAÇÕES --}}
@if(!empty($pessoa->observacao))
    <div class="section">
        <div class="section-title">Observações</div>
        <div class="section-body">
            <div class="obs-box">{{ $pessoa->observacao }}</div>
        </div>
    </div>
@endif

{{-- FOOTER --}}
<div class="footer">
    Documento gerado automaticamente em {{ \Carbon\Carbon::now()->format('d/m/Y \à\s H:i:s') }} &mdash; {{ $pessoa->escritorio->nome ?? '' }}
</div>

</body>
</html>
