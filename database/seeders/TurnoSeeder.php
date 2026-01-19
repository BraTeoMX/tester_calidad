<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Turno;

class TurnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Turno 1: Lunes a Jueves 8:00 - 19:00, Viernes 8:00 - 14:00
        Turno::firstOrCreate(
            ['nombre' => 'Turno 1 (Estandar)'],
            [
                'horario_semanal' => [
                    1 => ['inicio' => '08:00', 'fin' => '19:00'], // Lunes
                    2 => ['inicio' => '08:00', 'fin' => '19:00'], // Martes
                    3 => ['inicio' => '08:00', 'fin' => '19:00'], // Miércoles
                    4 => ['inicio' => '08:00', 'fin' => '19:00'], // Jueves
                    5 => ['inicio' => '08:00', 'fin' => '14:00'], // Viernes
                    // Sábado (6) y Domingo (0) no definidos implican Tiempo Extra todo el día
                ]
            ]
        );

        // Turno 2: Lunes a Viernes 7:30 - 17:45
        Turno::firstOrCreate(
            ['nombre' => 'Turno 2 (7:30 - 17:45)'],
            [
                'horario_semanal' => [
                    1 => ['inicio' => '07:30', 'fin' => '17:45'],
                    2 => ['inicio' => '07:30', 'fin' => '17:45'],
                    3 => ['inicio' => '07:30', 'fin' => '17:45'],
                    4 => ['inicio' => '07:30', 'fin' => '17:45'],
                    5 => ['inicio' => '07:30', 'fin' => '17:45'],
                ]
            ]
        );
    }
}
