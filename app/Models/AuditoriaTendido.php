<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditoriaTendido extends Model
{
    use HasFactory;
    protected $table = 'auditoria_tendidos';
    public function datoAX()
    {
        return $this->belongsTo(DatoAX::class, 'dato_ax_id');
    }
}
