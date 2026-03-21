<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\HasDatabase;

class Estado extends Model
{
    use HasDatabase;
    public $table = 'estado';
    protected $fillable = ['nome', 'uf', 'ibge', 'pais_id', 'ddd'];

    public function pais(){
        return $this->belongsTo(Pais::class);
    }
}
