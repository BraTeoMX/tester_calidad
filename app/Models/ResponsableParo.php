<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponsableParo extends Model
{
    use HasFactory;

    protected $table = 'responsables_paro';

    protected $fillable = [
        'auditoria_aql_id',
        'aseguramiento_calidad_id',
        'nombre',
        'razon_ajuste'
    ];

    public function auditoriaAql()
    {
        return $this->belongsTo(AuditoriaAQL::class, 'auditoria_aql_id');
    }

    public function aseguramientoCalidad()
    {
        return $this->belongsTo(AseguramientoCalidad::class, 'aseguramiento_calidad_id');
    }
}
