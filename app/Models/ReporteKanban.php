<?php

/**
 * @property int $id
 * @property string $op
 * @property string $cliente
 * @property string $estilo
 * @property int $piezas
 * @property string $planta
 * @property string|null $fecha_online
 * // ...agrega aquí otras columnas según tu migración...
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteKanban extends Model
{
    use HasFactory;
    protected $table = 'reporte_kanban';

    protected $fillable = [
        'auditor',
        'fecha_corte',
        'fecha_almacen',
        'op',
        'cliente',
        'estilo',
        'piezas',
        'planta',
        'estatus',
        'fecha_liberacion',
        'fecha_parcial',
        'fecha_rechazo',
        'fecha_online', // Added this field
    ];

    public function comentarios()
    {
        return $this->hasMany(ReporteKanbanComentario::class);
    }

    public function cantidades_parciales()
    {
        return $this->hasMany(ReporteKanbanCantidadParcial::class);
    }

}
