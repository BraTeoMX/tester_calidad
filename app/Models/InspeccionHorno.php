<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionHorno extends Model
{
    use HasFactory;
    protected $table = 'inspeccion_horno';

    public function screen()
    {
        return $this->hasOne(InspeccionHornoScreen::class, 'inspeccion_horno_id');
    }

    public function plancha()
    {
        return $this->hasOne(InspeccionHornoPlancha::class, 'inspeccion_horno_id');
    }
}
