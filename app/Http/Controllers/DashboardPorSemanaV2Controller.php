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
        $cacheKey = "aql_{$plantaConsulta}_{$fechaInicio->format('Y-m-d')}";

        Log::info("🔎 Iniciando consulta AQL para semana: {$fechaInicio->toDateTimeString()} - {$fechaFin->toDateTimeString()}");

        $inicio = microtime(true);
        $datosModuloEstiloAQL = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fechaInicio, $fechaFin, $plantaConsulta) {
            return $this->getDatosModuloEstiloAQL($fechaInicio, $plantaConsulta, null, $fechaFin);
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
        $cacheKey = "aqlte_{$plantaConsulta}_{$fechaInicio->format('Y-m-d')}";

        Log::info("🔎 Iniciando consulta AQL TE para semana: {$fechaInicio->toDateTimeString()} - {$fechaFin->toDateTimeString()}");

        $inicio = microtime(true);
        $datosModuloEstiloAQLTE = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fechaInicio, $fechaFin, $plantaConsulta) {
            return $this->getDatosModuloEstiloAQL($fechaInicio, $plantaConsulta, 1, $fechaFin);
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
        $cacheKey = "proceso_{$plantaConsulta}_{$fechaInicio->format('Y-m-d')}";

        Log::info("🔎 Iniciando consulta Proceso para semana: {$fechaInicio->toDateTimeString()} - {$fechaFin->toDateTimeString()}");

        $inicio = microtime(true);
        $datosModuloEstiloProceso = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fechaInicio, $fechaFin, $plantaConsulta) {
            return $this->getDatosModuloEstiloProceso($fechaInicio, $plantaConsulta, null, $fechaFin);
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
        $cacheKey = "proceso_te_{$plantaConsulta}_{$fechaInicio->format('Y-m-d')}";

        Log::info("🔎 Iniciando consulta Proceso TE para semana: {$fechaInicio->toDateTimeString()} - {$fechaFin->toDateTimeString()}");

        $inicio = microtime(true);
        $datosModuloEstiloProcesoTE = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fechaInicio, $fechaFin, $plantaConsulta) {
            return $this->getDatosModuloEstiloProceso($fechaInicio, $plantaConsulta, 1, $fechaFin);
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
        $cacheKey = "aql_{$plantaConsulta}_{$fechaInicio->format('Y-m-d')}";

        Log::info("🔎 P2 Iniciando consulta AQL para semana: {$fechaInicio->toDateTimeString()} - {$fechaFin->toDateTimeString()}");

        $inicio = microtime(true);
        $datosModuloEstiloAQL = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fechaInicio, $fechaFin, $plantaConsulta) {
            return $this->getDatosModuloEstiloAQL($fechaInicio, $plantaConsulta, null, $fechaFin);
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
        $cacheKey = "aqlte_{$plantaConsulta}_{$fechaInicio->format('Y-m-d')}";

        Log::info("🔎 P2 Iniciando consulta AQL TE para semana: {$fechaInicio->toDateTimeString()} - {$fechaFin->toDateTimeString()}");

        $inicio = microtime(true);
        $datosModuloEstiloAQLTE = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fechaInicio, $fechaFin, $plantaConsulta) {
            return $this->getDatosModuloEstiloAQL($fechaInicio, $plantaConsulta, 1, $fechaFin);
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
        $cacheKey = "proceso_{$plantaConsulta}_{$fechaInicio->format('Y-m-d')}";

        Log::info("🔎 P2 Iniciando consulta Proceso para semana: {$fechaInicio->toDateTimeString()} - {$fechaFin->toDateTimeString()}");

        $inicio = microtime(true);
        $datosModuloEstiloProceso = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fechaInicio, $fechaFin, $plantaConsulta) {
            return $this->getDatosModuloEstiloProceso($fechaInicio, $plantaConsulta, null, $fechaFin);
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
        $cacheKey = "proceso_te_{$plantaConsulta}_{$fechaInicio->format('Y-m-d')}";

        Log::info("🔎 P2 Iniciando consulta Proceso TE para semana: {$fechaInicio->toDateTimeString()} - {$fechaFin->toDateTimeString()}");

        $inicio = microtime(true);
        $datosModuloEstiloProcesoTE = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fechaInicio, $fechaFin, $plantaConsulta) {
            return $this->getDatosModuloEstiloProceso($fechaInicio, $plantaConsulta, 1, $fechaFin);
        });
        Log::info("⏳ P2 Tiempo ejecución Proceso TE: " . round(microtime(true) - $inicio, 3) . "s");

        return response()->json([
            'datosModuloEstiloProcesoTE' => count($datosModuloEstiloProcesoTE) > 0 ? $datosModuloEstiloProcesoTE : []
        ]);
    }

    private function getDatosModuloEstiloAQL($fecha, $plantaConsulta, $tiempoExtra = null)
    {
        // Construcción de la consulta base usando la fecha y planta proporcionadas
        $query = AuditoriaAQL::whereDate('created_at', $fecha)
            ->where('planta', $plantaConsulta);

        // Filtro condicional para $tiempoExtra
        if (is_null($tiempoExtra)) {
            $query->whereNull('tiempo_extra');
        } else {
            $query->where('tiempo_extra', $tiempoExtra);
        }

        // Obtener combinaciones únicas de módulo y estilo, y ordenar por módulo
        $modulosEstilosAQL = $query->select('modulo', 'estilo')
            ->distinct()
            ->orderBy('modulo', 'asc')
            ->get();

        // Inicializar un arreglo para almacenar los resultados
        $dataModuloEstiloAQL = [];

        // Recorrer cada combinación de módulo y estilo
        foreach ($modulosEstilosAQL as $item) {
            $modulo = $item->modulo;
            $estilo = $item->estilo;

            // Obtener auditores únicos
            $auditoresUnicos = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->distinct()
                ->pluck('auditor')
                ->implode(', ');

            //
            // Obtener supervisores únicos
            $supervisoresUnicos = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->distinct()
                ->pluck('team_leader')
                ->implode(', ');

            // Obtener modulos únicos y otras métricas específicas para AQL
            $modulosUnicos = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->distinct()
                ->count('modulo');

            $sumaAuditadaAQL = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->sum('cantidad_auditada');

            $sumaRechazadaAQL = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->sum('cantidad_rechazada');

            $porcentajeErrorAQL = ($sumaAuditadaAQL != 0) ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100 : 0;

            $conteoOperario = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->whereNotNull('nombre')
                ->where('nombre', '!=', '')
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->select(DB::raw('
                    SUM(
                        CHAR_LENGTH(nombre) - CHAR_LENGTH(REPLACE(nombre, ",", "")) + 1
                    ) as total_nombres
                '))
                ->first()
                ->total_nombres ?? 0;

            $conteoMinutos = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->count('minutos_paro');

            $sumaMinutos = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->sum('minutos_paro');

            $promedioMinutosEntero = $conteoMinutos != 0 ? ceil($sumaMinutos / $conteoMinutos) : 0;

            $estilosUnicos = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->distinct()
                ->pluck('estilo')
                ->implode(', ');

            $defectosUnicos = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function ($query) {
                    return $query->whereNull('tiempo_extra');
                }, function ($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->whereHas('tpAuditoriaAQL', function ($query) {
                    $query->where('tp', '!=', 'NINGUNO');
                })
                ->with(['tpAuditoriaAQL' => function ($query) {
                    $query->where('tp', '!=', 'NINGUNO');
                }])
                ->get()
                ->pluck('tpAuditoriaAQL.*.tp') // Obtiene los valores de 'tp'
                ->flatten() // Aplana la colección anidada
                ->filter() // Elimina valores nulos o vacíos
                ->groupBy(fn($item) => $item) // Agrupa por el valor de 'tp'
                ->map(function ($items, $key) {
                    $count = $items->count();
                    return $count > 1 ? "$key ($count)" : $key; // Agrega el conteo solo si es mayor a 1
                })
                ->sort() // Ordena alfabéticamente
                ->values() // Reindexa la colección
                ->implode(', ') ?: 'N/A';            

            $accionesCorrectivasUnicos = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->distinct()
                ->pluck('ac')
                ->filter()  // Filtra valores nulos o vacíos
                ->values()  // Reindexa la colección
                ->implode(', ') ?: 'N/A';
            
            $operariosUnicos = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function ($query) {
                    return $query->whereNull('tiempo_extra');
                }, function ($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->pluck('nombre') // Obtiene los nombres
                ->flatMap(function ($item) {
                    return collect(explode(',', $item)) // Divide cada cadena por comas
                        ->map(fn($name) => trim($name)) // Elimina espacios adicionales
                        ->filter(); // Elimina valores vacíos o nulos
                })
                ->groupBy(fn($item) => $item) // Agrupa los nombres individuales
                ->map(function ($items, $key) {
                    $count = $items->count();
                    return $count > 1 ? "$key ($count)" : $key; // Agrega el conteo solo si es mayor a 1
                })
                ->sort() // Ordena alfabéticamente
                ->values() // Reindexa la colección
                ->implode(', ') ?: 'N/A';            

            $sumaParoModular = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->sum('minutos_paro_modular') ?: 'N/A';

            //
             // Nuevo cálculo para conteParoModular
            $conteParoModular = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->count('minutos_paro_modular');

            //
            $sumaPiezasBulto = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->sum('pieza');

            //
            $cantidadBultosEncontrados = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->count();

            //
            $cantidadBultosRechazados = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->where('cantidad_rechazada', '>', 0)
                ->count();

            //
            $sumaReparacionRechazo = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->sum('reparacion_rechazo');

            //
            $piezasRechazadasUnicas = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->where('cantidad_rechazada', '>', 0)
                ->sum('pieza'); // Cambia pluck y implode por sum para obtener el total

            // Si no encuentra datos, retorna 'N/A'
            $piezasRechazadasUnicas = $piezasRechazadasUnicas > 0 ? $piezasRechazadasUnicas : 'N/A';

            // Almacenar todos los resultados en el arreglo principal
            $dataModuloEstiloAQL[] = [
                'modulo' => $modulo,
                'estilo' => $estilo,
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

        // Retornar los datos procesados
        return $dataModuloEstiloAQL;
    }

    private function getDatosModuloEstiloProceso($fecha, $plantaConsulta, $tiempoExtra = null)
    {
        // Construcción de la consulta base usando la fecha y planta proporcionadas
        $query = AseguramientoCalidad::whereDate('created_at', $fecha)
            ->where('planta', $plantaConsulta);

        // Filtro condicional para $tiempoExtra
        if (is_null($tiempoExtra)) {
            $query->whereNull('tiempo_extra');
        } else {
            $query->where('tiempo_extra', $tiempoExtra);
        }

        // Obtener combinaciones únicas de módulo y estilo, y ordenar por módulo
        $modulosEstilosProceso = $query->select('modulo', 'estilo')
            ->distinct()
            ->orderBy('modulo', 'asc')
            ->get();

        // Inicializar un arreglo para almacenar los resultados
        $dataModuloEstiloProceso = [];

        // Recorrer cada combinación de módulo y estilo
        foreach ($modulosEstilosProceso as $item) {
            $modulo = $item->modulo;
            $estilo = $item->estilo;

            // Obtener auditores únicos
            $auditoresUnicos = AseguramientoCalidad::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->distinct()
                ->pluck('auditor')
                ->implode(', ');

            //
            // Obtener supervisores únicos
            $supervisoresUnicos = AseguramientoCalidad::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->distinct()
                ->pluck('team_leader')
                ->implode(', ');

            // Obtener el valor de cantidadRecorridos
            $cantidadRecorridos = AseguramientoCalidad::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->selectRaw('nombre, COUNT(*) as cantidad_repeticiones')
                ->groupBy('nombre')
                ->orderByDesc('cantidad_repeticiones')
                ->limit(1)
                ->value('cantidad_repeticiones');

            // Otros cálculos específicos
            $sumaAuditadaProceso = AseguramientoCalidad::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->sum('cantidad_auditada');

            $sumaRechazadaProceso = AseguramientoCalidad::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->sum('cantidad_rechazada');

            $porcentajeErrorProceso = ($sumaAuditadaProceso != 0) ? ($sumaRechazadaProceso / $sumaAuditadaProceso) * 100 : 0;

            $conteoOperario = AseguramientoCalidad::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->whereNull('utility')
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->distinct()
                ->count('nombre');

            $conteoUtility = AseguramientoCalidad::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->where('utility', 1)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->distinct()
                ->count('nombre');

            $conteoMinutos = AseguramientoCalidad::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->count('minutos_paro');

            $sumaMinutos = AseguramientoCalidad::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->sum('minutos_paro');

            $promedioMinutosEntero = $conteoMinutos != 0 ? ceil($sumaMinutos / $conteoMinutos) : 0;

            $operariosUnicos = AseguramientoCalidad::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function ($query) {
                    return $query->whereNull('tiempo_extra');
                }, function ($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->where('cantidad_rechazada', '>', 0)
                ->pluck('nombre') // Obtiene los nombres
                ->filter() // Elimina valores nulos o vacíos
                ->groupBy(fn($item) => $item) // Agrupa por nombre
                ->map(function ($items, $key) {
                    $count = $items->count();
                    return $count > 1 ? "$key ($count)" : $key; // Agrega el conteo solo si es mayor a 1
                })
                ->sort() // Ordena alfabéticamente
                ->values() // Reindexa la colección
                ->implode(', ') ?: 'N/A';

            $sumaParoModular = AseguramientoCalidad::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->sum('minutos_paro_modular') ?: 'N/A';

            $conteParoModular = AseguramientoCalidad::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->count('minutos_paro_modular');

            // Obtener el valor de defectosUnicos
            $defectosUnicos = AseguramientoCalidad::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function ($query) {
                    return $query->whereNull('tiempo_extra');
                }, function ($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->whereHas('TpAseguramientoCalidad', function ($query) {
                    $query->where('tp', '!=', 'NINGUNO');
                })
                ->with(['TpAseguramientoCalidad' => function ($query) {
                    $query->where('tp', '!=', 'NINGUNO');
                }])
                ->get()
                ->pluck('TpAseguramientoCalidad.*.tp') // Obtiene los valores de 'tp'
                ->flatten() // Aplana la colección anidada
                ->filter() // Elimina valores nulos o vacíos
                ->groupBy(fn($item) => $item) // Agrupa por el valor de 'tp'
                ->map(function ($items, $key) {
                    $count = $items->count();
                    return $count > 1 ? "$key ($count)" : $key; // Agrega el conteo solo si es mayor a 1
                })
                ->sort() // Ordena alfabéticamente
                ->values() // Reindexa la colección
                ->implode(', ') ?: 'N/A';

            //
            $accionesCorrectivasUnicos = AseguramientoCalidad::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->distinct()
                ->pluck('ac')
                ->filter()  // Filtra valores nulos o vacíos
                ->values()  // Reindexa la colección
                ->implode(', ') ?: 'N/A';

            // Almacenar todos los resultados en el arreglo principal
            $dataModuloEstiloProceso[] = [
                'modulo' => $modulo,
                'estilo' => $estilo,
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

        // Retornar los datos procesados
        return $dataModuloEstiloProceso;
    }

    private function getDatosModuloEstiloAQLDetalles($fechaInicio, $fechaFin, $planta, $modulo, $estilo, $tiempoExtra = null)
    {
        $cacheKey = "aql_detalles_{$planta}_{$modulo}_{$estilo}_{$fechaInicio->format('Ymd')}_{$fechaFin->format('Ymd')}_" . ($tiempoExtra ? 'te' : 'tn');

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fechaInicio, $fechaFin, $planta, $modulo, $estilo, $tiempoExtra) {
            $query = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
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
                    'hora' => optional($registro->created_at)->format('H:i:s') ?? 'N/A',
                ];
            });
        });
    }


    public function obtenerDetallesAQLP2(Request $request)
    {
        Log::info('datos de request: ' . json_encode($request->all()));
        $modulo = $request->input('modulo');
        $estilo = $request->input('estilo');
        $fechaSemana = $request->input('fecha');
        $tiempoExtra = $request->input('tiempo_extra');

        $tiempoExtra = ($tiempoExtra === 'null' || $tiempoExtra === '') ? null : $tiempoExtra;
        $planta = "Intimark2";

        if (!$modulo || !$estilo || !$fechaSemana) {
            Log::warning('Faltan parámetros necesarios en obtenerDetallesAQLP2');
            return response()->json(['error' => 'Faltan parámetros necesarios'], 400);
        }

        // Convertir semana a rango
        $fechaInicio = Carbon::parse($fechaSemana)->startOfWeek()->setTime(0, 0, 0);
        $fechaFin = Carbon::parse($fechaSemana)->endOfWeek()->setTime(23, 59, 59);
        Log::info('fechaInicio: ' . $fechaInicio);
        Log::info('fechaFin: ' . $fechaFin);

        $detalles = $this->getDatosModuloEstiloAQLDetalles($fechaInicio, $fechaFin, $planta, $modulo, $estilo, $tiempoExtra);

        return response()->json($detalles);
    }

    public function obtenerDetallesAQLP1(Request $request)
    {
        Log::info('datos de request: ' . json_encode($request->all()));
        $modulo = $request->input('modulo');
        $estilo = $request->input('estilo');
        $fechaSemana = $request->input('fecha');
        $tiempoExtra = $request->input('tiempo_extra');

        $tiempoExtra = ($tiempoExtra === 'null' || $tiempoExtra === '') ? null : $tiempoExtra;
        $planta = "Intimark1";

        if (!$modulo || !$estilo || !$fechaSemana) {
            return response()->json(['error' => 'Faltan parámetros necesarios'], 400);
        }

        // Convertir semana a rango
        $fechaInicio = Carbon::parse($fechaSemana)->startOfWeek()->setTime(0, 0, 0);
        $fechaFin = Carbon::parse($fechaSemana)->endOfWeek()->setTime(23, 59, 59);
        Log::info('fechaInicio: ' . $fechaInicio);
        Log::info('fechaFin: ' . $fechaFin);
        
        $detalles = $this->getDatosModuloEstiloAQLDetalles($fechaInicio, $fechaFin, $planta, $modulo, $estilo, $tiempoExtra);

        return response()->json($detalles);
    }

    private function getDatosModuloEstiloProcesoDetalles($fechaInicio, $fechaFin, $planta, $modulo, $estilo, $tiempoExtra = null)
    {
        $cacheKey = "proceso_detalles_{$planta}_{$modulo}_{$estilo}_{$fechaInicio->format('Ymd')}_{$fechaFin->format('Ymd')}_" . ($tiempoExtra ? 'te' : 'tn');

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($fechaInicio, $fechaFin, $modulo, $estilo, $planta, $tiempoExtra) {
            $query = AseguramientoCalidad::where('modulo', $modulo)
                ->where('estilo', $estilo)
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
        Log::info('datos de request: ' . json_encode($request->all()));
        $modulo = $request->input('modulo');
        $estilo = $request->input('estilo');
        $fechaSemana = $request->input('fecha');
        $tiempoExtra = $request->input('tiempo_extra');

        $tiempoExtra = ($tiempoExtra === 'null' || $tiempoExtra === '') ? null : $tiempoExtra;
        $planta = "Intimark2";

        if (!$modulo || !$estilo || !$fechaSemana) {
            Log::warning('Faltan parámetros en obtenerDetallesProcesoP2');
            return response()->json(['error' => 'Faltan parámetros necesarios'], 400);
        }

        $fechaInicio = Carbon::parse($fechaSemana)->startOfWeek()->setTime(0, 0, 0);
        $fechaFin = Carbon::parse($fechaSemana)->endOfWeek()->setTime(23, 59, 59);
        Log::info('fechaInicio: ' . $fechaInicio);
        Log::info('fechaFin: ' . $fechaFin);

        $detalles = $this->getDatosModuloEstiloProcesoDetalles($fechaInicio, $fechaFin, $planta, $modulo, $estilo, $tiempoExtra);

        return response()->json($detalles);
    }

    public function obtenerDetallesProcesoP1(Request $request)
    {
        Log::info('datos de request: ' . json_encode($request->all()));
        $modulo = $request->input('modulo');
        $estilo = $request->input('estilo');
        $fechaSemana = $request->input('fecha');
        $tiempoExtra = $request->input('tiempo_extra');

        $tiempoExtra = ($tiempoExtra === 'null' || $tiempoExtra === '') ? null : $tiempoExtra;
        $planta = "Intimark1";

        if (!$modulo || !$estilo || !$fechaSemana) {
            Log::warning('Faltan parámetros en obtenerDetallesProcesoP1');
            return response()->json(['error' => 'Faltan parámetros necesarios'], 400);
        }

        $fechaInicio = Carbon::parse($fechaSemana)->startOfWeek()->setTime(0, 0, 0);
        $fechaFin = Carbon::parse($fechaSemana)->endOfWeek()->setTime(23, 59, 59);
        Log::info('fechaInicio: ' . $fechaInicio);
        Log::info('fechaFin: ' . $fechaFin);

        $detalles = $this->getDatosModuloEstiloProcesoDetalles($fechaInicio, $fechaFin, $planta, $modulo, $estilo, $tiempoExtra);

        return response()->json($detalles);
    }


}
