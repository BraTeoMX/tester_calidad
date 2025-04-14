<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuditoriaAQL;
use Carbon\Carbon; // Asegúrate de importar la clase Carbon
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class BultosNoFinalizadosController extends Controller
{

    public function index(Request $request) 
    {
        $pageSlug ='';
        $fechaActual = Carbon::now()->toDateString();
        $auditorDato = Auth::user()->name;
        $auditorPlanta = Auth::user()->Planta;
        $tipoUsuario = Auth::user()->puesto;
        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        if($auditorPlanta == "Planta1"){
            $datoPlanta = "1";
        }else{
            $datoPlanta = "2";
        }

        return view('bultosNoFinalizados.index', compact('mesesEnEspanol', 'pageSlug'));
    }

    public function bultosNoFinalizadosGeneral()
    {
        $fechaInicio = Carbon::now()->subDays(7)->startOfDay();
        $fechaFin = Carbon::now()->endOfDay();

        $bultos = AuditoriaAQL::whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->whereNotNull('inicio_paro')
            ->whereNull('fin_paro')
            ->get();

        return response()->json($bultos);
    }

    public function finalizarParoAQLgeneral(Request $request)
    {
        try {
            $registro = AuditoriaAQL::findOrFail($request->id);
            $registro->fin_paro = Carbon::now(); // Almacenamos la fecha actual como "fin_paro"

            // Usamos created_at como punto de inicio del cálculo
            $inicio = Carbon::parse($registro->created_at);
            $fin = Carbon::now();

            // Calcular minutos considerando horarios laborales
            $minutosParo = $this->calcularMinutosParoDesdeCreatedAt($inicio, $fin);

            $registro->minutos_paro = $minutosParo;
            $registro->reparacion_rechazo = $request->piezasReparadas;
            $registro->save();

            return response()->json([
                'success' => true,
                'message' => 'Paro finalizado y piezas reparadas almacenadas correctamente.',
                'minutos_paro' => $registro->minutos_paro,
                'reparacion_rechazo' => $registro->reparacion_rechazo
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al finalizar el paro: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calcula los minutos de paro basándose en created_at hasta el momento actual.
     * Respeta los horarios laborales establecidos:
     * - Lunes a jueves: 08:00 - 19:00
     * - Viernes: 08:00 - 14:00
     * - Fines de semana: no se cuentan.
     */
    private function calcularMinutosParoDesdeCreatedAt(Carbon $inicio, Carbon $fin)
    {
        $totalMinutos = 0;
        $actual = $inicio->copy();

        while ($actual->lessThan($fin)) {
            // Saltar fines de semana
            if ($actual->isWeekend()) {
                $actual->addDay()->startOfDay();
                continue;
            }

            // Determinar horarios del día actual
            $inicioJornada = $actual->copy()->setTime(8, 0, 0);
            if ($actual->dayOfWeek == Carbon::FRIDAY) {
                $finJornada = $actual->copy()->setTime(14, 0, 0);
            } else {
                $finJornada = $actual->copy()->setTime(19, 0, 0);
            }

            // Calcular minutos dentro del horario laboral
            if ($actual->lessThanOrEqualTo($finJornada) && $fin->greaterThanOrEqualTo($inicioJornada)) {
                $inicioEfectivo = $actual->greaterThan($inicioJornada) ? $actual : $inicioJornada;
                $finEfectivo = $fin->lessThan($finJornada) ? $fin : $finJornada;

                if ($inicioEfectivo->lessThan($finEfectivo)) {
                    $minutosHoy = $inicioEfectivo->diffInMinutes($finEfectivo);
                    $totalMinutos += max($minutosHoy, 0);
                }
            }

            // Avanzar al siguiente día
            $actual->addDay()->startOfDay();
        }

        return $totalMinutos;
    }
    
}
