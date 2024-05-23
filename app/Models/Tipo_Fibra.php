<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo_Fibra extends Model
{
    use HasFactory;
    protected $table = 'tipo_fibra';
    protected $fillable = [
        'id',
    'Tipo_Fibra'
    ];
}
