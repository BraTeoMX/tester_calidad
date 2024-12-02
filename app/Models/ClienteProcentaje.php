<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteProcentaje extends Model
{
    use HasFactory;

    protected $table = 'clientes_porcentajes';

    // Columnas permitidas para asignación masiva
    protected $fillable = ['nombre'];
}
