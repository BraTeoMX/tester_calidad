<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TpReporteAuditoriaEtiqueta extends Model
{
    use HasFactory;
    protected $table = 'tp_auditoria_etiquetas';


    // Relación: Cada defecto pertenece a un reporte
    public function reporte()
    {
        return $this->belongsTo(ReporteAuditoriaEtiqueta::class, 'id_reporte_auditoria_etiquetas');
    }
}
