<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAQLTemporal extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'job_aql_temporal';
    public $timestamps = true;

    protected $fillable = [
        'payrolldate',
        'prodpackticketid',
        'qty',
        'moduleid',
        'prodid',
        'itemid',
        'colorname',
        'customername',
        'inventcolorid',
        'inventsizeid',
    ];

    protected $casts = [
        'payrolldate' => 'datetime', // O 'datetime' según tu necesidad
        'qty' => 'integer',
    ];
}
