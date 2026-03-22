<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class AlunoSala extends Model
{
    use HasDatabase;
    use HasDomains;

    protected $table = 'aluno_sala';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'aluno_id',
        'sala_id',
    ];

    protected $casts = [
        'id' => 'string',
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

    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'aluno_id', 'id');
    }

    public function sala()
    {
        return $this->belongsTo(Sala::class, 'sala_id', 'id');
    }
}

