<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Session extends Model
{
    use HasDatabase;
    use HasDomains;

    protected $table = 'sessions';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity',
    ];

    protected $casts = [
        'last_activity' => 'integer',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'user_id', 'id');
    }
}

