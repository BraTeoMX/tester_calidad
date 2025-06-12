<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditoriaAQL extends Model
{
    use HasFactory;
    protected $table = 'auditoria_aql';

    protected $fillable = [
        'fin_paro_modular',
        'minutos_paro_modular',
    ];

    public function tpAuditoriaAQL()
    {
        return $this->hasMany(TpAuditoriaAQL::class, 'auditoria_aql_id');
    }

    public function responsableParo()
{
    return $this->hasOne(ResponsableParo::class, 'auditoria_aql_id');
}

}
