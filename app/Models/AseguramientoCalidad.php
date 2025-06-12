<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AseguramientoCalidad extends Model
{
    use HasFactory;
    protected $table = 'aseguramientos_calidad';

    protected $fillable = [
        'fin_paro_modular',
        'minutos_paro_modular',
        // Agrega aquí cualquier otro campo que necesites actualizar masivamente
    ];

    public function tpAseguramientoCalidad()
    {
        return $this->hasMany(TpAseguramientoCalidad::class, 'aseguramiento_calidad_id');
    }

    public function responsableParo()
    {
        return $this->hasOne(ResponsableParo::class, 'aseguramiento_calidad_id');
    }

}
