<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteAuditoriaEtiqueta extends Model
{
    use HasFactory;
    protected $table = 'reporte_auditoria_etiquetas';

    // Campos permitidos para asignación masiva
    protected $fillable = [
        'tipo',
        'orden',
        'estilo',
        'color',
        'talla',
        'cantidad',
        'muestreo',
        'estatus',
    ];

    // Relación: Un reporte puede tener muchos defectos asociados
    public function defectos()
    {
        return $this->hasMany(TpReporteAuditoriaEtiqueta::class, 'id_reporte_auditoria_etiquetas');
    }
}
