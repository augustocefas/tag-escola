<?php

namespace App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Support\Str;

class Dominio extends Model
{
    use HasDatabase;
    use HasDomains;
    use SoftDeletes;

    protected $table = 'dominio';
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
        'tp_dominio_id',
        'anexo_id',
        'dominio',
        'navegacao',
        'subnavegacao',
        'rota',
        'publico',
        'datasource',
        'icone',
        'fonte_cor',
        'fundo_cor',
        'ativo',
    ];

    public function tipoDominio(){
        return $this->belongsTo(TpDominio::class, 'tp_dominio_id', 'id');
    }
    public function anexo(){
        return $this->belongsTo(Anexo::class, 'anexo_id', 'id');
    }
}

