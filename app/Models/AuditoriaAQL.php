<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditoriaAQL extends Model
{
    use HasFactory;
    protected $table = 'auditoria_aql';

    public function tpAuditoriaAQL()
    {
        return $this->hasMany(TpAuditoriaAQL::class, 'auditoria_aql_id');
    }

}
