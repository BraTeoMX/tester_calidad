<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\AuditoriaAQL;
use App\Models\AseguramientoCalidad;
use App\Models\TpAseguramientoCalidad;
use App\Models\TpAuditoriaAQL;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod; // Asegúrate de importar la clase Carbon
use Illuminate\Support\Facades\DB; // Importa la clase DB


class DashboardController extends Controller
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

    public function dashboarAProcesoPlayera()
    {
        $title = "";

        $clientes = AseguramientoCalidad::whereNotNull('cliente')
            ->orderBy('cliente')
            ->pluck('cliente')
            ->unique();
        $porcentajesError = [];

        foreach ($clientes as $cliente) {
            $sumaAuditada = AseguramientoCalidad::where('cliente', $cliente)->sum('cantidad_auditada');
            $sumaRechazada = AseguramientoCalidad::where('cliente', $cliente)->sum('cantidad_rechazada');

            if ($sumaAuditada != 0) {
                $porcentajeError = ($sumaRechazada / $sumaAuditada) * 100;
            } else {
                $porcentajeError = 0;
            }

            $porcentajesError[$cliente] = $porcentajeError;
        }
        // Ordenar los cleintes por el porcentaje de error de mayor a menor
        arsort($porcentajesError);


        // apartado para operarios de maquina
        $nombres = AseguramientoCalidad::whereNotNull('nombre')
            ->orderBy('nombre')
            ->pluck('nombre')
            ->unique();
        $porcentajesErrorNombre = [];

        foreach ($nombres as $nombre) {
            $sumaAuditadaNombre = AseguramientoCalidad::where('nombre', $nombre)->sum('cantidad_auditada');
            $sumaRechazadaNombre = AseguramientoCalidad::where('nombre', $nombre)->sum('cantidad_rechazada');

            if ($sumaAuditadaNombre != 0) {
                $porcentajeErrorNombre = ($sumaRechazadaNombre / $sumaAuditadaNombre) * 100;
            } else {
                $porcentajeErrorNombre = 0;
            }

            $porcentajesErrorNombre[$nombre] = $porcentajeErrorNombre;

            // Obtener la operación correspondiente al operario de máquina
            $operacion = AseguramientoCalidad::where('nombre', $nombre)->value('operacion');
            $operacionesPorNombre[$nombre] = $operacion;
            // Obtener la operación correspondiente al team leader vinculado al operario de máquina
            $teamleader = AseguramientoCalidad::where('nombre', $nombre)->value('team_leader');
            $teamLeaderPorNombre[$nombre] = $teamleader;
            // Obtener la modulo correspondiente al operario de máquina
            $modulo = AseguramientoCalidad::where('nombre', $nombre)->value('modulo');
            $moduloPorNombre[$nombre] = $modulo;

        }
        // Ordenar los operarios de maquina por el porcentaje de error de mayor a menor
        arsort($porcentajesErrorNombre);

        //apartado para team leader
        $teamLeaders = AseguramientoCalidad::whereNotNull('team_leader')
            ->orderBy('team_leader')
            ->pluck('team_leader')
            ->unique();
        $porcentajesErrorTeamLeader = [];

        foreach ($teamLeaders as $teamLeader) {
            $sumaAuditadaTeamLeader = AseguramientoCalidad::where('team_leader', $teamLeader)->sum('cantidad_auditada');
            $sumaRechazadaTeamLeader = AseguramientoCalidad::where('team_leader', $teamLeader)->sum('cantidad_rechazada');

            if ($sumaAuditadaTeamLeader != 0) {
                $porcentajeErrorTeamLeader = ($sumaRechazadaTeamLeader / $sumaAuditadaTeamLeader) * 100;
            } else {
                $porcentajeErrorTeamLeader = 0;
            }

            $porcentajesErrorTeamLeader[$teamLeader] = $porcentajeErrorTeamLeader;
        }
        // Ordenar los team leaders por el porcentaje de error de mayor a menor
        arsort($porcentajesErrorTeamLeader);

        return view('dashboar.dashboarAProcesoPlayera', compact('title', 'clientes', 'porcentajesError',
                'nombres', 'porcentajesErrorNombre', 'operacionesPorNombre', 'teamLeaderPorNombre', 'moduloPorNombre',
                'teamLeaders', 'porcentajesErrorTeamLeader'));
    }

    public function dashboarAProcesoAQL(Request $request)
    {
        $title = "";

        // Obtener la semana de inicio y la semana de fin directamente del Request
        if ($request->fecha_fin) {
            $fechaInicio = Carbon::parse($request->input('fecha_inicio'))->startOfWeek();
            $fechaFin = Carbon::parse($request->input('fecha_fin'))->endOfWeek();
        } else {
            $fechaFin = Carbon::now()->endOfWeek();
            $fechaInicio = $fechaFin->copy()->subWeeks(2)->startOfWeek();
        }

        // Obtener las semanas en el rango
        $semanas = collect();
        $currentWeek = $fechaInicio->copy();
        while ($currentWeek <= $fechaFin) {
            $semanas->push($currentWeek->format('Y-W')); // Formato Año-Semana
            $currentWeek->addWeek();
        }

        // Calcular porcentajes AQL y Proceso para cada semana
        $porcentajesAQL = $semanas->map(function($semana) {
            list($year, $week) = explode('-', $semana);
            return $this->calcularPorcentajePorSemana(AuditoriaAQL::class, $year, $week);
        });

        $porcentajesProceso = $semanas->map(function($semana) {
            list($year, $week) = explode('-', $semana);
            return $this->calcularPorcentajePorSemana(AseguramientoCalidad::class, $year, $week);
        });

        // Datos para las gráficas de clientes
        $dataGrafica = $this->obtenerDatosClientesPorRangoFechas($fechaInicio, $fechaFin);
        $clientesGrafica = collect($dataGrafica['clientesUnicos'])->toArray();
        $semanasGrafica = $semanas->toArray();
        $datasetsAQL = collect($dataGrafica['dataCliente'])->map(function ($clienteData) {
            return [
                'label' => $clienteData['cliente'],
                'data' => $clienteData['porcentajesErrorAQL'],
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'borderWidth' => 1,
                'fill' => false
            ];
        })->toArray();
        $datasetsProceso = collect($dataGrafica['dataCliente'])->map(function ($clienteData) {
            return [
                'label' => $clienteData['cliente'],
                'data' => $clienteData['porcentajesErrorProceso'],
                'borderColor' => 'rgba(153, 102, 255, 1)',
                'borderWidth' => 1,
                'fill' => false
            ];
        })->toArray();

        // Datos para las gráficas de módulos
        $dataGraficaModulo = $this->obtenerDatosModulosPorRangoFechas($fechaInicio, $fechaFin);
        $modulosGrafica = collect($dataGraficaModulo['modulosUnicos'])->toArray();

        $datasetsAQLModulos = collect($dataGraficaModulo['dataModulo'])->map(function ($moduloData) {
            return [
                'label' => $moduloData['modulo'],
                'data' => $moduloData['porcentajesErrorAQL'],
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'borderWidth' => 1,
                'fill' => false
            ];
        })->toArray();

        $datasetsProcesoModulos = collect($dataGraficaModulo['dataModulo'])->map(function ($moduloData) {
            return [
                'label' => $moduloData['modulo'],
                'data' => $moduloData['porcentajesErrorProceso'],
                'borderColor' => 'rgba(153, 102, 255, 1)',
                'borderWidth' => 1,
                'fill' => false
            ];
        })->toArray();

        // Datos para las gráficas de supervisor antes team leader
        $dataGraficaSupervisor = $this->obtenerDatosTeamLeaderPorRangoFechas($fechaInicio, $fechaFin);
        $teamLeadersGrafica = collect($dataGraficaSupervisor['teamLeadersUnicos'])->toArray();
        $datasetsAQLSupervisor = collect($dataGraficaSupervisor['dataTeamLeader'])->map(function ($teamLeaderData) {
            return [
                'label' => $teamLeaderData['team_leader'],
                'data' => $teamLeaderData['porcentajesErrorAQL'],
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'borderWidth' => 1,
                'fill' => false
            ];
        })->toArray();
        $datasetsProcesoSupervisor = collect($dataGraficaSupervisor['dataTeamLeader'])->map(function ($teamLeaderData) {
            return [
                'label' => $teamLeaderData['team_leader'],
                'data' => $teamLeaderData['porcentajesErrorProceso'],
                'borderColor' => 'rgba(153, 102, 255, 1)',
                'borderWidth' => 1,
                'fill' => false
            ];
        })->toArray();

        // Datos generales
        $dataGeneral = $this->obtenerDatosClientesPorRangoFechas($fechaInicio, $fechaFin);
        $totalGeneral = $this->calcularTotales($dataGeneral['dataCliente'], $fechaInicio, $fechaFin);

        // Datos para gerentes de producción
        $dataGerentesAQLGeneral = $this->getDataGerentesProduccionAQL($fechaInicio, $fechaFin);
        $dataGerentesProcesoGeneral = $this->getDataGerentesProduccionProceso($fechaInicio, $fechaFin);
        $dataGerentesGeneral = $this->combineDataGerentes($dataGerentesAQLGeneral, $dataGerentesProcesoGeneral);

        // Datos para módulos
        $dataModuloAQLGeneral = $this->getDataModuloAQL($fechaInicio, $fechaFin);
        $dataModuloProcesoGeneral = $this->getDataModuloProceso($fechaInicio, $fechaFin);
        $dataModuloAQLPlanta1 = $this->getDataModuloAQL($fechaInicio, $fechaFin, 'Intimark1');
        $dataModuloAQLPlanta2 = $this->getDataModuloAQL($fechaInicio, $fechaFin, 'Intimark2');
        $dataModuloProcesoPlanta1 = $this->getDataModuloProceso($fechaInicio, $fechaFin, 'Intimark1');
        $dataModuloProcesoPlanta2 = $this->getDataModuloProceso($fechaInicio, $fechaFin, 'Intimark2');
        $dataModulosGeneral = $this->combineDataModulos($dataModuloAQLGeneral, $dataModuloProcesoGeneral);

        // Consulta para obtener los 3 valores más repetidos de 'tp' excluyendo 'NINGUNO'
        $topDefectosAQL = TpAuditoriaAQL::select('tp', DB::raw('count(*) as total'))
            ->where('tp', '!=', 'NINGUNO')
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->groupBy('tp')
            ->orderBy('total', 'desc')
            ->limit(3)
            ->get();

        $topDefectosProceso = TpAseguramientoCalidad::select('tp', DB::raw('count(*) as total'))
            ->where('tp', '!=', 'NINGUNO')
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->groupBy('tp')
            ->orderBy('total', 'desc')
            ->limit(3)
            ->get();

        //para textos

        // Obtener las fechas
        $fechaInicioUnico = substr($fechaInicio, 0, 10); // Extraer solo la parte de la fecha
        $fechaFinUnico = substr($fechaFin, 0, 10); // Extraer solo la parte de la fecha
        $fechaInicioExplode = explode('-', $fechaInicioUnico);
        if (count($fechaInicioExplode) === 3) {
            $diaInicio = $fechaInicioExplode[2];
            $mesInicio = (int) $fechaInicioExplode[1]; // Convertir a entero
            $añoInicio = $fechaInicioExplode[0];
        } else {
            // Manejar el error de formato de fecha aquí
            return response()->json(['error' => 'Formato de fecha de inicio inválido'], 400);
        }

        $fechaFinExplode = explode('-', $fechaFinUnico);
        if (count($fechaFinExplode) === 3) {
            $diaFin = $fechaFinExplode[2];
            $mesFin = (int) $fechaFinExplode[1]; // Convertir a entero
            $añoFin = $fechaFinExplode[0];
        } else {
            // Manejar el error de formato de fecha aquí
            return response()->json(['error' => 'Formato de fecha de fin inválido'], 400);
        }

        $mesesDelAño = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        $nombreMesInicio = $mesesDelAño[$mesInicio] ?? '';
        $nombreMesFin = $mesesDelAño[$mesFin] ?? '';
        $fechaInicioFormateada = $diaInicio . ' de ' . $nombreMesInicio . ' de ' . $añoInicio . ' al ';
        $fechaFinFormateada = $diaFin . ' de ' . $nombreMesFin . ' de ' . $añoFin;

        return view('dashboar.dashboarAProcesoAQL', compact('title', 'semanas', 'porcentajesAQL', 'porcentajesProceso',
            'semanasGrafica', 'datasetsAQL', 'datasetsProceso', 'clientesGrafica', 'dataGeneral', 'totalGeneral',
            'dataGerentesGeneral', 'dataModulosGeneral', 'dataModuloAQLPlanta1', 'dataModuloAQLPlanta2',
            'dataModuloProcesoPlanta1', 'dataModuloProcesoPlanta2', 'topDefectosAQL', 'topDefectosProceso',
            'fechaInicio', 'fechaFin', 'dataModuloAQLGeneral', 'dataModuloProcesoGeneral',
            'fechaInicioFormateada', 'fechaFinFormateada', 'datasetsAQLModulos', 'datasetsProcesoModulos',
            'datasetsAQLSupervisor', 'datasetsProcesoSupervisor'));
    }

    private function calcularPorcentajePorSemana($modelo, $year, $week)
    {
        $startOfWeek = Carbon::now()->setISODate($year, $week)->startOfWeek();
        $endOfWeek = $startOfWeek->copy()->endOfWeek();
        $data = $modelo::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->selectRaw('SUM(cantidad_auditada) as cantidad_auditada, SUM(cantidad_rechazada) as cantidad_rechazada')
                    ->first();
        return $data->cantidad_auditada != 0 ? number_format(($data->cantidad_rechazada / $data->cantidad_auditada) * 100, 2) : 0;
    }

    private function obtenerDatosClientesPorRangoFechas($fechaInicio, $fechaFin, $planta = null)
    {
        $clientesUnicos = collect();
        $dataCliente = [];

        // Iterar sobre cada semana en el rango
        $period = CarbonPeriod::create($fechaInicio, '1 week', $fechaFin)->toArray();
        $semanasStr = array_map(function ($date) {
            return $date->format('Y-W');
        }, $period);

        foreach ($period as $week) {
            $startOfWeek = $week->startOfWeek()->toDateString();
            $endOfWeek = $week->endOfWeek()->toDateString();

            // Obtener clientes únicos para la semana actual
            $queryAQL = AuditoriaAQL::whereNotNull('cliente')->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
            $queryProceso = AseguramientoCalidad::whereNotNull('cliente')->whereBetween('created_at', [$startOfWeek, $endOfWeek]);

            if ($planta) {
                $queryAQL->where('planta', $planta);
                $queryProceso->where('planta', $planta);
            }

            $clientesAQL = $queryAQL->pluck('cliente');
            $clientesProceso = $queryProceso->pluck('cliente');
            $clientesDelDia = $clientesAQL->merge($clientesProceso)->unique();

            $clientesUnicos = $clientesUnicos->merge($clientesDelDia)->unique();

            foreach ($clientesDelDia as $cliente) {
                // Inicializar los datos del cliente si no existen
                if (!isset($dataCliente[$cliente])) {
                    $dataCliente[$cliente] = [
                        'cliente' => $cliente,
                        'semanas' => $semanasStr,
                        'porcentajesErrorAQL' => array_fill(0, count($semanasStr), 0),
                        'porcentajesErrorProceso' => array_fill(0, count($semanasStr), 0)
                    ];
                }

                // Obtener datos de AQL
                $sumaAuditadaAQL = AuditoriaAQL::where('cliente', $cliente)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->sum('cantidad_auditada');
                $sumaRechazadaAQL = AuditoriaAQL::where('cliente', $cliente)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->sum('cantidad_rechazada');

                $porcentajeErrorAQL = ($sumaAuditadaAQL != 0) ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100 : 0;

                // Obtener datos de Procesos
                $sumaAuditadaProceso = AseguramientoCalidad::where('cliente', $cliente)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->sum('cantidad_auditada');
                $sumaRechazadaProceso = AseguramientoCalidad::where('cliente', $cliente)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->sum('cantidad_rechazada');

                $porcentajeErrorProceso = ($sumaAuditadaProceso != 0) ? ($sumaRechazadaProceso / $sumaAuditadaProceso) * 100 : 0;

                // Encontrar el índice correspondiente a la semana
                $index = array_search($week->format('Y-W'), $semanasStr);

                // Agregar datos al array dataCliente
                $dataCliente[$cliente]['porcentajesErrorAQL'][$index] = $porcentajeErrorAQL;
                $dataCliente[$cliente]['porcentajesErrorProceso'][$index] = $porcentajeErrorProceso;

                // Agregar también las claves porcentajeErrorProceso y porcentajeErrorAQL al nivel superior
                $dataCliente[$cliente]['porcentajeErrorAQL'] = array_sum($dataCliente[$cliente]['porcentajesErrorAQL']) / count($dataCliente[$cliente]['porcentajesErrorAQL']);
                $dataCliente[$cliente]['porcentajeErrorProceso'] = array_sum($dataCliente[$cliente]['porcentajesErrorProceso']) / count($dataCliente[$cliente]['porcentajesErrorProceso']);
            }
        }

        // Convertir dataCliente a la estructura esperada
        $dataCliente = array_values($dataCliente);

        return [
            'clientesUnicos' => $clientesUnicos,
            'dataCliente' => $dataCliente,
        ];
    }

    private function obtenerDatosModulosPorRangoFechas($fechaInicio, $fechaFin, $planta = null)
    {
        $modulosUnicos = collect();
        $dataModulo = [];

        // Iterar sobre cada semana en el rango
        $period = CarbonPeriod::create($fechaInicio, '1 week', $fechaFin)->toArray();
        $semanasStr = array_map(function ($date) {
            return $date->format('Y-W');
        }, $period);

        foreach ($period as $week) {
            $startOfWeek = $week->startOfWeek()->toDateString();
            $endOfWeek = $week->endOfWeek()->toDateString();

            // Obtener módulos únicos para la semana actual
            $queryAQL = AuditoriaAQL::whereNotNull('modulo')->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
            $queryProceso = AseguramientoCalidad::whereNotNull('modulo')->whereBetween('created_at', [$startOfWeek, $endOfWeek]);

            if ($planta) {
                $queryAQL->where('planta', $planta);
                $queryProceso->where('planta', $planta);
            }

            $modulosAQL = $queryAQL->pluck('modulo');
            $modulosProceso = $queryProceso->pluck('modulo');
            $modulosDelDia = $modulosAQL->merge($modulosProceso)->unique();

            $modulosUnicos = $modulosUnicos->merge($modulosDelDia)->unique();

            foreach ($modulosDelDia as $modulo) {
                // Inicializar los datos del módulo si no existen
                if (!isset($dataModulo[$modulo])) {
                    $dataModulo[$modulo] = [
                        'modulo' => $modulo,
                        'semanas' => $semanasStr,
                        'porcentajesErrorAQL' => array_fill(0, count($semanasStr), 0),
                        'porcentajesErrorProceso' => array_fill(0, count($semanasStr), 0)
                    ];
                }

                // Obtener datos de AQL
                $sumaAuditadaAQL = AuditoriaAQL::where('modulo', $modulo)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->sum('cantidad_auditada');
                $sumaRechazadaAQL = AuditoriaAQL::where('modulo', $modulo)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->sum('cantidad_rechazada');

                $porcentajeErrorAQL = ($sumaAuditadaAQL != 0) ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100 : 0;

                // Obtener datos de Procesos
                $sumaAuditadaProceso = AseguramientoCalidad::where('modulo', $modulo)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->sum('cantidad_auditada');
                $sumaRechazadaProceso = AseguramientoCalidad::where('modulo', $modulo)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->sum('cantidad_rechazada');

                $porcentajeErrorProceso = ($sumaAuditadaProceso != 0) ? ($sumaRechazadaProceso / $sumaAuditadaProceso) * 100 : 0;

                // Encontrar el índice correspondiente a la semana
                $index = array_search($week->format('Y-W'), $semanasStr);

                // Agregar datos al array dataModulo
                $dataModulo[$modulo]['porcentajesErrorAQL'][$index] = $porcentajeErrorAQL;
                $dataModulo[$modulo]['porcentajesErrorProceso'][$index] = $porcentajeErrorProceso;

                // Agregar también las claves porcentajeErrorProceso y porcentajeErrorAQL al nivel superior
                $dataModulo[$modulo]['porcentajeErrorAQL'] = array_sum($dataModulo[$modulo]['porcentajesErrorAQL']) / count($dataModulo[$modulo]['porcentajesErrorAQL']);
                $dataModulo[$modulo]['porcentajeErrorProceso'] = array_sum($dataModulo[$modulo]['porcentajesErrorProceso']) / count($dataModulo[$modulo]['porcentajesErrorProceso']);
            }
        }

        // Convertir dataModulo a la estructura esperada
        $dataModulo = array_values($dataModulo);

        return [
            'modulosUnicos' => $modulosUnicos,
            'dataModulo' => $dataModulo,
        ];
    }

    private function obtenerDatosTeamLeaderPorRangoFechas($fechaInicio, $fechaFin, $planta = null)
    {
        $teamLeadersUnicos = collect();
        $dataTeamLeader = [];

        // Iterar sobre cada semana en el rango
        $period = CarbonPeriod::create($fechaInicio, '1 week', $fechaFin)->toArray();
        $semanasStr = array_map(function ($date) {
            return $date->format('Y-W');
        }, $period);

        foreach ($period as $week) {
            $startOfWeek = $week->startOfWeek()->toDateString();
            $endOfWeek = $week->endOfWeek()->toDateString();

            // Obtener team leaders únicos para la semana actual
            $queryAQL = AuditoriaAQL::whereNotNull('team_leader')->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
            $queryProceso = AseguramientoCalidad::whereNotNull('team_leader')->whereBetween('created_at', [$startOfWeek, $endOfWeek]);

            if ($planta) {
                $queryAQL->where('planta', $planta);
                $queryProceso->where('planta', $planta);
            }

            $teamLeadersAQL = $queryAQL->pluck('team_leader');
            $teamLeadersProceso = $queryProceso->pluck('team_leader');
            $teamLeadersDelDia = $teamLeadersAQL->merge($teamLeadersProceso)->unique();

            $teamLeadersUnicos = $teamLeadersUnicos->merge($teamLeadersDelDia)->unique();

            foreach ($teamLeadersDelDia as $teamLeader) {
                // Inicializar los datos del team leader si no existen
                if (!isset($dataTeamLeader[$teamLeader])) {
                    $dataTeamLeader[$teamLeader] = [
                        'team_leader' => $teamLeader,
                        'semanas' => $semanasStr,
                        'porcentajesErrorAQL' => array_fill(0, count($semanasStr), 0),
                        'porcentajesErrorProceso' => array_fill(0, count($semanasStr), 0)
                    ];
                }

                // Obtener datos de AQL
                $sumaAuditadaAQL = AuditoriaAQL::where('team_leader', $teamLeader)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->sum('cantidad_auditada');
                $sumaRechazadaAQL = AuditoriaAQL::where('team_leader', $teamLeader)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->sum('cantidad_rechazada');

                $porcentajeErrorAQL = ($sumaAuditadaAQL != 0) ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100 : 0;

                // Obtener datos de Procesos
                $sumaAuditadaProceso = AseguramientoCalidad::where('team_leader', $teamLeader)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->sum('cantidad_auditada');
                $sumaRechazadaProceso = AseguramientoCalidad::where('team_leader', $teamLeader)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->sum('cantidad_rechazada');

                $porcentajeErrorProceso = ($sumaAuditadaProceso != 0) ? ($sumaRechazadaProceso / $sumaAuditadaProceso) * 100 : 0;

                // Encontrar el índice correspondiente a la semana
                $index = array_search($week->format('Y-W'), $semanasStr);

                // Agregar datos al array dataTeamLeader
                $dataTeamLeader[$teamLeader]['porcentajesErrorAQL'][$index] = $porcentajeErrorAQL;
                $dataTeamLeader[$teamLeader]['porcentajesErrorProceso'][$index] = $porcentajeErrorProceso;

                // Agregar también las claves porcentajeErrorProceso y porcentajeErrorAQL al nivel superior
                $dataTeamLeader[$teamLeader]['porcentajeErrorAQL'] = array_sum($dataTeamLeader[$teamLeader]['porcentajesErrorAQL']) / count($dataTeamLeader[$teamLeader]['porcentajesErrorAQL']);
                $dataTeamLeader[$teamLeader]['porcentajeErrorProceso'] = array_sum($dataTeamLeader[$teamLeader]['porcentajesErrorProceso']) / count($dataTeamLeader[$teamLeader]['porcentajesErrorProceso']);
            }
        }

        // Convertir dataTeamLeader a la estructura esperada
        $dataTeamLeader = array_values($dataTeamLeader);

        return [
            'teamLeadersUnicos' => $teamLeadersUnicos,
            'dataTeamLeader' => $dataTeamLeader,
        ];
    }

    private function calcularTotales($dataClientes, $fechaInicio, $fechaFin)
    {
        $totalAuditadaAQL = array_sum(array_map(function ($data) use ($fechaInicio, $fechaFin) {
            return AuditoriaAQL::where('cliente', $data['cliente'])->whereBetween('created_at', [$fechaInicio, $fechaFin])->sum('cantidad_auditada');
        }, $dataClientes));

        $totalRechazadaAQL = array_sum(array_map(function ($data) use ($fechaInicio, $fechaFin) {
            return AuditoriaAQL::where('cliente', $data['cliente'])->whereBetween('created_at', [$fechaInicio, $fechaFin])->sum('cantidad_rechazada');
        }, $dataClientes));

        $totalAuditadaProceso = array_sum(array_map(function ($data) use ($fechaInicio, $fechaFin) {
            return AseguramientoCalidad::where('cliente', $data['cliente'])->whereBetween('created_at', [$fechaInicio, $fechaFin])->sum('cantidad_auditada');
        }, $dataClientes));

        $totalRechazadaProceso = array_sum(array_map(function ($data) use ($fechaInicio, $fechaFin) {
            return AseguramientoCalidad::where('cliente', $data['cliente'])->whereBetween('created_at', [$fechaInicio, $fechaFin])->sum('cantidad_rechazada');
        }, $dataClientes));

        return [
            'totalPorcentajeErrorAQL' => ($totalAuditadaAQL != 0) ? ($totalRechazadaAQL / $totalAuditadaAQL) * 100 : 0,
            'totalPorcentajeErrorProceso' => ($totalAuditadaProceso != 0) ? ($totalRechazadaProceso / $totalAuditadaProceso) * 100 : 0,
        ];
    }

    private function getDataGerentesProduccionAQL($fechaInicio, $fechaFin, $planta = null)
    {
        $query = AuditoriaAQL::whereBetween('created_at', [$fechaInicio, $fechaFin]);

        if (!is_null($planta)) {
            $query->where('planta', $planta);
        }

        $gerentesAQL = $query->select('team_leader')
                            ->distinct()
                            ->pluck('team_leader')
                            ->all();

        $dataGerentesAQL = [];
        foreach ($gerentesAQL as $gerente) {
            $modulosUnicosAQL = AuditoriaAQL::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->select('modulo')
                ->distinct()
                ->get()
                ->pluck('modulo');

            $modulosUnicos = $modulosUnicosAQL->count();

            $sumaAuditadaAQL = AuditoriaAQL::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('cantidad_auditada');

            $sumaRechazadaAQL = AuditoriaAQL::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('cantidad_rechazada');

            $porcentajeErrorAQL = ($sumaAuditadaAQL != 0) ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100 : 0;

            $conteoOperario = AuditoriaAQL::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->distinct('nombre')
                ->count('nombre');

            $conteoMinutos = AuditoriaAQL::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->count('minutos_paro');

            $conteParoModular = AuditoriaAQL::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->count('minutos_paro_modular');

            $sumaMinutos = AuditoriaAQL::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('minutos_paro');

            $promedioMinutos = $conteoMinutos != 0 ? $sumaMinutos / $conteoMinutos : 0;
            $promedioMinutosEntero = ceil($promedioMinutos);

            $dataGerentesAQL[] = [
                'team_leader' => $gerente,
                'modulos_unicos' => $modulosUnicos,
                'porcentaje_error_aql' => $porcentajeErrorAQL,
                'conteoOperario' => $conteoOperario,
                'conteoMinutos' => $conteoMinutos,
                'sumaMinutos' => $sumaMinutos,
                'promedioMinutosEntero' => $promedioMinutosEntero,
                'conteParoModular' => $conteParoModular,
            ];
        }

        return $dataGerentesAQL;
    }

    private function getDataGerentesProduccionProceso($fechaInicio, $fechaFin, $planta = null)
    {
        $query = AseguramientoCalidad::whereBetween('created_at', [$fechaInicio, $fechaFin]);

        if (!is_null($planta)) {
            $query->where('planta', $planta);
        }

        $gerentesProceso = $query->select('team_leader')
                                ->distinct()
                                ->pluck('team_leader')
                                ->all();

        $dataGerentesProceso = [];
        foreach ($gerentesProceso as $gerente) {
            $modulosUnicosProceso = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->select('modulo')
                ->distinct()
                ->get()
                ->pluck('modulo');

            $modulosUnicos = $modulosUnicosProceso->count();

            $sumaAuditadaProceso = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('cantidad_auditada');

            $sumaRechazadaProceso = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('cantidad_rechazada');

            $porcentajeErrorProceso = ($sumaAuditadaProceso != 0) ? ($sumaRechazadaProceso / $sumaAuditadaProceso) * 100 : 0;

            $conteoOperario = AseguramientoCalidad::where('team_leader', $gerente)
                ->where('utility', null)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->distinct('nombre')
                ->count('nombre');

            $conteoUtility = AseguramientoCalidad::where('team_leader', $gerente)
                ->where('utility', 1)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->distinct('nombre')
                ->count('nombre');

            $conteoMinutos = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->count('minutos_paro');

            $sumaMinutos = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('minutos_paro');

            $promedioMinutos = $conteoMinutos != 0 ? $sumaMinutos / $conteoMinutos : 0;
            $promedioMinutosEntero = ceil($promedioMinutos);

            $dataGerentesProceso[] = [
                'team_leader' => $gerente,
                'modulos_unicos' => $modulosUnicos,
                'porcentaje_error_proceso' => $porcentajeErrorProceso,
                'conteoOperario' => $conteoOperario,
                'conteoUtility' => $conteoUtility,
                'conteoMinutos' => $conteoMinutos,
                'sumaMinutos' => $sumaMinutos,
                'promedioMinutosEntero' => $promedioMinutosEntero,
            ];
        }

        return $dataGerentesProceso;
    }

    private function combineDataGerentes($dataAQL, $dataProceso)
    {
        $combinedData = [];

        // Indexar datos de Proceso por team_leader
        $dataProcesoIndexed = [];
        foreach ($dataProceso as $item) {
            $dataProcesoIndexed[$item['team_leader']] = $item;
        }

        // Combinar datos
        foreach ($dataAQL as $itemAQL) {
            $teamLeader = $itemAQL['team_leader'];
            $itemProceso = $dataProcesoIndexed[$teamLeader] ?? null;

            $combinedData[] = [
                'team_leader' => $teamLeader,
                'porcentaje_error_aql' => $itemAQL['porcentaje_error_aql'],
                'porcentaje_error_proceso' => $itemProceso['porcentaje_error_proceso'] ?? null
            ];

            // Eliminar el entry del array indexado para evitar duplicados
            unset($dataProcesoIndexed[$teamLeader]);
        }

        // Agregar cualquier item de Proceso que no haya sido combinado
        foreach ($dataProcesoIndexed as $itemProceso) {
            $combinedData[] = [
                'team_leader' => $itemProceso['team_leader'],
                'porcentaje_error_aql' => null,
                'porcentaje_error_proceso' => $itemProceso['porcentaje_error_proceso']
            ];
        }

        return $combinedData;
    }

    private function getDataModuloAQL($fechaInicio, $fechaFin, $planta = null)
    {
        $query = AuditoriaAQL::whereBetween('created_at', [$fechaInicio, $fechaFin]);

        if (!is_null($planta)) {
            $query->where('planta', $planta);
        }

        $modulosAQL = $query->select('modulo')
                            ->distinct()
                            ->pluck('modulo')
                            ->all();

        $dataModuloAQL = [];
        foreach ($modulosAQL as $modulo) {
            $queryModulo = AuditoriaAQL::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin]);

            if (!is_null($planta)) {
                $queryModulo->where('planta', $planta);
            }

            $modulosUnicos = AuditoriaAQL::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->distinct()
                                ->count('modulo');

            $sumaAuditadaAQL = AuditoriaAQL::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->sum('cantidad_auditada');

            $sumaRechazadaAQL = AuditoriaAQL::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->sum('cantidad_rechazada');

            $porcentajeErrorAQL = ($sumaAuditadaAQL != 0) ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100 : 0;

            $conteoOperario = AuditoriaAQL::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->distinct()
                                ->count('nombre');

            $conteoMinutos = AuditoriaAQL::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->count('minutos_paro');

            $conteParoModular = AuditoriaAQL::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->count('minutos_paro_modular');

            $sumaMinutos = AuditoriaAQL::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->sum('minutos_paro');

            $promedioMinutos = $conteoMinutos != 0 ? $sumaMinutos / $conteoMinutos : 0;
            $promedioMinutosEntero = ceil($promedioMinutos);

            $dataModuloAQL[] = [
                'modulo' => $modulo,
                'modulos_unicos' => $modulosUnicos,
                'porcentaje_error_aql' => $porcentajeErrorAQL,
                'conteoOperario' => $conteoOperario,
                'conteoMinutos' => $conteoMinutos,
                'sumaMinutos' => $sumaMinutos,
                'promedioMinutosEntero' => $promedioMinutosEntero,
                'conteParoModular' => $conteParoModular,
            ];
        }

        return $dataModuloAQL;
    }

    private function getDataModuloProceso($fechaInicio, $fechaFin, $planta = null)
    {
        $query = AseguramientoCalidad::whereBetween('created_at', [$fechaInicio, $fechaFin]);

        if (!is_null($planta)) {
            $query->where('planta', $planta);
        }

        $modulosProceso = $query->select('modulo')
                                ->distinct()
                                ->pluck('modulo')
                                ->all();

        $dataModuloProceso = [];
        foreach ($modulosProceso as $modulo) {
            $queryModulo = AseguramientoCalidad::where('modulo', $modulo)
                                    ->whereBetween('created_at', [$fechaInicio, $fechaFin]);

            if (!is_null($planta)) {
                $queryModulo->where('planta', $planta);
            }

            $modulosUnicos = AseguramientoCalidad::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->distinct()
                                ->count('modulo');

            $sumaAuditadaProceso = AseguramientoCalidad::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->sum('cantidad_auditada');

            $sumaRechazadaProceso = AseguramientoCalidad::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->sum('cantidad_rechazada');

            $porcentajeErrorProceso = ($sumaAuditadaProceso != 0) ? ($sumaRechazadaProceso / $sumaAuditadaProceso) * 100 : 0;

            $conteoOperario = AseguramientoCalidad::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->where('utility', null)
                                ->distinct()
                                ->count('nombre');

            $conteoUtility = AseguramientoCalidad::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->where('utility', 1)
                                ->distinct()
                                ->count('nombre');

            $conteoMinutos = AseguramientoCalidad::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->count('minutos_paro');

            $sumaMinutos = AseguramientoCalidad::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->sum('minutos_paro');

            $promedioMinutos = $conteoMinutos != 0 ? $sumaMinutos / $conteoMinutos : 0;
            $promedioMinutosEntero = ceil($promedioMinutos);

            $dataModuloProceso[] = [
                'modulo' => $modulo,
                'modulos_unicos' => $modulosUnicos,
                'porcentaje_error_proceso' => $porcentajeErrorProceso,
                'conteoOperario' => $conteoOperario,
                'conteoUtility' => $conteoUtility,
                'conteoMinutos' => $conteoMinutos,
                'sumaMinutos' => $sumaMinutos,
                'promedioMinutosEntero' => $promedioMinutosEntero,
            ];
        }

        return $dataModuloProceso;
    }

    private function combineDataModulos($dataAQL, $dataProceso)
    {
        $combinedData = [];

        // Indexar datos de Proceso por modulo
        $dataProcesoIndexed = [];
        foreach ($dataProceso as $item) {
            $dataProcesoIndexed[$item['modulo']] = $item;
        }

        // Combinar datos
        foreach ($dataAQL as $itemAQL) {
            $modulo = $itemAQL['modulo'];
            $itemProceso = $dataProcesoIndexed[$modulo] ?? null;

            $combinedData[] = [
                'modulo' => $modulo,
                'porcentaje_error_aql' => $itemAQL['porcentaje_error_aql'],
                'porcentaje_error_proceso' => $itemProceso['porcentaje_error_proceso'] ?? null
            ];

            // Eliminar el entry del array indexado para evitar duplicados
            unset($dataProcesoIndexed[$modulo]);
        }

        // Agregar cualquier item de Proceso que no haya sido combinado
        foreach ($dataProcesoIndexed as $itemProceso) {
            $combinedData[] = [
                'modulo' => $itemProceso['modulo'],
                'porcentaje_error_aql' => null,
                'porcentaje_error_proceso' => $itemProceso['porcentaje_error_proceso']
            ];
        }

        return $combinedData;
    }



    public function detallePorGerente(Request $request)
    {
        $title = "";
        //dd($request->all());
        $rangoInicialShort = substr($request->fecha_inicio, 0, 19); // Obtener los primeros 19 caracteres
        $rangofinShort = substr($request->fecha_fin, 0, 19); // Obtener los primeros 19 caracteres
        //dd($request->all());
        // Obtener el nombre del mes en español
        $meses = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre'
        ];

        $rangoInicial = date('d', strtotime($rangoInicialShort)) . ' ' . $meses[date('n', strtotime($rangoInicialShort))] . ' ' . date('Y', strtotime($rangoInicialShort));
        $rangoFinal = date('d', strtotime($rangofinShort)) . ' ' . $meses[date('n', strtotime($rangofinShort))] . ' ' . date('Y', strtotime($rangofinShort));

        $gerenteProduccion = $request->team_leader;

        $modulosUnicosAQL = AuditoriaAQL::where('team_leader', $request->team_leader)
            ->whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])
            ->where('planta', 'Intimark1')
            ->select('modulo')
            ->distinct()
            ->get();

        $modulosUnicosProceso = AseguramientoCalidad::where('team_leader', $request->team_leader)
            ->whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])
            ->where('planta', 'Intimark1')
            ->select('modulo')
            ->distinct()
            ->get();

        $mostrarRegistroModulo = $modulosUnicosAQL->union($modulosUnicosProceso);

        //dd($mostrarRegistroModulo, $modulosUnicosProceso, $modulosUnicosAQL);

        $mostrarRegistroOperario = AseguramientoCalidad::whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])
            ->where('utility', null)
            ->where('planta', $request->planta)
            ->where('team_leader', $request->team_leader)
            ->select('nombre')
            ->distinct()
            ->get();

        $mostrarRegistroUtility = AseguramientoCalidad::whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])
            ->where('utility', 1)
            ->where('planta', $request->planta)
            ->where('team_leader', $request->team_leader)
            ->select('nombre')
            ->distinct()
            ->get();


        return view('dashboar.detallePorGerente', compact('title', 'mostrarRegistroModulo', 'rangoInicial', 'rangoFinal',
                                                        'gerenteProduccion', 'mostrarRegistroOperario', 'mostrarRegistroUtility'));
    }


    public function detallePorCliente(Request $request)
    {
        $title = "";
        //dd($request->all());
        $rangoInicialShort = substr($request->fecha_inicio, 0, 19); // Obtener los primeros 19 caracteres
        $rangofinShort = substr($request->fecha_fin, 0, 19); // Obtener los primeros 19 caracteres

        // Obtener el nombre del mes en español
        $meses = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre'
        ];

        $rangoInicial = date('d', strtotime($rangoInicialShort)) . ' ' . $meses[date('n', strtotime($rangoInicialShort))] . ' ' . date('Y', strtotime($rangoInicialShort));
        $rangoFinal = date('d', strtotime($rangofinShort)) . ' ' . $meses[date('n', strtotime($rangofinShort))] . ' ' . date('Y', strtotime($rangofinShort));

        $clienteSeleccionado = $request->cliente;


        $datosAQLPlanta1TurnoNormal = AuditoriaAQL::with('tpAuditoriaAQL')
            ->where('cliente', $request->cliente)
            ->whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])
            ->where('planta', 'Intimark1')
            ->where('tiempo_extra', null)
            ->get();

        $datosProcesoPlanta1TurnoNormal = AseguramientoCalidad::with('tpAseguramientoCalidad')
            ->where('cliente', $request->cliente)
            ->whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])
            ->where('planta', 'Intimark1')
            ->where('tiempo_extra', null)
            ->where('cantidad_rechazada', '>', 0) // Filtrar registros con cantidad_rechazada mayor a 0
            ->get();

        $conteoRechazos = $datosProcesoPlanta1TurnoNormal->where('cantidad_rechazada', '>', 0)->count();


        $modulosUnicosAQL = AuditoriaAQL::where('team_leader', $request->team_leader)
            ->whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])
            ->where('planta', 'Intimark1')
            ->select('modulo')
            ->distinct()
            ->get();

        $modulosUnicosProceso = AseguramientoCalidad::where('team_leader', $request->team_leader)
            ->whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])
            ->where('planta', 'Intimark1')
            ->select('modulo')
            ->distinct()
            ->get();

        $mostrarRegistroModulo = $modulosUnicosAQL->union($modulosUnicosProceso);

        //dd($mostrarRegistroModulo, $modulosUnicosProceso, $modulosUnicosAQL);

        $mostrarRegistroOperario = AseguramientoCalidad::whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])
            ->where('utility', null)
            ->where('planta', $request->planta)
            ->where('team_leader', $request->team_leader)
            ->select('nombre')
            ->distinct()
            ->get();

        $mostrarRegistroUtility = AseguramientoCalidad::whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])
            ->where('utility', 1)
            ->where('planta', $request->planta)
            ->where('team_leader', $request->team_leader)
            ->select('nombre')
            ->distinct()
            ->get();


        return view('dashboar.detallePorCliente', compact('title', 'mostrarRegistroModulo', 'rangoInicial', 'rangoFinal',
                                                        'clienteSeleccionado', 'datosAQLPlanta1TurnoNormal', 'datosProcesoPlanta1TurnoNormal',
                                                        'conteoRechazos'));
    }


    public function buscadorDinamico()
    {


        return view('dashboar.buscadorDinamico');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $results = [];

        if ($query) {
            try {
                // Inicializa arrays para rastrear en qué columnas y modelos se encontraron los resultados
                $aseguramientoCalidadColumns = [];
                $auditoriaAQLColumns = [];

                // Buscar en AseguramientoCalidad
                $aseguramientoCalidad = AseguramientoCalidad::where('nombre', 'like', "%{$query}%")
                    ->orWhere('cliente', 'like', "%{$query}%")
                    ->orWhere('team_leader', 'like', "%{$query}%")
                    ->orWhere('modulo', 'like', "%{$query}%")
                    ->orWhere('auditor', 'like', "%{$query}%")
                    ->get();

                foreach ($aseguramientoCalidad as $item) {
                    if (stripos($item->nombre, $query) !== false && !in_array('nombre', $aseguramientoCalidadColumns)) {
                        $aseguramientoCalidadColumns[] = 'nombre';
                    }
                    if (stripos($item->cliente, $query) !== false && !in_array('cliente', $aseguramientoCalidadColumns)) {
                        $aseguramientoCalidadColumns[] = 'cliente';
                    }
                    if (stripos($item->team_leader, $query) !== false && !in_array('team_leader', $aseguramientoCalidadColumns)) {
                        $aseguramientoCalidadColumns[] = 'team_leader';
                    }
                    if (stripos($item->modulo, $query) !== false && !in_array('modulo', $aseguramientoCalidadColumns)) {
                        $aseguramientoCalidadColumns[] = 'modulo';
                    }
                    if (stripos($item->auditor, $query) !== false && !in_array('auditor', $aseguramientoCalidadColumns)) {
                        $aseguramientoCalidadColumns[] = 'auditor';
                    }
                }

                if (!empty($aseguramientoCalidadColumns)) {
                    $results[] = [
                        'model' => 'AseguramientoCalidad',
                        'found_in' => 'Auditoria Proceso',
                        'columns' => $aseguramientoCalidadColumns
                    ];
                }

                // Buscar en AuditoriaAQL
                $auditoriaAQL = AuditoriaAQL::where('nombre', 'like', "%{$query}%")
                    ->orWhere('auditor', 'like', "%{$query}%")
                    ->orWhere('team_leader', 'like', "%{$query}%")
                    ->orWhere('cliente', 'like', "%{$query}%")
                    ->orWhere('modulo', 'like', "%{$query}%")
                    ->get();

                foreach ($auditoriaAQL as $item) {
                    if (stripos($item->nombre, $query) !== false && !in_array('nombre', $auditoriaAQLColumns)) {
                        $auditoriaAQLColumns[] = 'nombre';
                    }
                    if (stripos($item->auditor, $query) !== false && !in_array('auditor', $auditoriaAQLColumns)) {
                        $auditoriaAQLColumns[] = 'auditor';
                    }
                    if (stripos($item->team_leader, $query) !== false && !in_array('team_leader', $auditoriaAQLColumns)) {
                        $auditoriaAQLColumns[] = 'team_leader';
                    }
                    if (stripos($item->cliente, $query) !== false && !in_array('cliente', $auditoriaAQLColumns)) {
                        $auditoriaAQLColumns[] = 'cliente';
                    }
                    if (stripos($item->modulo, $query) !== false && !in_array('modulo', $auditoriaAQLColumns)) {
                        $auditoriaAQLColumns[] = 'modulo';
                    }
                }

                if (!empty($auditoriaAQLColumns)) {
                    $results[] = [
                        'model' => 'AuditoriaAQL',
                        'found_in' => 'Auditoria AQL',
                        'columns' => $auditoriaAQLColumns
                    ];
                }
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        return response()->json($results);
    }

    public function detalleXModulo(Request $request)
    {
        $title = "";
        $clienteBusqueda = $request->input('clienteBusqueda');
        if ($request->fecha_fin) {
            $fechaInicio = Carbon::parse($request->input('fecha_inicio'))->startOfWeek();
            $fechaFin = Carbon::parse($request->input('fecha_fin'))->endOfWeek();
        } else {
            $fechaFin = Carbon::now()->endOfWeek();
            $fechaInicio = $fechaFin->copy()->subWeeks(2)->startOfWeek();
        }

        // Obtener las semanas en el rango
        $semanas = collect();
        $currentWeek = $fechaInicio->copy();
        while ($currentWeek <= $fechaFin) {
            $semanas->push($currentWeek->format('Y-W')); // Formato Año-Semana
            $currentWeek->addWeek();
        }

        // Consultas en los dos modelos
        $auditoriasAQL = AuditoriaAQL::where('cliente', $clienteBusqueda)
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->get();

        $audtidoriaProceso = AseguramientoCalidad::where('cliente', $clienteBusqueda)
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->get();

        // Agrupar los datos por módulo y semana, y realizar cálculos
        $datosCombinados = [];
        $promediosGenerales = [];

        foreach ($semanas as $semana) {
            $promediosGenerales[$semana] = [
                'total_rechazada_AQL' => 0,
                'total_auditada_AQL' => 0,
                'total_rechazada_Proceso' => 0,
                'total_auditada_Proceso' => 0
            ];
        }

        foreach ($auditoriasAQL as $item) {
            $semana = Carbon::parse($item->created_at)->format('Y-W');
            $modulo = $item->modulo;
            if (!isset($datosCombinados[$modulo])) {
                $datosCombinados[$modulo] = [
                    'semanas' => [],
                    'cantidad_total_rechazada_AQL' => 0,
                    'cantidad_total_auditada_AQL' => 0,
                    'cantidad_total_rechazada_Proceso' => 0,
                    'cantidad_total_auditada_Proceso' => 0
                ];
            }
            if (!isset($datosCombinados[$modulo]['semanas'][$semana])) {
                $datosCombinados[$modulo]['semanas'][$semana] = [
                    'cantidad_rechazada_AQL' => 0,
                    'cantidad_auditada_AQL' => 0,
                    'cantidad_rechazada_Proceso' => 0,
                    'cantidad_auditada_Proceso' => 0
                ];
            }
            $datosCombinados[$modulo]['semanas'][$semana]['cantidad_rechazada_AQL'] += $item->cantidad_rechazada;
            $datosCombinados[$modulo]['semanas'][$semana]['cantidad_auditada_AQL'] += $item->cantidad_auditada;
            $datosCombinados[$modulo]['cantidad_total_rechazada_AQL'] += $item->cantidad_rechazada;
            $datosCombinados[$modulo]['cantidad_total_auditada_AQL'] += $item->cantidad_auditada;

            $promediosGenerales[$semana]['total_rechazada_AQL'] += $item->cantidad_rechazada;
            $promediosGenerales[$semana]['total_auditada_AQL'] += $item->cantidad_auditada;
        }

        foreach ($audtidoriaProceso as $item) {
            $semana = Carbon::parse($item->created_at)->format('Y-W');
            $modulo = $item->modulo;
            if (!isset($datosCombinados[$modulo])) {
                $datosCombinados[$modulo] = [
                    'semanas' => [],
                    'cantidad_total_rechazada_AQL' => 0,
                    'cantidad_total_auditada_AQL' => 0,
                    'cantidad_total_rechazada_Proceso' => 0,
                    'cantidad_total_auditada_Proceso' => 0
                ];
            }
            if (!isset($datosCombinados[$modulo]['semanas'][$semana])) {
                $datosCombinados[$modulo]['semanas'][$semana] = [
                    'cantidad_rechazada_AQL' => 0,
                    'cantidad_auditada_AQL' => 0,
                    'cantidad_rechazada_Proceso' => 0,
                    'cantidad_auditada_Proceso' => 0
                ];
            }
            $datosCombinados[$modulo]['semanas'][$semana]['cantidad_rechazada_Proceso'] += $item->cantidad_rechazada;
            $datosCombinados[$modulo]['semanas'][$semana]['cantidad_auditada_Proceso'] += $item->cantidad_auditada;
            $datosCombinados[$modulo]['cantidad_total_rechazada_Proceso'] += $item->cantidad_rechazada;
            $datosCombinados[$modulo]['cantidad_total_auditada_Proceso'] += $item->cantidad_auditada;

            $promediosGenerales[$semana]['total_rechazada_Proceso'] += $item->cantidad_rechazada;
            $promediosGenerales[$semana]['total_auditada_Proceso'] += $item->cantidad_auditada;
        }

        return view('dashboar.detalleXModulo', compact('title', 'clienteBusqueda', 'semanas', 'datosCombinados', 'promediosGenerales'));
    }




}
