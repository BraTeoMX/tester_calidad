<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maquilas extends Model
{
    use HasFactory;
    protected $table = 'maquila';
    protected $fillable = [
                'Auditor',
                'Status',
                'Descripcion',
                'Cliente',
                'Estilo' ,
                'OP_Defec',
                'Maquina',
                'Tecnico',
                'Corte',
                'Color',
                'Talla',
                'Piezas_Auditar',
                'Tipo_Problema',
                'Num_Problemas',
                'Ac_Correctiva'
            ];
}
