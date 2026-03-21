<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Support\Str;

class Usuarios extends Model
{
    use HasDatabase;
    use HasDomains;

    protected $table = 'users';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $primaryKey = 'id';
    protected $casts = [
        'id' => 'string',
    ];


    protected static function booted(): void
    {
        static::creating(function ($s) {
            if (empty($s->id)) {
                $s->id = (string)Str::uuid();
            }
        });
    }

    protected $hidden = ['password', 'remember_token'];

    public function anexo()
    {
        return $this->belongsTo(Anexo::class, 'anexo_id', 'id');
    }
}
