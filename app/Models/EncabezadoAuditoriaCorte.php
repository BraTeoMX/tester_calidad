<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncabezadoAuditoriaCorte extends Model
{
    use HasFactory;
    protected $table = 'encabezado_auditoria_cortes';

    protected $fillable = [
        'dato_ax_id','orden_id', 'estilo_id', 'planta_id', 'temporada_id', 'cliente_id', 'color_id', 'evento', 'total_evento', 
        'estatus', 'estatus_evaluacion_corte', 'material', 'pieza', 'trazo', 'lienzo',
    ];

    public function datoAX()
    {
        return $this->belongsTo(DatoAX::class, 'dato_ax_id');
    }
}
