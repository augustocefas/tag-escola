<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\HasDatabase;

class Pais extends Model
{
    use HasDatabase;
    public $table = 'pais';
    protected $fillable = ['nome', 'nome_pt', 'sigla', 'bacen'];
}
