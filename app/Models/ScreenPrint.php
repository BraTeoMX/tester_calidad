<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreenPrint extends Model
{
    use HasFactory;
    protected $table = 'screenprint';
    protected $fillable = [
                'Auditor',
                'Status',
                'Cliente',
                'Estilo' ,
                'OP_Defec',
                'Tecnico',
                'Color',
                'Num_Grafico',
                'Tecnica' ,
                'Fibras',
                'Porcen_Fibra',
                'Piezas_Auditar',
                'Tipo_Problema',
                'Num_Problemas',
                'Ac_Correctiva',
                'created_at',
                'updated_at'
            ];
}
