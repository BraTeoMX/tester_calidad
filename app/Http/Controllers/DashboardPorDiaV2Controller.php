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


class DashboardPorDiaV2Controller extends Controller
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

    public function dashboardPlanta1V2()
    {
        $title = "";

        $plantaConsulta = "Intimark1";

        return view('dashboar.dashboardPlanta1PorDiaV2', compact('title'));
    }

    public function dashboardPlanta2V2()
    {
        $title = "";

        $plantaConsulta = "Intimark2";

        return view('dashboar.dashboardPlanta2PorDiaV2', compact('title'));
    }

    public function buscarAQL(Request $request)
    {
        if (!$request->has('fecha_inicio')) {
            return response()->json(['error' => 'No se recibió una fecha'], 400);
        }

        $fechaActual = Carbon::parse($request->input('fecha_inicio'))->format('Y-m-d');
        $plantaConsulta = "Intimark1";
        $cacheKey = "aql_{$plantaConsulta}_{$fechaActual}";

        //Log::info("🔎 Iniciando consulta AQL para fecha: {$fechaActual}");

        $inicio = microtime(true);
        $datosModuloEstiloAQL = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($fechaActual, $plantaConsulta) {
            return $this->getDatosModuloEstiloAQL($fechaActual, $plantaConsulta, null);
        });
        //Log::info("⏳ Tiempo ejecución AQL: " . round(microtime(true) - $inicio, 3) . "s");

        return response()->json([
            'datosModuloEstiloAQL' => count($datosModuloEstiloAQL) > 0 ? $datosModuloEstiloAQL : []
        ]);
    }

    public function buscarAQLTE(Request $request)
    {
        if (!$request->has('fecha_inicio')) {
            return response()->json(['error' => 'No se recibió una fecha'], 400);
        }

        $fechaActual = Carbon::parse($request->input('fecha_inicio'))->format('Y-m-d');
        $plantaConsulta = "Intimark1";
        $cacheKey = "aqlte_{$plantaConsulta}_{$fechaActual}";

        //Log::info("🔎 Iniciando consulta AQL TE para fecha: {$fechaActual}");

        $inicio = microtime(true);
        $datosModuloEstiloAQLTE = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($fechaActual, $plantaConsulta) {
            return $this->getDatosModuloEstiloAQL($fechaActual, $plantaConsulta, 1);
        });
        //Log::info("⏳ Tiempo ejecución AQL TE: " . round(microtime(true) - $inicio, 3) . "s");

        return response()->json([
            'datosModuloEstiloAQLTE' => count($datosModuloEstiloAQLTE) > 0 ? $datosModuloEstiloAQLTE : []
        ]);
    }

    public function buscarProceso(Request $request)
    {
        if (!$request->has('fecha_inicio')) {
            return response()->json(['error' => 'No se recibió una fecha'], 400);
        }

        $fechaActual = Carbon::parse($request->input('fecha_inicio'))->format('Y-m-d');
        $plantaConsulta = "Intimark1";
        $cacheKey = "proceso_{$plantaConsulta}_{$fechaActual}";

        //Log::info("🔎 Iniciando consulta Proceso para fecha: {$fechaActual}");

        $inicio = microtime(true);
        $datosModuloEstiloProceso = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($fechaActual, $plantaConsulta) {
            return $this->getDatosModuloEstiloProceso($fechaActual, $plantaConsulta, null);
        });
        //Log::info("⏳ Tiempo ejecución Proceso: " . round(microtime(true) - $inicio, 3) . "s");

        return response()->json([
            'datosModuloEstiloProceso' => count($datosModuloEstiloProceso) > 0 ? $datosModuloEstiloProceso : []
        ]);
    }

    public function buscarProcesoTE(Request $request)
    {
        if (!$request->has('fecha_inicio')) {
            return response()->json(['error' => 'No se recibió una fecha'], 400);
        }

        $fechaActual = Carbon::parse($request->input('fecha_inicio'))->format('Y-m-d');
        $plantaConsulta = "Intimark1";
        $cacheKey = "proceso_te_{$plantaConsulta}_{$fechaActual}";

        //Log::info("🔎 Iniciando consulta Proceso TE para fecha: {$fechaActual}");

        $inicio = microtime(true);
        $datosModuloEstiloProcesoTE = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($fechaActual, $plantaConsulta) {
            return $this->getDatosModuloEstiloProceso($fechaActual, $plantaConsulta, 1);
        });
        //Log::info("⏳ Tiempo ejecución Proceso TE: " . round(microtime(true) - $inicio, 3) . "s");

        return response()->json([
            'datosModuloEstiloProcesoTE' => count($datosModuloEstiloProcesoTE) > 0 ? $datosModuloEstiloProcesoTE : []
        ]);
    }

    public function buscarAQLP2(Request $request)
    {
        if (!$request->has('fecha_inicio')) {
            return response()->json(['error' => 'No se recibió una fecha'], 400);
        }

        $fechaActual = Carbon::parse($request->input('fecha_inicio'))->format('Y-m-d');
        $plantaConsulta = "Intimark2";
        $cacheKey = "aql_{$plantaConsulta}_{$fechaActual}";

        //Log::info("🔎 P2 Iniciando consulta AQL para fecha: {$fechaActual}");

        $inicio = microtime(true);
        $datosModuloEstiloAQL = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($fechaActual, $plantaConsulta) {
            return $this->getDatosModuloEstiloAQL($fechaActual, $plantaConsulta, null);
        });
        //Log::info("⏳ P2 Tiempo ejecución AQL: " . round(microtime(true) - $inicio, 3) . "s");

        return response()->json([
            'datosModuloEstiloAQL' => count($datosModuloEstiloAQL) > 0 ? $datosModuloEstiloAQL : []
        ]);
    }

    public function buscarAQLTEP2(Request $request)
    {
        if (!$request->has('fecha_inicio')) {
            return response()->json(['error' => 'No se recibió una fecha'], 400);
        }

        $fechaActual = Carbon::parse($request->input('fecha_inicio'))->format('Y-m-d');
        $plantaConsulta = "Intimark2";
        $cacheKey = "aqlte_{$plantaConsulta}_{$fechaActual}";

        //Log::info("🔎 P2 Iniciando consulta AQL TE para fecha: {$fechaActual}");

        $inicio = microtime(true);
        $datosModuloEstiloAQLTE = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($fechaActual, $plantaConsulta) {
            return $this->getDatosModuloEstiloAQL($fechaActual, $plantaConsulta, 1);
        });
        //Log::info("⏳P2 Tiempo ejecución AQL TE: " . round(microtime(true) - $inicio, 3) . "s");

        return response()->json([
            'datosModuloEstiloAQLTE' => count($datosModuloEstiloAQLTE) > 0 ? $datosModuloEstiloAQLTE : []
        ]);
    }

    public function buscarProcesoP2(Request $request)
    {
        if (!$request->has('fecha_inicio')) {
            return response()->json(['error' => 'No se recibió una fecha'], 400);
        }

        $fechaActual = Carbon::parse($request->input('fecha_inicio'))->format('Y-m-d');
        $plantaConsulta = "Intimark2";
        $cacheKey = "proceso_{$plantaConsulta}_{$fechaActual}";

        //Log::info("🔎 P2 Iniciando consulta Proceso para fecha: {$fechaActual}");

        $inicio = microtime(true);
        $datosModuloEstiloProceso = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($fechaActual, $plantaConsulta) {
            return $this->getDatosModuloEstiloProceso($fechaActual, $plantaConsulta, null);
        });
        //Log::info("⏳ P2 Tiempo ejecución Proceso: " . round(microtime(true) - $inicio, 3) . "s");

        return response()->json([
            'datosModuloEstiloProceso' => count($datosModuloEstiloProceso) > 0 ? $datosModuloEstiloProceso : []
        ]);
    }

    public function buscarProcesoTEP2(Request $request)
    {
        if (!$request->has('fecha_inicio')) {
            return response()->json(['error' => 'No se recibió una fecha'], 400);
        }

        $fechaActual = Carbon::parse($request->input('fecha_inicio'))->format('Y-m-d');
        $plantaConsulta = "Intimark2";
        $cacheKey = "proceso_te_{$plantaConsulta}_{$fechaActual}";

        //Log::info("🔎 P2 Iniciando consulta Proceso TE para fecha: {$fechaActual}");

        $inicio = microtime(true);
        $datosModuloEstiloProcesoTE = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($fechaActual, $plantaConsulta) {
            return $this->getDatosModuloEstiloProceso($fechaActual, $plantaConsulta, 1);
        });
        //Log::info("⏳ P2 Tiempo ejecución Proceso TE: " . round(microtime(true) - $inicio, 3) . "s");

        return response()->json([
            'datosModuloEstiloProcesoTE' => count($datosModuloEstiloProcesoTE) > 0 ? $datosModuloEstiloProcesoTE : []
        ]);
    }

    private function getDatosModuloEstiloAQL($fecha, $plantaConsulta, $tiempoExtra = null)
    {
        // 1) Traer de una sola vez todos los registros del día, planta y tiempo extra (si aplica),
        //    incluyendo la relación tpAuditoriaAQL para defectos.
        $registros = AuditoriaAQL::with('tpAuditoriaAQL')
            ->whereDate('created_at', $fecha)
            ->where('planta', $plantaConsulta)
            ->when(
                is_null($tiempoExtra),
                fn($q) => $q->whereNull('tiempo_extra'),
                fn($q) => $q->where('tiempo_extra', $tiempoExtra)
            )
            ->get();

        // 2) Agrupar toda la colección primero por módulo y luego por estilo
        $agrupados = $registros->groupBy(['modulo', 'estilo']);

        $dataModuloEstiloAQL = [];

        // 3) Recorrer cada grupo (módulo → estilo) y calcular todas las métricas en memoria
        foreach ($agrupados as $modulo => $porEstilo) {
            foreach ($porEstilo as $estilo => $items) {
                // Auditores únicos
                $auditoresUnicos = $items->pluck('auditor')
                    ->filter()
                    ->unique()
                    ->implode(', ');

                // Supervisores únicos
                $supervisoresUnicos = $items->pluck('team_leader')
                    ->filter()
                    ->unique()
                    ->implode(', ');

                // Conteo de módulos únicos (siempre 1, pero lo mantenemos por consistencia)
                $modulosUnicos = $items->pluck('modulo')
                    ->unique()
                    ->count();

                $OpUnicos = $items->pluck('op')
                    ->filter()
                    ->unique()
                    ->implode(', ');

                // Sumas y porcentaje de error AQL
                $sumaAuditadaAQL  = $items->sum('cantidad_auditada');
                $sumaRechazadaAQL = $items->sum('cantidad_rechazada');
                $porcentajeErrorAQL = $sumaAuditadaAQL
                    ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100
                    : 0;

                // Conteo total de operarios (dividiendo cadenas separadas por comas)
                $conteoOperario = $items->pluck('nombre')
                    ->flatMap(fn($n) => explode(',', $n))
                    ->map(fn($n) => trim($n))
                    ->filter()
                    ->count();

                // Conteo y suma de minutos de paro, y promedio entero
                $conteoMinutos = $items->pluck('minutos_paro')
                    ->filter()
                    ->count();
                $sumaMinutos = $items->sum('minutos_paro');
                $promedioMinutosEntero = $conteoMinutos
                    ? (int) ceil($sumaMinutos / $conteoMinutos)
                    : 0;

                // Estilos únicos (en este contexto, solo el propio estilo)
                $estilosUnicos = $items->pluck('estilo')
                    ->unique()
                    ->implode(', ');

                // Defectos únicos con conteo
                $defectosUnicos = $items->pluck('tpAuditoriaAQL')
                    ->flatten()
                    ->pluck('tp')
                    ->filter(fn($tp) => $tp !== 'NINGUNO')
                    ->countBy()
                    ->map(fn($count, $tp) => $count > 1 ? "$tp ($count)" : $tp)
                    ->sort()
                    ->values()
                    ->implode(', ') ?: 'N/A';

                // Acciones correctivas únicas
                $accionesCorrectivasUnicos = $items->pluck('ac')
                    ->filter()
                    ->unique()
                    ->implode(', ') ?: 'N/A';

                // Lista de operarios con repeticiones formateadas
                $operariosUnicos = $items->pluck('nombre')
                    ->flatMap(fn($n) => explode(',', $n))
                    ->map(fn($n) => trim($n))
                    ->filter()
                    ->countBy()
                    ->map(fn($count, $name) => $count > 1 ? "$name ($count)" : $name)
                    ->sort()
                    ->values()
                    ->implode(', ') ?: 'N/A';

                // Minutos de paro modular
                $sumaParoModular = $items->sum('minutos_paro_modular') ?: 'N/A';
                $conteParoModular = $items->pluck('minutos_paro_modular')
                    ->filter()
                    ->count();

                // Piezas bulto y conteos de bultos
                $sumaPiezasBulto           = $items->sum('pieza');
                $cantidadBultosEncontrados  = $items->count();
                $cantidadBultosRechazados   = $items
                    ->filter(fn($i) => $i->cantidad_rechazada > 0)
                    ->count();

                // Reparación de rechazo
                $sumaReparacionRechazo = $items->sum('reparacion_rechazo');

                // Piezas rechazadas únicas (sumadas)
                $piezasRechazadasUnicas = $items
                    ->filter(fn($i) => $i->cantidad_rechazada > 0)
                    ->sum('pieza');
                $piezasRechazadasUnicas = $piezasRechazadasUnicas > 0
                    ? $piezasRechazadasUnicas
                    : 'N/A';

                // 4) Armar el array tal cual para devolverlo
                $dataModuloEstiloAQL[] = [
                    'modulo'                   => $modulo,
                    'opUnicos'                 => $OpUnicos,
                    'estilo'                   => $estilo,
                    'auditoresUnicos'          => $auditoresUnicos,
                    'supervisoresUnicos'       => $supervisoresUnicos,
                    'modulosUnicos'            => $modulosUnicos,
                    'sumaAuditadaAQL'          => $sumaAuditadaAQL,
                    'sumaRechazadaAQL'         => $sumaRechazadaAQL,
                    'porcentajeErrorAQL'       => $porcentajeErrorAQL,
                    'conteoOperario'           => $conteoOperario,
                    'conteoMinutos'            => $conteoMinutos,
                    'sumaMinutos'              => $sumaMinutos,
                    'promedioMinutosEntero'    => $promedioMinutosEntero,
                    'estilosUnicos'            => $estilosUnicos,
                    'defectosUnicos'           => $defectosUnicos,
                    'accionesCorrectivasUnicos' => $accionesCorrectivasUnicos,
                    'operariosUnicos'          => $operariosUnicos,
                    'sumaParoModular'          => $sumaParoModular,
                    'conteParoModular'         => $conteParoModular,
                    'sumaPiezasBulto'          => $sumaPiezasBulto,
                    'cantidadBultosEncontrados' => $cantidadBultosEncontrados,
                    'cantidadBultosRechazados' => $cantidadBultosRechazados,
                    'sumaReparacionRechazo'    => $sumaReparacionRechazo,
                    'piezasRechazadasUnicas'   => $piezasRechazadasUnicas,
                ];
            }
        }

        return $dataModuloEstiloAQL;
    }

    private function getDatosModuloEstiloProceso($fecha, $plantaConsulta, $tiempoExtra = null)
    {
        // 1) Traer de una sola vez todos los registros del día, planta y tiempo extra (si aplica),
        //    incluyendo la relación TpAseguramientoCalidad para los defectos.
        $registros = AseguramientoCalidad::with('TpAseguramientoCalidad')
            ->whereDate('created_at', $fecha)
            ->where('planta', $plantaConsulta)
            ->when(
                is_null($tiempoExtra),
                fn($q) => $q->whereNull('tiempo_extra'),
                fn($q) => $q->where('tiempo_extra', $tiempoExtra)
            )
            ->get();

        // 2) Agrupar la colección primero por módulo y luego por estilo
        $agrupados = $registros->groupBy(['modulo', 'estilo']);

        $dataModuloEstiloProceso = [];

        // 3) Recorrer cada grupo y calcular todas las métricas en memoria
        foreach ($agrupados as $modulo => $porEstilo) {
            foreach ($porEstilo as $estilo => $items) {
                // Auditores únicos
                $auditoresUnicos = $items->pluck('auditor')
                    ->filter()
                    ->unique()
                    ->implode(', ');

                // Supervisores únicos
                $supervisoresUnicos = $items->pluck('team_leader')
                    ->filter()
                    ->unique()
                    ->implode(', ');

                // Cantidad de recorridos (máximo de repeticiones de un mismo nombre)
                $cantidadRecorridos = $items->pluck('nombre')
                    ->filter()
                    ->countBy()
                    ->max() ?: 0;

                // Sumas y porcentaje de error de proceso
                $sumaAuditadaProceso  = $items->sum('cantidad_auditada');
                $sumaRechazadaProceso = $items->sum('cantidad_rechazada');
                $porcentajeErrorProceso = $sumaAuditadaProceso
                    ? ($sumaRechazadaProceso / $sumaAuditadaProceso) * 100
                    : 0;

                // Conteo de operarios (sin utility)
                $conteoOperario = $items
                    ->filter(fn($i) => is_null($i->utility))
                    ->pluck('nombre')
                    ->filter()
                    ->unique()
                    ->count();

                // Conteo de operarios utility=1
                $conteoUtility = $items
                    ->filter(fn($i) => $i->utility == 1)
                    ->pluck('nombre')
                    ->filter()
                    ->unique()
                    ->count();

                // Conteo y suma de minutos de paro, y promedio entero
                $conteoMinutos = $items->pluck('minutos_paro')
                    ->filter()
                    ->count();
                $sumaMinutos = $items->sum('minutos_paro');
                $promedioMinutosEntero = $conteoMinutos
                    ? (int) ceil($sumaMinutos / $conteoMinutos)
                    : 0;

                // Operarios con rechazo > 0, contados y formateados
                $operariosUnicos = $items
                    ->filter(fn($i) => $i->cantidad_rechazada > 0)
                    ->pluck('nombre')
                    ->filter()
                    ->countBy()
                    ->map(fn($count, $name) => $count > 1 ? "$name ($count)" : $name)
                    ->sort()
                    ->values()
                    ->implode(', ') ?: 'N/A';

                // Paro modular
                $sumaParoModular = $items->sum('minutos_paro_modular') ?: 'N/A';
                $conteParoModular = $items->pluck('minutos_paro_modular')
                    ->filter()
                    ->count();

                // Defectos únicos con relación TpAseguramientoCalidad
                $defectosUnicos = $items->pluck('TpAseguramientoCalidad')
                    ->flatten()
                    ->pluck('tp')
                    ->filter(fn($tp) => $tp !== 'NINGUNO')
                    ->countBy()
                    ->map(fn($count, $tp) => $count > 1 ? "$tp ($count)" : $tp)
                    ->sort()
                    ->values()
                    ->implode(', ') ?: 'N/A';

                // Acciones correctivas únicas
                $accionesCorrectivasUnicos = $items->pluck('ac')
                    ->filter()
                    ->unique()
                    ->implode(', ') ?: 'N/A';

                // 4) Armar el array de salida idéntico al original
                $dataModuloEstiloProceso[] = [
                    'modulo'                   => $modulo,
                    'estilo'                   => $estilo,
                    'auditoresUnicos'          => $auditoresUnicos,
                    'supervisoresUnicos'       => $supervisoresUnicos,
                    'cantidadRecorridos'       => $cantidadRecorridos,
                    'sumaAuditadaProceso'      => $sumaAuditadaProceso,
                    'sumaRechazadaProceso'     => $sumaRechazadaProceso,
                    'porcentajeErrorProceso'   => $porcentajeErrorProceso,
                    'conteoOperario'           => $conteoOperario,
                    'conteoUtility'            => $conteoUtility,
                    'conteoMinutos'            => $conteoMinutos,
                    'sumaMinutos'              => $sumaMinutos,
                    'promedioMinutosEntero'    => $promedioMinutosEntero,
                    'operariosUnicos'          => $operariosUnicos,
                    'sumaParoModular'          => $sumaParoModular,
                    'conteParoModular'         => $conteParoModular,
                    'defectosUnicos'           => $defectosUnicos,
                    'accionesCorrectivasUnicos' => $accionesCorrectivasUnicos,
                ];
            }
        }

        return $dataModuloEstiloProceso;
    }

    private function getDatosModuloEstiloAQLDetalles($fecha, $planta, $modulo, $estilo, $tiempoExtra = null)
    {
        $cacheKey = "aql_detalles_{$planta}_{$modulo}_{$estilo}_{$fecha}_" . ($tiempoExtra ? 'te' : 'tn');

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($fecha, $planta, $modulo, $estilo, $tiempoExtra) {
            $query = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->where('planta', $planta)
                ->whereDate('created_at', $fecha)
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
                    'op' => $registro->op,
                    'bulto' => $registro->bulto,
                    'pieza' => $registro->pieza,
                    'talla' => $registro->talla,
                    'color' => $registro->color,
                    'estilo' => $registro->estilo,
                    'cantidad_auditada' => $registro->cantidad_auditada,
                    'cantidad_rechazada' => $registro->cantidad_rechazada,
                    'defectos' => $registro->tpAuditoriaAQL->pluck('tp')->filter()->implode(', ') ?: 'N/A',
                    'accion_correctiva' => $registro->ac ?? 'N/A',
                    'hora' => optional($registro->created_at)->format('H:i:s') ?? 'N/A',
                ];
            });
        });
    }


    public function obtenerDetallesAQLP2(Request $request)
    {
        $modulo = $request->input('modulo');
        $estilo = $request->input('estilo');
        $fecha = $request->input('fecha');
        $tiempoExtra = $request->input('tiempo_extra');

        // Convertir "null" en texto a null real
        $tiempoExtra = ($tiempoExtra === 'null' || $tiempoExtra === '') ? null : $tiempoExtra;

        $planta = "Intimark2";


        if (!$modulo || !$estilo || !$fecha) {
            Log::warning('Faltan parámetros necesarios en obtenerDetallesAQLP2');
            return response()->json(['error' => 'Faltan parámetros necesarios'], 400);
        }

        $detalles = $this->getDatosModuloEstiloAQLDetalles($fecha, $planta, $modulo, $estilo, $tiempoExtra);

        return response()->json($detalles);
    }

    public function obtenerDetallesAQLP1(Request $request)
    {
        $modulo = $request->input('modulo');
        $estilo = $request->input('estilo');
        $fecha = $request->input('fecha');
        $tiempoExtra = $request->input('tiempo_extra');

        // Convertir "null" en texto a null real
        $tiempoExtra = ($tiempoExtra === 'null' || $tiempoExtra === '') ? null : $tiempoExtra;

        $planta = "Intimark1";


        if (!$modulo || !$estilo || !$fecha) {
            //Log::warning('Faltan parámetros necesarios en obtenerDetallesAQLP2');
            return response()->json(['error' => 'Faltan parámetros necesarios'], 400);
        }

        $detalles = $this->getDatosModuloEstiloAQLDetalles($fecha, $planta, $modulo, $estilo, $tiempoExtra);

        return response()->json($detalles);
    }

    private function getDatosModuloEstiloProcesoDetalles($fecha, $planta, $modulo, $estilo, $tiempoExtra = null)
    {
        // Clave de caché única por cada combinación de planta, módulo, estilo, fecha y tipo de turno
        $cacheKey = "proceso_detalles_{$planta}_{$modulo}_{$estilo}_{$fecha}_" . ($tiempoExtra ? 'te' : 'tn');

        // Tiempo de vida del caché: 10 minutos (ajustable)
        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($modulo, $estilo, $planta, $fecha, $tiempoExtra) {
            $query = AseguramientoCalidad::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->where('planta', $planta)
                ->whereDate('created_at', $fecha)
                ->with('tpAseguramientoCalidad');

            if (is_null($tiempoExtra)) {
                $query->whereNull('tiempo_extra');
            } else {
                $query->where('tiempo_extra', $tiempoExtra);
            }

            return $query->get()->map(function ($registro) {
                return [
                    'minutos_paro' => $registro->minutos_paro,
                    'cliente' => $registro->cliente,
                    'nombre' => $registro->nombre,
                    'operacion' => $registro->operacion,
                    'cantidad_auditada' => $registro->cantidad_auditada,
                    'cantidad_rechazada' => $registro->cantidad_rechazada,
                    'tipo_problema' => $registro->tpAseguramientoCalidad->pluck('tp')->filter()->implode(', ') ?: 'N/A',
                    'ac' => $registro->ac ?? 'N/A',
                    'pxp' => $registro->pxp ?? 'N/A',
                    'hora' => optional($registro->created_at)->format('H:i:s') ?? 'N/A',
                ];
            });
        });
    }

    public function obtenerDetallesProcesoP2(Request $request)
    {
        $modulo = $request->input('modulo');
        $estilo = $request->input('estilo');
        $fecha = $request->input('fecha');
        $tiempoExtra = $request->input('tiempo_extra');

        $tiempoExtra = ($tiempoExtra === 'null' || $tiempoExtra === '') ? null : $tiempoExtra;
        $planta = "Intimark2";

        if (!$modulo || !$estilo || !$fecha) {
            Log::warning('Faltan parámetros en obtenerDetallesProcesoP2');
            return response()->json(['error' => 'Faltan parámetros necesarios'], 400);
        }

        $detalles = $this->getDatosModuloEstiloProcesoDetalles($fecha, $planta, $modulo, $estilo, $tiempoExtra);

        return response()->json($detalles);
    }

    public function obtenerDetallesProcesoP1(Request $request)
    {
        //Log::info('datos de request' . "{$request}");
        $modulo = $request->input('modulo');
        $estilo = $request->input('estilo');
        $fecha = $request->input('fecha');
        $tiempoExtra = $request->input('tiempo_extra');

        $tiempoExtra = ($tiempoExtra === 'null' || $tiempoExtra === '') ? null : $tiempoExtra;
        $planta = "Intimark1";

        if (!$modulo || !$estilo || !$fecha) {
            Log::warning('Faltan parámetros en obtenerDetallesProcesoP2');
            return response()->json(['error' => 'Faltan parámetros necesarios'], 400);
        }

        $detalles = $this->getDatosModuloEstiloProcesoDetalles($fecha, $planta, $modulo, $estilo, $tiempoExtra);

        return response()->json($detalles);
    }
}
