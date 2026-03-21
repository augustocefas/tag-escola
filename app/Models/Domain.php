<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Concerns\HasDatabase;

class Domain extends Model
{
    use HasDatabase;
    protected $table = 'domain';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'domain',
        'navigatio_opc',
        'navigation_subopc',
        'datasource',
        'icon',
        'font_cor',
        'bg_cor',
        'ativo',
    ];

    protected static function booted(): void
    {
        static::creating(function ($tenant) {
            if (empty($tenant->id)) {
                $tenant->id = (string) Str::uuid();
            }
        });
    }
}
