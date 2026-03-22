<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class PasswordResetToken extends Model
{
    use HasDatabase;
    use HasDomains;

    protected $table = 'password_reset_tokens';
    protected $primaryKey = 'email';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}

