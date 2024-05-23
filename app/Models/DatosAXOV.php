<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosAXOV extends Model
{
    use HasFactory;
    protected $table = 'datos_auditoriasov';
    protected $fillable = [
        'id',
        'op',
        'cpo',
        'salesid',
        'Estilos',
        'inventcolorid',
        'sizename',
        'qty',
        'status',
        'custorname',


    ];

}
