<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\AseguramientoCalidad;
use App\Models\TpAseguramientoCalidad;
use App\Models\TpAuditoriaAQL;
use App\Models\AuditoriaAQL;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod; // Asegúrate de importar la clase Carbon
use Illuminate\Support\Facades\DB; // Importa la clase DB
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;


class DashboardPorSemanaV2Controller extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */

    public function dashboardSemanaPlanta1V2()
    {
        $title = "";

        $plantaConsulta = "Intimark1";

        return view('dashboard.dashboardPlanta1PorSemanaV2', compact('title' ));
    }

    public function dashboardSemanaPlanta2V2()
    {
        $title = "";

        $plantaConsulta = "Intimark2";

        return view('dashboard.dashboardPlanta2PorSemanaV2', compact('title' ));
    }

    public function buscarAQL(Request $request)
    {
        if (!$request->has('fecha_inicio')) {
            return response()->json(['error' => 'No se recibió una fecha'], 400);
        }

        $fechaRaw = $request->input('fecha_inicio');
        $fechaInicio = Carbon::parse($fechaRaw)->startOfWeek()->setTime(0, 0, 0);
        $fechaFin = Carbon::parse($fechaRaw)->endOfWeek()->setTime(23, 59, 59);

        $plantaConsulta = "Intimark1";
        $cacheKey = "semana_aql_{$plantaConsulta}_{$fechaInicio->format('Y-m-d')}";

        Log::info("🔎 Iniciando consulta AQL para semana: {$fechaInicio->toDateTimeString()} - {$fechaFin->toDateTimeString()}");

        $inicio = microtime(true);
        $datosModuloEstiloAQL = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fechaInicio, $fechaFin, $plantaConsulta) {
            return $this->getDatosModuloClienteAQL($fechaInicio, $plantaConsulta, null, $fechaFin);
        });
        Log::info("⏳ Tiempo ejecución AQL: " . round(microtime(true) - $inicio, 3) . "s");

        return response()->json([
            'datosModuloEstiloAQL' => count($datosModuloEstiloAQL) > 0 ? $datosModuloEstiloAQL : []
        ]);
    }

    public function buscarAQLTE(Request $request)
    {
        if (!$request->has('fecha_inicio')) {
            return response()->json(['error' => 'No se recibió una fecha'], 400);
        }

        $fechaRaw = $request->input('fecha_inicio');
        $fechaInicio = Carbon::parse($fechaRaw)->startOfWeek()->setTime(0, 0, 0);
        $fechaFin = Carbon::parse($fechaRaw)->endOfWeek()->setTime(23, 59, 59);

        $plantaConsulta = "Intimark1";
        $cacheKey = "semana_aqlte_{$plantaConsulta}_{$fechaInicio->format('Y-m-d')}";

        Log::info("🔎 Iniciando consulta AQL TE para semana: {$fechaInicio->toDateTimeString()} - {$fechaFin->toDateTimeString()}");

        $inicio = microtime(true);
        $datosModuloEstiloAQLTE = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fechaInicio, $fechaFin, $plantaConsulta) {
            return $this->getDatosModuloClienteAQL($fechaInicio, $plantaConsulta, 1, $fechaFin);
        });
        Log::info("⏳ Tiempo ejecución AQL TE: " . round(microtime(true) - $inicio, 3) . "s");

        return response()->json([
            'datosModuloEstiloAQLTE' => count($datosModuloEstiloAQLTE) > 0 ? $datosModuloEstiloAQLTE : []
        ]);
    }

    public function buscarProceso(Request $request)
    {
        if (!$request->has('fecha_inicio')) {
            return response()->json(['error' => 'No se recibió una fecha'], 400);
        }

        $fechaRaw = $request->input('fecha_inicio');
        $fechaInicio = Carbon::parse($fechaRaw)->startOfWeek()->setTime(0, 0, 0);
        $fechaFin = Carbon::parse($fechaRaw)->endOfWeek()->setTime(23, 59, 59);

        $plantaConsulta = "Intimark1";
        $cacheKey = "semana_proceso_{$plantaConsulta}_{$fechaInicio->format('Y-m-d')}";

        Log::info("🔎 Iniciando consulta Proceso para semana: {$fechaInicio->toDateTimeString()} - {$fechaFin->toDateTimeString()}");

        $inicio = microtime(true);
        $datosModuloEstiloProceso = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fechaInicio, $fechaFin, $plantaConsulta) {
            return $this->getDatosModuloClienteProceso($fechaInicio, $plantaConsulta, null, $fechaFin);
        });
        Log::info("⏳ Tiempo ejecución Proceso: " . round(microtime(true) - $inicio, 3) . "s");

        return response()->json([
            'datosModuloEstiloProceso' => count($datosModuloEstiloProceso) > 0 ? $datosModuloEstiloProceso : []
        ]);
    }

    public function buscarProcesoTE(Request $request)
    {
        if (!$request->has('fecha_inicio')) {
            return response()->json(['error' => 'No se recibió una fecha'], 400);
        }

        $fechaRaw = $request->input('fecha_inicio');
        $fechaInicio = Carbon::parse($fechaRaw)->startOfWeek()->setTime(0, 0, 0);
        $fechaFin = Carbon::parse($fechaRaw)->endOfWeek()->setTime(23, 59, 59);

        $plantaConsulta = "Intimark1";
        $cacheKey = "semana_proceso_te_{$plantaConsulta}_{$fechaInicio->format('Y-m-d')}";

        Log::info("🔎 Iniciando consulta Proceso TE para semana: {$fechaInicio->toDateTimeString()} - {$fechaFin->toDateTimeString()}");

        $inicio = microtime(true);
        $datosModuloEstiloProcesoTE = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fechaInicio, $fechaFin, $plantaConsulta) {
            return $this->getDatosModuloClienteProceso($fechaInicio, $plantaConsulta, 1, $fechaFin);
        });
        Log::info("⏳ Tiempo ejecución Proceso TE: " . round(microtime(true) - $inicio, 3) . "s");

        return response()->json([
            'datosModuloEstiloProcesoTE' => count($datosModuloEstiloProcesoTE) > 0 ? $datosModuloEstiloProcesoTE : []
        ]);
    }

    public function buscarAQLP2(Request $request)
    {
        if (!$request->has('fecha_inicio')) {
            return response()->json(['error' => 'No se recibió una fecha'], 400);
        }

        $fechaRaw = $request->input('fecha_inicio');
        $fechaInicio = Carbon::parse($fechaRaw)->startOfWeek()->setTime(0, 0, 0);
        $fechaFin = Carbon::parse($fechaRaw)->endOfWeek()->setTime(23, 59, 59);

        $plantaConsulta = "Intimark2";
        $cacheKey = "semana_aql_{$plantaConsulta}_{$fechaInicio->format('Y-m-d')}";

        Log::info("🔎 P2 Iniciando consulta AQL para semana: {$fechaInicio->toDateTimeString()} - {$fechaFin->toDateTimeString()}");

        $inicio = microtime(true);
        $datosModuloEstiloAQL = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fechaInicio, $fechaFin, $plantaConsulta) {
            return $this->getDatosModuloClienteAQL($fechaInicio, $plantaConsulta, null, $fechaFin);
        });
        Log::info("⏳ P2 Tiempo ejecución AQL: " . round(microtime(true) - $inicio, 3) . "s");

        return response()->json([
            'datosModuloEstiloAQL' => count($datosModuloEstiloAQL) > 0 ? $datosModuloEstiloAQL : []
        ]);
    }

    public function buscarAQLTEP2(Request $request)
    {
        if (!$request->has('fecha_inicio')) {
            return response()->json(['error' => 'No se recibió una fecha'], 400);
        }

        $fechaRaw = $request->input('fecha_inicio');
        $fechaInicio = Carbon::parse($fechaRaw)->startOfWeek()->setTime(0, 0, 0);
        $fechaFin = Carbon::parse($fechaRaw)->endOfWeek()->setTime(23, 59, 59);

        $plantaConsulta = "Intimark2";
        $cacheKey = "semana_aqlte_{$plantaConsulta}_{$fechaInicio->format('Y-m-d')}";

        Log::info("🔎 P2 Iniciando consulta AQL TE para semana: {$fechaInicio->toDateTimeString()} - {$fechaFin->toDateTimeString()}");

        $inicio = microtime(true);
        $datosModuloEstiloAQLTE = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fechaInicio, $fechaFin, $plantaConsulta) {
            return $this->getDatosModuloClienteAQL($fechaInicio, $plantaConsulta, 1, $fechaFin);
        });
        Log::info("⏳P2 Tiempo ejecución AQL TE: " . round(microtime(true) - $inicio, 3) . "s");

        return response()->json([
            'datosModuloEstiloAQLTE' => count($datosModuloEstiloAQLTE) > 0 ? $datosModuloEstiloAQLTE : []
        ]);
    }

    public function buscarProcesoP2(Request $request)
    {
        if (!$request->has('fecha_inicio')) {
            return response()->json(['error' => 'No se recibió una fecha'], 400);
        }

        $fechaRaw = $request->input('fecha_inicio');
        $fechaInicio = Carbon::parse($fechaRaw)->startOfWeek()->setTime(0, 0, 0);
        $fechaFin = Carbon::parse($fechaRaw)->endOfWeek()->setTime(23, 59, 59);

        $plantaConsulta = "Intimark2";
        $cacheKey = "semana_proceso_{$plantaConsulta}_{$fechaInicio->format('Y-m-d')}";

        Log::info("🔎 P2 Iniciando consulta Proceso para semana: {$fechaInicio->toDateTimeString()} - {$fechaFin->toDateTimeString()}");

        $inicio = microtime(true);
        $datosModuloEstiloProceso = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fechaInicio, $fechaFin, $plantaConsulta) {
            return $this->getDatosModuloClienteProceso($fechaInicio, $plantaConsulta, null, $fechaFin);
        });
        Log::info("⏳ P2 Tiempo ejecución Proceso: " . round(microtime(true) - $inicio, 3) . "s");

        return response()->json([
            'datosModuloEstiloProceso' => count($datosModuloEstiloProceso) > 0 ? $datosModuloEstiloProceso : []
        ]);
    }

    public function buscarProcesoTEP2(Request $request)
    {
        if (!$request->has('fecha_inicio')) {
            return response()->json(['error' => 'No se recibió una fecha'], 400);
        }

        $fechaRaw = $request->input('fecha_inicio');
        $fechaInicio = Carbon::parse($fechaRaw)->startOfWeek()->setTime(0, 0, 0);
        $fechaFin = Carbon::parse($fechaRaw)->endOfWeek()->setTime(23, 59, 59);

        $plantaConsulta = "Intimark2";
        $cacheKey = "semana_proceso_te_{$plantaConsulta}_{$fechaInicio->format('Y-m-d')}";

        Log::info("🔎 P2 Iniciando consulta Proceso TE para semana: {$fechaInicio->toDateTimeString()} - {$fechaFin->toDateTimeString()}");

        $inicio = microtime(true);
        $datosModuloEstiloProcesoTE = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fechaInicio, $fechaFin, $plantaConsulta) {
            return $this->getDatosModuloClienteProceso($fechaInicio, $plantaConsulta, 1, $fechaFin);
        });
        Log::info("⏳ P2 Tiempo ejecución Proceso TE: " . round(microtime(true) - $inicio, 3) . "s");

        return response()->json([
            'datosModuloEstiloProcesoTE' => count($datosModuloEstiloProcesoTE) > 0 ? $datosModuloEstiloProcesoTE : []
        ]);
    }

    private function getDatosModuloClienteAQL($fechaInicio, $plantaConsulta, $tiempoExtra = null, $fechaFin = null)
    {
        $registros = AuditoriaAQL::with('tpAuditoriaAQL')
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('planta', $plantaConsulta)
            ->when(
                is_null($tiempoExtra),
                fn($q) => $q->whereNull('tiempo_extra'),
                fn($q) => $q->where('tiempo_extra', $tiempoExtra)
            )
            ->get();

        $agrupados = $registros->groupBy(['modulo', 'cliente']);
        $dataModuloEstiloAQL = [];

        foreach ($agrupados as $modulo => $porCliente) {
            foreach ($porCliente as $cliente => $items) {

                $auditoresUnicos = $items->pluck('auditor')->filter()->unique()->implode(', ');
                $supervisoresUnicos = $items->pluck('team_leader')->filter()->unique()->implode(', ');
                $modulosUnicos = $items->pluck('modulo')->unique()->count();

                $sumaAuditadaAQL = $items->sum('cantidad_auditada');
                $sumaRechazadaAQL = $items->sum('cantidad_rechazada');
                $porcentajeErrorAQL = $sumaAuditadaAQL ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100 : 0;

                $conteoOperario = $items->pluck('nombre')
                    ->filter()
                    ->flatMap(fn($n) => explode(',', $n))
                    ->map(fn($n) => trim($n))
                    ->filter()
                    ->count();

                $conteoMinutos = $items->pluck('minutos_paro')->filter()->count();
                $sumaMinutos = $items->sum('minutos_paro');
                $promedioMinutosEntero = $conteoMinutos ? ceil($sumaMinutos / $conteoMinutos) : 0;

                $estilosUnicos = $items->pluck('cliente')->unique()->implode(', ');

                $defectosUnicos = $items->pluck('tpAuditoriaAQL')
                    ->flatten()
                    ->pluck('tp')
                    ->filter(fn($tp) => $tp !== 'NINGUNO')
                    ->countBy()
                    ->map(fn($count, $tp) => $count > 1 ? "$tp ($count)" : $tp)
                    ->sort()
                    ->values()
                    ->implode(', ') ?: 'N/A';

                $accionesCorrectivasUnicos = $items->pluck('ac')->filter()->unique()->implode(', ') ?: 'N/A';

                $operariosUnicos = $items->pluck('nombre')
                    ->flatMap(fn($n) => explode(',', $n))
                    ->map(fn($n) => trim($n))
                    ->filter()
                    ->countBy()
                    ->map(fn($count, $name) => $count > 1 ? "$name ($count)" : $name)
                    ->sort()
                    ->values()
                    ->implode(', ') ?: 'N/A';

                $sumaParoModular = $items->sum('minutos_paro_modular') ?: 'N/A';
                $conteParoModular = $items->pluck('minutos_paro_modular')->filter()->count();

                $sumaPiezasBulto = $items->sum('pieza');
                $cantidadBultosEncontrados = $items->count();
                $cantidadBultosRechazados = $items->filter(fn($i) => $i->cantidad_rechazada > 0)->count();
                $sumaReparacionRechazo = $items->sum('reparacion_rechazo');

                $piezasRechazadasUnicas = $items
                    ->filter(fn($i) => $i->cantidad_rechazada > 0)
                    ->sum('pieza');
                $piezasRechazadasUnicas = $piezasRechazadasUnicas > 0 ? $piezasRechazadasUnicas : 'N/A';

                $dataModuloEstiloAQL[] = [
                    'modulo' => $modulo,
                    'cliente' => $cliente,
                    'auditoresUnicos' => $auditoresUnicos,
                    'supervisoresUnicos' => $supervisoresUnicos,
                    'modulosUnicos' => $modulosUnicos,
                    'sumaAuditadaAQL' => $sumaAuditadaAQL,
                    'sumaRechazadaAQL' => $sumaRechazadaAQL,
                    'porcentajeErrorAQL' => $porcentajeErrorAQL,
                    'conteoOperario' => $conteoOperario,
                    'conteoMinutos' => $conteoMinutos,
                    'sumaMinutos' => $sumaMinutos,
                    'promedioMinutosEntero' => $promedioMinutosEntero,
                    'estilosUnicos' => $estilosUnicos,
                    'defectosUnicos' => $defectosUnicos,
                    'accionesCorrectivasUnicos' => $accionesCorrectivasUnicos,
                    'operariosUnicos' => $operariosUnicos,
                    'sumaParoModular' => $sumaParoModular,
                    'conteParoModular' => $conteParoModular,
                    'sumaPiezasBulto' => $sumaPiezasBulto,
                    'cantidadBultosEncontrados' => $cantidadBultosEncontrados,
                    'cantidadBultosRechazados' => $cantidadBultosRechazados,
                    'sumaReparacionRechazo' => $sumaReparacionRechazo,
                    'piezasRechazadasUnicas' => $piezasRechazadasUnicas,
                ];
            }
        }

        return $dataModuloEstiloAQL;
    }

    private function getDatosModuloClienteProceso($fechaInicio, $plantaConsulta, $tiempoExtra = null, $fechaFin = null)
    {
        $registros = AseguramientoCalidad::with('TpAseguramientoCalidad')
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('planta', $plantaConsulta)
            ->when(
                is_null($tiempoExtra),
                fn($q) => $q->whereNull('tiempo_extra'),
                fn($q) => $q->where('tiempo_extra', $tiempoExtra)
            )
            ->get();

        $agrupados = $registros->groupBy(['modulo', 'cliente']);
        $dataModuloEstiloProceso = [];

        foreach ($agrupados as $modulo => $porCliente) {
            foreach ($porCliente as $cliente => $items) {

                $auditoresUnicos = $items->pluck('auditor')->filter()->unique()->implode(', ');
                $supervisoresUnicos = $items->pluck('team_leader')->filter()->unique()->implode(', ');

                $cantidadRecorridos = $items->pluck('nombre')
                    ->filter()
                    ->countBy()
                    ->max() ?: 0;

                $sumaAuditadaProceso = $items->sum('cantidad_auditada');
                $sumaRechazadaProceso = $items->sum('cantidad_rechazada');
                $porcentajeErrorProceso = $sumaAuditadaProceso ? ($sumaRechazadaProceso / $sumaAuditadaProceso) * 100 : 0;

                $conteoOperario = $items
                    ->filter(fn($i) => is_null($i->utility))
                    ->pluck('nombre')
                    ->filter()
                    ->unique()
                    ->count();

                $conteoUtility = $items
                    ->filter(fn($i) => $i->utility == 1)
                    ->pluck('nombre')
                    ->filter()
                    ->unique()
                    ->count();

                $conteoMinutos = $items->pluck('minutos_paro')->filter()->count();
                $sumaMinutos = $items->sum('minutos_paro');
                $promedioMinutosEntero = $conteoMinutos ? ceil($sumaMinutos / $conteoMinutos) : 0;

                $operariosUnicos = $items
                    ->filter(fn($i) => $i->cantidad_rechazada > 0)
                    ->pluck('nombre')
                    ->filter()
                    ->countBy()
                    ->map(fn($count, $name) => $count > 1 ? "$name ($count)" : $name)
                    ->sort()
                    ->values()
                    ->implode(', ') ?: 'N/A';

                $sumaParoModular = $items->sum('minutos_paro_modular') ?: 'N/A';
                $conteParoModular = $items->pluck('minutos_paro_modular')->filter()->count();

                $defectosUnicos = $items->pluck('TpAseguramientoCalidad')
                    ->flatten()
                    ->pluck('tp')
                    ->filter(fn($tp) => $tp !== 'NINGUNO')
                    ->countBy()
                    ->map(fn($count, $tp) => $count > 1 ? "$tp ($count)" : $tp)
                    ->sort()
                    ->values()
                    ->implode(', ') ?: 'N/A';

                $accionesCorrectivasUnicos = $items->pluck('ac')->filter()->unique()->implode(', ') ?: 'N/A';

                $dataModuloEstiloProceso[] = [
                    'modulo' => $modulo,
                    'cliente' => $cliente,
                    'auditoresUnicos' => $auditoresUnicos,
                    'supervisoresUnicos' => $supervisoresUnicos,
                    'cantidadRecorridos' => $cantidadRecorridos,
                    'sumaAuditadaProceso' => $sumaAuditadaProceso,
                    'sumaRechazadaProceso' => $sumaRechazadaProceso,
                    'porcentajeErrorProceso' => $porcentajeErrorProceso,
                    'conteoOperario' => $conteoOperario,
                    'conteoUtility' => $conteoUtility,
                    'conteoMinutos' => $conteoMinutos,
                    'sumaMinutos' => $sumaMinutos,
                    'promedioMinutosEntero' => $promedioMinutosEntero,
                    'operariosUnicos' => $operariosUnicos,
                    'sumaParoModular' => $sumaParoModular,
                    'conteParoModular' => $conteParoModular,
                    'defectosUnicos' => $defectosUnicos,
                    'accionesCorrectivasUnicos' => $accionesCorrectivasUnicos,
                ];
            }
        }

        return $dataModuloEstiloProceso;
    }

    private function getDatosModuloClienteAQLDetalles($fechaInicio, $fechaFin, $planta, $modulo, $cliente, $tiempoExtra = null)
    {
        $cacheKey = "semana_aql_detalles_{$planta}_{$modulo}_{$cliente}_{$fechaInicio->format('Ymd')}_{$fechaFin->format('Ymd')}_" . ($tiempoExtra ? 'te' : 'tn');

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fechaInicio, $fechaFin, $planta, $modulo, $cliente, $tiempoExtra) {
            $query = AuditoriaAQL::where('modulo', $modulo)
                ->where('cliente', $cliente)
                ->where('planta', $planta)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->with('tpAuditoriaAQL');

            if (is_null($tiempoExtra)) {
                $query->whereNull('tiempo_extra');
            } else {
                $query->where('tiempo_extra', $tiempoExtra);
            }

            return $query->get()->map(function ($registro) {
                return [
                    'minutos_paro' => $registro->minutos_paro,
                    'cliente' => $registro->cliente,
                    'bulto' => $registro->bulto,
                    'pieza' => $registro->pieza,
                    'talla' => $registro->talla,
                    'color' => $registro->color,
                    'estilo' => $registro->estilo,
                    'cantidad_auditada' => $registro->cantidad_auditada,
                    'cantidad_rechazada' => $registro->cantidad_rechazada,
                    'defectos' => $registro->tpAuditoriaAQL->pluck('tp')->filter()->implode(', ') ?: 'N/A',
                    'accion_correctiva' => $registro->ac ?? 'N/A',
                    'hora' => $registro->created_at ? Carbon::parse($registro->created_at)->format('d/m/Y - H:i:s'): 'N/A',
                ];
            });
        });
    }


    public function obtenerDetallesAQLP2(Request $request)
    {
        Log::info('datos de request: ' . json_encode($request->all()));
        $modulo = $request->input('modulo');
        $cliente = $request->input('cliente');
        $fechaSemana = $request->input('fecha');
        $tiempoExtra = $request->input('tiempo_extra');

        $tiempoExtra = ($tiempoExtra === 'null' || $tiempoExtra === '') ? null : $tiempoExtra;
        $planta = "Intimark2";

        if (!$modulo || !$cliente || !$fechaSemana) {
            Log::warning('Faltan parámetros necesarios en obtenerDetallesAQLP2');
            return response()->json(['error' => 'Faltan parámetros necesarios'], 400);
        }

        // Convertir semana a rango
        $fechaInicio = Carbon::parse($fechaSemana)->startOfWeek()->setTime(0, 0, 0);
        $fechaFin = Carbon::parse($fechaSemana)->endOfWeek()->setTime(23, 59, 59);
        Log::info('fechaInicio: ' . $fechaInicio);
        Log::info('fechaFin: ' . $fechaFin);

        $detalles = $this->getDatosModuloClienteAQLDetalles($fechaInicio, $fechaFin, $planta, $modulo, $cliente, $tiempoExtra);

        return response()->json($detalles);
    }

    public function obtenerDetallesAQLP1(Request $request)
    {
        Log::info('datos de request: ' . json_encode($request->all()));
        $modulo = $request->input('modulo');
        $cliente = $request->input('cliente');
        $fechaSemana = $request->input('fecha');
        $tiempoExtra = $request->input('tiempo_extra');

        $tiempoExtra = ($tiempoExtra === 'null' || $tiempoExtra === '') ? null : $tiempoExtra;
        $planta = "Intimark1";

        if (!$modulo || !$cliente || !$fechaSemana) {
            return response()->json(['error' => 'Faltan parámetros necesarios'], 400);
        }

        // Convertir semana a rango
        $fechaInicio = Carbon::parse($fechaSemana)->startOfWeek()->setTime(0, 0, 0);
        $fechaFin = Carbon::parse($fechaSemana)->endOfWeek()->setTime(23, 59, 59);
        Log::info('fechaInicio: ' . $fechaInicio);
        Log::info('fechaFin: ' . $fechaFin);
        
        $detalles = $this->getDatosModuloClienteAQLDetalles($fechaInicio, $fechaFin, $planta, $modulo, $cliente, $tiempoExtra);

        return response()->json($detalles);
    }

    private function getDatosModuloClienteProcesoDetalles($fechaInicio, $fechaFin, $planta, $modulo, $cliente, $tiempoExtra = null)
    {
        $cacheKey = "semana_proceso_detalles_{$planta}_{$modulo}_{$cliente}_{$fechaInicio->format('Ymd')}_{$fechaFin->format('Ymd')}_" . ($tiempoExtra ? 'te' : 'tn');

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fechaInicio, $fechaFin, $modulo, $cliente, $planta, $tiempoExtra) {
            $query = AseguramientoCalidad::where('modulo', $modulo)
                ->where('cliente', $cliente)
                ->where('planta', $planta)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->with('tpAseguramientoCalidad');

        if (is_null($tiempoExtra)) {
            $query->whereNull('tiempo_extra');
        } else {
            $query->where('tiempo_extra', $tiempoExtra);
        }

        return $query->get()->map(function ($registro) {
            return [
                'minutos_paro' => $registro->minutos_paro,
                'estilo' => $registro->estilo,
                'nombre' => $registro->nombre,
                'operacion' => $registro->operacion,
                'cantidad_auditada' => $registro->cantidad_auditada,
                'cantidad_rechazada' => $registro->cantidad_rechazada,
                'tipo_problema' => $registro->tpAseguramientoCalidad->pluck('tp')->filter()->implode(', ') ?: 'N/A',
                'ac' => $registro->ac ?? 'N/A',
                'pxp' => $registro->pxp ?? 'N/A',
                'hora' => $registro->created_at ? Carbon::parse($registro->created_at)->format('d/m/Y - H:i:s'): 'N/A',
            ];
        });
    });
}

    public function obtenerDetallesProcesoP2(Request $request)
    {
        Log::info('datos de request: ' . json_encode($request->all()));
        $modulo = $request->input('modulo');
        $cliente = $request->input('cliente');
        $fechaSemana = $request->input('fecha');
        $tiempoExtra = $request->input('tiempo_extra');

        $tiempoExtra = ($tiempoExtra === 'null' || $tiempoExtra === '') ? null : $tiempoExtra;
        $planta = "Intimark2";

        if (!$modulo || !$cliente || !$fechaSemana) {
            Log::warning('Faltan parámetros en obtenerDetallesProcesoP2');
            return response()->json(['error' => 'Faltan parámetros necesarios'], 400);
        }

        $fechaInicio = Carbon::parse($fechaSemana)->startOfWeek()->setTime(0, 0, 0);
        $fechaFin = Carbon::parse($fechaSemana)->endOfWeek()->setTime(23, 59, 59);
        Log::info('fechaInicio: ' . $fechaInicio);
        Log::info('fechaFin: ' . $fechaFin);

        $detalles = $this->getDatosModuloClienteProcesoDetalles($fechaInicio, $fechaFin, $planta, $modulo, $cliente, $tiempoExtra);

        return response()->json($detalles);
    }

    public function obtenerDetallesProcesoP1(Request $request)
    {
        Log::info('datos de request: ' . json_encode($request->all()));
        $modulo = $request->input('modulo');
        $cliente = $request->input('cliente');
        $fechaSemana = $request->input('fecha');
        $tiempoExtra = $request->input('tiempo_extra');

        $tiempoExtra = ($tiempoExtra === 'null' || $tiempoExtra === '') ? null : $tiempoExtra;
        $planta = "Intimark1";

        if (!$modulo || !$cliente || !$fechaSemana) {
            Log::warning('Faltan parámetros en obtenerDetallesProcesoP1');
            return response()->json(['error' => 'Faltan parámetros necesarios'], 400);
        }

        $fechaInicio = Carbon::parse($fechaSemana)->startOfWeek()->setTime(0, 0, 0);
        $fechaFin = Carbon::parse($fechaSemana)->endOfWeek()->setTime(23, 59, 59);
        Log::info('fechaInicio: ' . $fechaInicio);
        Log::info('fechaFin: ' . $fechaFin);

        $detalles = $this->getDatosModuloClienteProcesoDetalles($fechaInicio, $fechaFin, $planta, $modulo, $cliente, $tiempoExtra);

        return response()->json($detalles);
    }


}
