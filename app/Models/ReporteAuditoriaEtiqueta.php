<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteAuditoriaEtiqueta extends Model
{
    use HasFactory;
    protected $table = 'reporte_auditoria_etiquetas';

    protected $fillable = [
        'id',
        'Orden',
        'Estilos',
        'Cantidad',
        'Muestreo',
        'Defectos',
        'Tipo_Defectos',
        'Talla',
        'Color',
        'Status',
        ];

}
