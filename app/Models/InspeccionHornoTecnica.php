<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionHornoTecnica extends Model
{
    use HasFactory;
    protected $table = 'inspeccion_horno_tecnicas';

    protected $fillable = ['inspeccion_horno_id', 'nombre', 'descripcion'];

    public function inspeccion()
    {
        return $this->belongsTo(InspeccionHorno::class, 'inspeccion_horno_id');
    }
}
