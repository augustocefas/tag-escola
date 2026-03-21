<?php

namespace App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Support\Str;

class Config extends Model
{
    use HasDatabase, HasDomains;

    protected $table = 'config';
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
    ];

    protected $fillable = [
        'key',
        'subkey',
        'value',
        'tipo_dominio_id',
        'dominio_id',
    ];

    public function tipoDominio(){
        return $this->belongsTo(TipoDominio::class, 'tipo_dominio_id', 'id');
    }

    public function dominio(){
        return $this->belongsTo(Dominio::class, 'dominio_id', 'id');
    }
}
