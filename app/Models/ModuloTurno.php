<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuloTurno extends Model
{
    use HasFactory;

    protected $table = 'modulo_turnos';

    protected $fillable = ['modulo', 'turno_id', 'fecha'];

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turno_id');
    }
}
