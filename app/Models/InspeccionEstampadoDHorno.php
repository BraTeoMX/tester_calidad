<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionEstampadoDHorno extends Model
{
    use HasFactory;
    protected $table = 'inspecestamphor';
    protected $fillable = [
                'Auditor',
                'Status',
                'Cliente',
                'Estilo' ,
                'OP_Defec',
                'Tecnico',
                'Color',
                'Num_Grafico',
                'Tipo_Maquina',
                'LeyendaSprint',
                'Tecnica' ,
                'Piezas_Auditar' ,
                'Fibras',
                'Porcen_Fibra',
                'Hora',
                'Bulto',
                'Talla',
                'Tipo_Problema',
                'Num_Problemas' ,
                'Ac_Correctiva'
            ];
}
