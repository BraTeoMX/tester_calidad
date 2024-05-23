<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditoriaFinal extends Model
{
    use HasFactory;
    protected $table = 'auditoria_final';
    public function datoAX()
    {
        return $this->belongsTo(DatoAX::class, 'dato_ax_id');
    }
}
