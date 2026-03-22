<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Sala extends Model
{
    use HasDatabase;
    use HasDomains;
    use SoftDeletes;

    protected $table = 'sala';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'tp_dominio_turno_id',
        'tp_dominio_periodo_id',
        'ano',
        'nome',
        'sigla',
        'dados_adicionais',
    ];

    protected $casts = [
        'id' => 'string',
        'ano' => 'integer',
        'dados_adicionais' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function turno()
    {
        return $this->belongsTo(Dominio::class, 'tp_dominio_turno_id', 'id');
    }

    public function periodo()
    {
        return $this->belongsTo(Dominio::class, 'tp_dominio_periodo_id', 'id');
    }

    public function alunos()
    {
        return $this->belongsToMany(Aluno::class, 'aluno_sala', 'sala_id', 'aluno_id');
    }

    public function usuarios()
    {
        return $this->belongsToMany(Usuarios::class, 'users_sala', 'sala_id', 'users_id');
    }
}

