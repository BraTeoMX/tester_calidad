<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAQLHistorial extends Model
{
    use HasFactory;
    protected $table = 'job_aql_v2';
    protected $connection = 'mysql';
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
}
