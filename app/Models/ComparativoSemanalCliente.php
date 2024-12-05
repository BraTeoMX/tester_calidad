<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComparativoSemanalCliente extends Model
{
    use HasFactory;

    protected $table = 'comparativo_semanal_clientes';

    /**
     * Atributos que se pueden asignar de forma masiva.
     */
    protected $fillable = [
        'semana',
        'anio',
        'cliente',
        'estilo',
        'modulo',
        'planta',
        'cantidad_auditada_aql',
        'cantidad_rechazada_aql',
        'cantidad_auditada_proceso',
        'cantidad_rechazada_proceso',
        'porcentaje_aql',
        'porcentaje_proceso',
        'created_at',
        'updated_at',
    ];

    /**
     * Deshabilita la asignación masiva para cualquier atributo no listado.
     */
    protected $guarded = [];
}
