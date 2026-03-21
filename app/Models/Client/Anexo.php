<?php

namespace App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Anexo extends Model
{
    use HasDatabase;
    use HasDomains;
    use SoftDeletes;

    protected $table = 'anexo';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $primaryKey = 'id';
    protected $hidden=['created_at', 'updated_at'];
    protected $guarded = [];
    protected static function booted(): void
    {
        static::creating(function ($s) {
            if (empty($s->id)) {
                $s->id = (string) Str::uuid();
            }
        });
    }




}
