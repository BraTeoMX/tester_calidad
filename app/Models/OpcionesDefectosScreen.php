<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpcionesDefectosScreen extends Model
{
    use HasFactory;
    protected $table = 'defectos_screenprint';
    protected $fillable = [
        'id',
    'Defecto'
    ];
}
