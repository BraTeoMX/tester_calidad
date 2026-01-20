<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'horario_semanal', 'estatus', 'planta'];

    protected $casts = [
        'horario_semanal' => 'array',
        'estatus' => 'boolean',
        'planta' => 'integer',
    ];

    public function getEstatusLabelAttribute()
    {
        return $this->estatus ? 'Activo' : 'Inactivo';
    }

    public function getEstatusBadgeClassAttribute()
    {
        return $this->estatus ? 'badge-info' : 'badge-dark';
    }

    public function getPlantaLabelAttribute()
    {
        switch ($this->planta) {
            case 1:
                return 'Ixtlahuaca';
            case 2:
                return 'San Bartolo';
            case 0:
            default:
                return 'Ambos';
        }
    }

    public function getPlantaBadgeAttribute() // Keeping simpler name convention if desired, or use planta_badge_class
    {
        // User used badge-brown for all, but we can differentiate if needed. 
        // For now user code had badge-brown for all.
        return 'badge-brown';
    }
}
