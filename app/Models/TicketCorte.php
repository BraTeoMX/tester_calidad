<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCorte extends Model
{
    use HasFactory;

    // 👇 Esta es la clave para que se conecte a la segunda BD
    protected $connection = 'avances';

    protected $table = 'ticket_corte';

    // Agrega aquí los campos que vas a insertar/updatear si los necesitas
    protected $fillable = [];
}
