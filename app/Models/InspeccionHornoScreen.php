<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionHornoScreen extends Model
{
    use HasFactory;
    protected $table = 'inspeccion_horno_screen';

    public function inspeccion()
    {
        return $this->belongsTo(InspeccionHorno::class, 'inspeccion_horno_id');
    }

    public function defectos()
    {
        return $this->hasMany(InspeccionHornoScreenDefecto::class, 'inspeccion_horno_screen_id');
    }
}
