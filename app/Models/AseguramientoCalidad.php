<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AseguramientoCalidad extends Model
{
    use HasFactory;
    protected $table = 'aseguramientos_calidad';

    public function tpAseguramientoCalidad()
    {
        return $this->hasMany(TpAseguramientoCalidad::class, 'aseguramiento_calidad_id');
    }
}
