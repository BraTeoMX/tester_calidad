<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccionCorrectScreen extends Model
{
    use HasFactory;
    protected $table = 'accioncorrectiva_screenprint';
    protected $fillable = [
        'id',
    'AccionCorrectiva'
    ];
}
