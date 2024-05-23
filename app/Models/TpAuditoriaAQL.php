<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TpAuditoriaAQL extends Model
{
    use HasFactory;
    protected $table = 'tp_auditoria_aql';

    public function auditoriaAQL()
    {
        return $this->belongsTo(AuditoriaAQL::class, 'auditoria_aql_id');
    }
}
