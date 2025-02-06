<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionHornoPlancha extends Model
{
    use HasFactory;
    protected $table = 'inspeccion_horno_plancha';

    public function inspeccion()
    {
        return $this->belongsTo(InspeccionHorno::class, 'inspeccion_horno_id');
    }

    public function defectos()
    {
        return $this->hasMany(InspeccionHornoPlanchaDefecto::class, 'inspeccion_horno_plancha_id');
    }
}
