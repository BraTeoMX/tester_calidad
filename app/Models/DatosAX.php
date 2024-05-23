<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosAX extends Model
{
    use HasFactory;
    protected $table = 'datos_auditorias';
    protected $fillable = [
        'id',
        'op',
        'cpo',
        'salesid',
        'estilo',
        'inventcolorid',
        'sizename',
        'qty',
        'statusOP',
        'custorname',


    ];


}
