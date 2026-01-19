<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'horario_semanal'];

    protected $casts = [
        'horario_semanal' => 'array',
    ];
}
