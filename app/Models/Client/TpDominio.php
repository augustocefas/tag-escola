<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Support\Str;

class TpDominio extends Model
{
    use HasDatabase;
    use HasDomains;

    protected $table = 'tp_dominio';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $guarded = [];
    protected static function booted(): void
    {
        static::creating(function ($s) {
            if (empty($s->id)) {
                $s->id = (string) Str::uuid();
            }
        });
    }

    protected $casts = [
        'id' => 'string',
        'ativo' => 'boolean',

    ];

    protected $fillable = [
        'tp_dominio',
        'navegacao',
        'subnavegacao',
        'rota',
        'publico',
        'datasource',
        'icone',
        'fonte_cor',
        'fundo_cor',
        'ativo',
        'subtitulo',
    ];
}
