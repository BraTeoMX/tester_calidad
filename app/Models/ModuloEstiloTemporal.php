<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuloEstiloTemporal extends Model
{
    use HasFactory;
    protected $table = 'job_modulo_estilo_temporal';

    protected $fillable = [
        'moduleid',
        'itemid',
        'custname',
    ];
}
