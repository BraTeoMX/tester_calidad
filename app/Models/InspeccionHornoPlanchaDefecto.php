<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionHornoPlanchaDefecto extends Model
{
    use HasFactory;
    protected $table = 'inspeccion_horno_plancha_defecto';

    public function plancha()
    {
        return $this->belongsTo(InspeccionHornoPlancha::class, 'inspeccion_horno_plancha_id');
    }
}
