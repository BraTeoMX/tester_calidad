<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcesoPlancha extends Model
{
    use HasFactory;
    protected $table = 'calidad_plancha';
    protected $fillable = [
                'Auditor',
                'Status',
                'Cliente',
                'Estilo' ,
                'OP_Defec',
                'Tecnico',
                'Color',
                'Num_Grafico',
                'Piezas_Auditar',
                'Tipo_Problema',
                'Num_Problemas',
                'Ac_Correctiva'
            ];

}
