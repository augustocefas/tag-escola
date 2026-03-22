<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class UsersSala extends Model
{
    use HasDatabase;
    use HasDomains;

    protected $table = 'users_sala';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'users_id',
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

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'users_id', 'id');
    }

    public function sala()
    {
        return $this->belongsTo(Sala::class, 'sala_id', 'id');
    }
}

