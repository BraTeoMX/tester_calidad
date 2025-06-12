<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteKanbanCantidadParcial extends Model
{
    use HasFactory;
    protected $table = 'reporte_kanban_cantidades_parcial';

    protected $fillable = [
        'reporte_kanban_id',
        'cantidad',
        'cantidad_calidad',
        'created_at',
        'updated_at',
    ];

    public function reporte()
    {
        return $this->belongsTo(ReporteKanban::class, 'reporte_kanban_id');
    }

}
