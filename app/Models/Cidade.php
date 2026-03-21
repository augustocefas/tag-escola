<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\HasDatabase;

class Cidade extends Model
{
    use HasDatabase;
    public $table = 'cidade';
    protected $fillable = ['nome', 'estado_id', 'ibge'];

    public function estado(){
        return $this->belongsTo(Estado::class)->with('pais');
    }
}
