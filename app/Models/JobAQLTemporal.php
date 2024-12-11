<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAQLTemporal extends Model
{
    use HasFactory;
    protected $table = 'job_aql_temporal';

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
