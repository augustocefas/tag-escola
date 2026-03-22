<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Aluno extends Model
{
    use HasDatabase;
    use HasDomains;
    use SoftDeletes;

    protected $table = 'aluno';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nome',
        'nascimento',
        'matricula',
        'anexo_id',
        'dados_adicionais',
    ];

    protected $casts = [
        'id' => 'string',
        'nascimento' => 'date',
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

    public function anexo()
    {
        return $this->belongsTo(Anexo::class, 'anexo_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_aluno', 'aluno_id', 'tag_id');
    }

    public function salas()
    {
        return $this->belongsToMany(Sala::class, 'aluno_sala', 'aluno_id', 'sala_id');
    }
}

