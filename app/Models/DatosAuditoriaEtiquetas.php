<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosAuditoriaEtiquetas extends Model
{
    use HasFactory;
    protected $table = 'auditoria_etiquetas';
    protected $fillable = [
    'id',
    'OrdenCalidad',
    'OrdenCompra',
    'Proveedor',
    'Estilos',
    'Descripcion',
    'Cantidad',
    'inventdimid',
    'Talla',
    'Color',
    'Lotes',
    'year',
    'status',
    ];
}
