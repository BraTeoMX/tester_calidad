<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteKanban extends Model
{
    use HasFactory;
    protected $table = 'reporte_kanban';

    protected $fillable = [];

    public function comentarios()
    {
        return $this->hasMany(ReporteKanbanComentario::class);
    }

}
