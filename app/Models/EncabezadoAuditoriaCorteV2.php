<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncabezadoAuditoriaCorteV2 extends Model
{
    use HasFactory;
    protected $table = 'auditoria_corte_encabezado';

    /**
     * Relación con AuditoriaCorteMarcada
     */
    public function marcada()
    {
        return $this->hasOne(AuditoriaCorteMarcada::class, 'encabezado_id');
    }

    /**
     * Relación con AuditoriaCorteTendido
     */
    public function tendido()
    {
        return $this->hasOne(AuditoriaCorteTendido::class, 'encabezado_id');
    }

    /**
     * Relación con AuditoriaCorteLectra
     */
    public function lectra()
    {
        return $this->hasOne(AuditoriaCorteLectra::class, 'encabezado_id');
    }

    /**
     * Relación con AuditoriaCorteBulto
     */
    public function bulto()
    {
        return $this->hasOne(AuditoriaCorteBulto::class, 'encabezado_id');
    }

    /**
     * Relación con AuditoriaCorteFinal
     */
    public function final()
    {
        return $this->hasOne(AuditoriaCorteFinal::class, 'encabezado_id');
    }

    /**
     * Scope para filtrar por fechas
     */
    public function scopeEntreFechas($query, $fechaDesde, $fechaHasta)
    {
        return $query->whereBetween('created_at', [$fechaDesde, $fechaHasta]);
    }

    /**
     * Scope para filtrar por OP
     */
    public function scopePorOrden($query, $ordenId)
    {
        return $query->where('orden_id', 'LIKE', '%' . $ordenId . '%');
    }

    /**
     * Scope para filtrar por estatus
     */
    public function scopePorEstatus($query, $estatus)
    {
        switch ($estatus) {
            case '1':
                return $query->whereHas('final', function ($q) {
                    $q->where('aceptado_rechazado', 1);
                });
            case '2':
                return $query->whereHas('final', function ($q) {
                    $q->where('aceptado_rechazado', 0)
                        ->whereNotNull('aceptado_condicion');
                });
            case '3':
                return $query->whereHas('final', function ($q) {
                    $q->where('aceptado_rechazado', 0)
                        ->whereNull('aceptado_condicion');
                });
            default:
                return $query;
        }
    }
}
