<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionHornoScreenDefecto extends Model
{
    use HasFactory;
    protected $table = 'inspeccion_horno_screen_defecto';

    public function screen()
    {
        return $this->belongsTo(InspeccionHornoScreen::class, 'inspeccion_horno_screen_id');
    }
}

