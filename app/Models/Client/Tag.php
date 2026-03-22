<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tag extends Model
{
    use HasDatabase;
    use HasDomains;

    protected $table = 'tag';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'apelido',
        'mac_address',
        'key',
        'passkey',
        'responsavel',
        'dados_adicionais',
    ];

    protected $casts = [
        'id' => 'string',
        'dados_adicionais' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function alunos()
    {
        return $this->belongsToMany(Aluno::class, 'tag_aluno', 'tag_id', 'aluno_id');
    }
}

