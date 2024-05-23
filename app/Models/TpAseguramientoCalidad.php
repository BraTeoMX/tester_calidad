<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TpAseguramientoCalidad extends Model
{
    use HasFactory;
    protected $table = 'tp_aseguramiento_calidad';

    public function aseguramientoCalidad()
    {
        return $this->belongsTo(AseguramientoCalidad::class, 'aseguramiento_calidad_id');
    }

}
