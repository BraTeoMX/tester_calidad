<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horno_Banda extends Model
{
    use HasFactory;
    protected $table = 'horno_banda';
    protected $fillable = [
        'id',
        'Tem_Horno',
        'Vel_Banda',
        'created_at'
    ];
}
