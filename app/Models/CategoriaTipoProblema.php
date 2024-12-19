<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaTipoProblema extends Model
{
    use HasFactory;
    protected $table = 'categoria_tipos_problemas';

    // Agregar las columnas que pueden ser asignadas masivamente
    protected $fillable = ['nombre', 'area'];
}
