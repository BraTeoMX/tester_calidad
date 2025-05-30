<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteKanbanComentario extends Model
{
    use HasFactory;
    protected $table = 'reporte_kanban_comentarios';

    protected $fillable = [
        'reporte_kanban_id',
        'nombre',
        'created_at',
        'updated_at',
    ];

    public function reporte()
    {
        return $this->belongsTo(ReporteKanban::class, 'reporte_kanban_id');
    }

}
