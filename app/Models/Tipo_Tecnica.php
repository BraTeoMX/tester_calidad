<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo_Tecnica extends Model
{
    use HasFactory;
    protected $table = 'tipo_tecnica';
    protected $fillable = [
        'id',
    'Tipo_tecnica'
    ];
}
