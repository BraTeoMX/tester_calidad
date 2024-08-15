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


class DashboardPlanta1DetalleController extends Controller
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

    public function dashboardPlanta1Detalle(Request $request)
    {
        $title = "";
        // Definir el valor de planta
        $planta = "Intimark1";
        if($request->fecha_fin){
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
            return $this->calcularPorcentajePorSemana(AuditoriaAQL::class, $year, $week, 'Intimark1');
        });

        $porcentajesProceso = $semanas->map(function($semana) {
            list($year, $week) = explode('-', $semana);
            return $this->calcularPorcentajePorSemana(AseguramientoCalidad::class, $year, $week, 'Intimark1');
        });
        // Datos para las gráficas de clientes
        $dataGrafica = $this->obtenerDatosClientesPorRangoFechas($fechaInicio, $fechaFin, 'Intimark1');
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

        // Datos generales
        $dataGeneral = $this->obtenerDatosClientesPorRangoFechas($fechaInicio, $fechaFin, $planta);
        $totalGeneral = $this->calcularTotales($dataGeneral['dataCliente'], $fechaInicio, $fechaFin);
        //dd($dataGeneral, $totalGeneral);

        // Datos para gerentes de producción
        $dataGerentesAQLGeneral = $this->getDataGerentesProduccionAQL($fechaInicio, $fechaFin, 'Intimark1');
        $dataGerentesProcesoGeneral = $this->getDataGerentesProduccionProceso($fechaInicio, $fechaFin, 'Intimark1');
        $dataGerentesGeneral = $this->combineDataGerentes($dataGerentesAQLGeneral, $dataGerentesProcesoGeneral);

        // Datos para módulos
        $dataModuloAQLGeneral = $this->getDataModuloAQL($fechaInicio, $fechaFin, 'Intimark1');
        $dataModuloProcesoGeneral = $this->getDataModuloProceso($fechaInicio, $fechaFin, 'Intimark1');
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

        // Obtener el nombre del mes en español
        $mesesEnEspanol = [
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

        // Formatear la fecha con el nombre del mes en español
        $fechaInicioFormateada = $diaInicio . ' de ' . $mesesEnEspanol[$mesInicio] . ' ' . $añoInicio;
        $fechaFinFormateada = $diaFin . ' de ' . $mesesEnEspanol[$mesFin] . ' ' . $añoFin;
        //dd($fechaInicioFormateada, $fechaFinFormateada);

        // Datos para las gráficas de módulos
        $dataGraficaModulos = $this->obtenerDatosModulosPorRangoFechas($fechaInicio, $fechaFin);
        $modulosGrafica = collect($dataGraficaModulos['modulosUnicos'])->toArray();
        $semanasGraficaModulos = collect($dataGraficaModulos['dataModulo'][0]['semanas'])->toArray();

        $datasetsAQLModulos = collect($dataGraficaModulos['dataModulo'])->map(function ($moduloData) {
            return [
                'label' => $moduloData['modulo'],
                'data' => $moduloData['porcentajesErrorAQL'],
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'borderWidth' => 1,
                'fill' => false
            ];
        })->toArray();

        $datasetsProcesoModulos = collect($dataGraficaModulos['dataModulo'])->map(function ($moduloData) {
            return [
                'label' => $moduloData['modulo'],
                'data' => $moduloData['porcentajesErrorProceso'],
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

        // Añade esto después de calcular $dataGeneral, $dataGraficaModulos, y $dataGraficaSupervisor
        $detallesClientes = $this->obtenerDetallesClientes($clientesGrafica, $fechaInicio, $fechaFin);
        $detallesModulos = $this->obtenerDetallesModulos($modulosGrafica, $fechaInicio, $fechaFin);
        $detallesSupervisores = $this->obtenerDetallesSupervisores($teamLeadersGrafica, $fechaInicio, $fechaFin);

        

        return view('dashboar.dashboardPlanta1Detalle', compact('title', 'semanas', 'porcentajesAQL', 'porcentajesProceso',
            'semanasGrafica', 'datasetsAQL', 'datasetsProceso', 'clientesGrafica', 'dataGeneral', 'totalGeneral',
            'dataGerentesGeneral', 'dataModulosGeneral', 'dataModuloAQLPlanta1', 'dataModuloAQLPlanta2',
            'dataModuloProcesoPlanta1', 'dataModuloProcesoPlanta2', 'topDefectosAQL', 'topDefectosProceso',
            'fechaInicio', 'fechaFin', 'dataModuloAQLGeneral', 'dataModuloProcesoGeneral',
            'fechaInicioFormateada', 'fechaFinFormateada', 'modulosGrafica', 'teamLeadersGrafica',
            'semanasGraficaModulos', 'datasetsAQLModulos', 'datasetsProcesoModulos', 'datasetsAQLModulos', 'datasetsProcesoModulos',
            'datasetsAQLSupervisor', 'datasetsProcesoSupervisor',
            'detallesClientes', 'detallesModulos', 'detallesSupervisores'));
    }

    private function obtenerDetallesClientes($clientesGrafica, $fechaInicio, $fechaFin)
    {
        $detalles = [];
        foreach ($clientesGrafica as $cliente) {
            $detallesAQL = AuditoriaAQL::where('cliente', $cliente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->get();
            $detallesProceso = AseguramientoCalidad::where('cliente', $cliente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->get();
            $detalles[$cliente] = [
                'aql' => $detallesAQL,
                'proceso' => $detallesProceso
            ];
        }
        return $detalles;
    }

    private function obtenerDetallesModulos($modulosGrafica, $fechaInicio, $fechaFin)
    {
        $detalles = [];
        foreach ($modulosGrafica as $modulo) {
            $detallesAQL = AuditoriaAQL::where('modulo', $modulo)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->get();
            $detallesProceso = AseguramientoCalidad::where('modulo', $modulo)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->get();
            $detalles[$modulo] = [
                'aql' => $detallesAQL,
                'proceso' => $detallesProceso
            ];
        }
        return $detalles;
    }

    private function obtenerDetallesSupervisores($teamLeadersGrafica, $fechaInicio, $fechaFin)
    {
        $detalles = [];
        foreach ($teamLeadersGrafica as $supervisor) {
            $detallesAQL = AuditoriaAQL::where('team_leader', $supervisor)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->get();
            $detallesProceso = AseguramientoCalidad::where('team_leader', $supervisor)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->get();
            $detalles[$supervisor] = [
                'aql' => $detallesAQL,
                'proceso' => $detallesProceso
            ];
        }
        //dd($teamLeadersGrafica, $fechaInicio, $fechaFin, $detalles);
        return $detalles;
    }

    private function calcularPorcentajePorSemana($modelo, $year, $week, $planta)
    {
        $startOfWeek = Carbon::now()->setISODate($year, $week)->startOfWeek();
        $endOfWeek = $startOfWeek->copy()->endOfWeek();
        $data = $modelo::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->where('planta', $planta)
                    ->selectRaw('SUM(cantidad_auditada) as cantidad_auditada, SUM(cantidad_rechazada) as cantidad_rechazada')
                    ->first();
        return $data->cantidad_auditada != 0 ? number_format(($data->cantidad_rechazada / $data->cantidad_auditada) * 100, 2) : 0;
    }

    private function obtenerDatosClientesPorRangoFechas($fechaInicio, $fechaFin, $planta = 'Intimark1')
    {
        $clientesUnicos = collect();
        $dataCliente = [];

        $period = CarbonPeriod::create($fechaInicio, '1 week', $fechaFin)->toArray();
        $semanasStr = array_map(function ($date) {
            return $date->format('Y-W');
        }, $period);

        foreach ($period as $week) {
            $startOfWeek = $week->startOfWeek()->toDateString();
            $endOfWeek = $week->endOfWeek()->toDateString();

            $queryAQL = AuditoriaAQL::whereNotNull('cliente')
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->when($planta, function ($query) use ($planta) {
                    return $query->where('planta', $planta);
                });

            $queryProceso = AseguramientoCalidad::whereNotNull('cliente')
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->when($planta, function ($query) use ($planta) {
                    return $query->where('planta', $planta);
                });

            $clientesAQL = $queryAQL->pluck('cliente');
            $clientesProceso = $queryProceso->pluck('cliente');

            $clientesDelDia = $clientesAQL->merge($clientesProceso)->unique();
            $clientesUnicos = $clientesUnicos->merge($clientesDelDia)->unique();

            foreach ($clientesDelDia as $cliente) {
                if (!isset($dataCliente[$cliente])) {
                    $dataCliente[$cliente] = [
                        'cliente' => $cliente,
                        'semanas' => $semanasStr,
                        'porcentajesErrorAQL' => array_fill(0, count($semanasStr), 0),
                        'porcentajesErrorProceso' => array_fill(0, count($semanasStr), 0)
                    ];
                }

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

                $index = array_search($week->format('Y-W'), $semanasStr);

                $dataCliente[$cliente]['porcentajesErrorAQL'][$index] = $porcentajeErrorAQL;
                $dataCliente[$cliente]['porcentajesErrorProceso'][$index] = $porcentajeErrorProceso;

                // Asegurar que los índices se calculen correctamente
                $dataCliente[$cliente]['porcentajeErrorAQL'] = array_sum($dataCliente[$cliente]['porcentajesErrorAQL']) / count($dataCliente[$cliente]['porcentajesErrorAQL']);
                $dataCliente[$cliente]['porcentajeErrorProceso'] = array_sum($dataCliente[$cliente]['porcentajesErrorProceso']) / count($dataCliente[$cliente]['porcentajesErrorProceso']);
            }
        }

        $dataCliente = array_values($dataCliente);

        return [
            'clientesUnicos' => $clientesUnicos,
            'dataCliente' => $dataCliente,
        ];
    }


    private function obtenerDatosModulosPorRangoFechas($fechaInicio, $fechaFin, $planta = 'Intimark1')
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

    private function obtenerDatosTeamLeaderPorRangoFechas($fechaInicio, $fechaFin, $planta = 'Intimark1')
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
            return AuditoriaAQL::where('cliente', $data['cliente'])->where('planta', 'Intimark1')->whereBetween('created_at', [$fechaInicio, $fechaFin])->sum('cantidad_auditada');
        }, $dataClientes));

        $totalRechazadaAQL = array_sum(array_map(function ($data) use ($fechaInicio, $fechaFin) {
            return AuditoriaAQL::where('cliente', $data['cliente'])->where('planta', 'Intimark1')->whereBetween('created_at', [$fechaInicio, $fechaFin])->sum('cantidad_rechazada');
        }, $dataClientes));

        $totalAuditadaProceso = array_sum(array_map(function ($data) use ($fechaInicio, $fechaFin) {
            return AseguramientoCalidad::where('cliente', $data['cliente'])->where('planta', 'Intimark1')->whereBetween('created_at', [$fechaInicio, $fechaFin])->sum('cantidad_auditada');
        }, $dataClientes));

        $totalRechazadaProceso = array_sum(array_map(function ($data) use ($fechaInicio, $fechaFin) {
            return AseguramientoCalidad::where('cliente', $data['cliente'])->where('planta', 'Intimark1')->whereBetween('created_at', [$fechaInicio, $fechaFin])->sum('cantidad_rechazada');
        }, $dataClientes));

        return [
            'totalPorcentajeErrorAQL' => ($totalAuditadaAQL != 0) ? ($totalRechazadaAQL / $totalAuditadaAQL) * 100 : 0,
            'totalPorcentajeErrorProceso' => ($totalAuditadaProceso != 0) ? ($totalRechazadaProceso / $totalAuditadaProceso) * 100 : 0,
        ];
    }

    private function getDataGerentesProduccionAQL($fechaInicio, $fechaFin, $planta = null)
    {
        $query = AuditoriaAQL::whereBetween('created_at', [$fechaInicio, $fechaFin])->where('planta', $planta);

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
                ->where('planta', $planta)
                ->select('modulo')
                ->distinct()
                ->get()
                ->pluck('modulo');

            $modulosUnicos = $modulosUnicosAQL->count();

            $sumaAuditadaAQL = AuditoriaAQL::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', $planta)
                ->sum('cantidad_auditada');

            $sumaRechazadaAQL = AuditoriaAQL::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', $planta)
                ->sum('cantidad_rechazada');

            $porcentajeErrorAQL = ($sumaAuditadaAQL != 0) ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100 : 0;

            $conteoOperario = AuditoriaAQL::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', $planta)
                ->distinct('nombre')
                ->count('nombre');

            $conteoMinutos = AuditoriaAQL::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', $planta)
                ->count('minutos_paro');

            $conteParoModular = AuditoriaAQL::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', $planta)
                ->count('minutos_paro_modular');

            $sumaMinutos = AuditoriaAQL::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', $planta)
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
        $query = AseguramientoCalidad::whereBetween('created_at', [$fechaInicio, $fechaFin])->where('planta', $planta);

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
                ->where('planta', $planta)
                ->select('modulo')
                ->distinct()
                ->get()
                ->pluck('modulo');

            $modulosUnicos = $modulosUnicosProceso->count();

            $sumaAuditadaProceso = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', $planta)
                ->sum('cantidad_auditada');

            $sumaRechazadaProceso = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', $planta)
                ->sum('cantidad_rechazada');

            $porcentajeErrorProceso = ($sumaAuditadaProceso != 0) ? ($sumaRechazadaProceso / $sumaAuditadaProceso) * 100 : 0;

            $conteoOperario = AseguramientoCalidad::where('team_leader', $gerente)
                ->where('utility', null)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', $planta)
                ->distinct('nombre')
                ->count('nombre');

            $conteoUtility = AseguramientoCalidad::where('team_leader', $gerente)
                ->where('utility', 1)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', $planta)
                ->distinct('nombre')
                ->count('nombre');

            $conteoMinutos = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', $planta)
                ->count('minutos_paro');

            $sumaMinutos = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', $planta)
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
        $query = AuditoriaAQL::whereBetween('created_at', [$fechaInicio, $fechaFin])->where('planta', $planta);

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
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->where('planta', $planta);

            if (!is_null($planta)) {
                $queryModulo->where('planta', $planta);
            }

            $modulosUnicos = AuditoriaAQL::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->where('planta', $planta)
                                ->distinct()
                                ->count('modulo');

            $sumaAuditadaAQL = AuditoriaAQL::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->where('planta', $planta)
                                ->sum('cantidad_auditada');

            $sumaRechazadaAQL = AuditoriaAQL::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->where('planta', $planta)
                                ->sum('cantidad_rechazada');

            $porcentajeErrorAQL = ($sumaAuditadaAQL != 0) ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100 : 0;

            $conteoOperario = AuditoriaAQL::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->where('planta', $planta)
                                ->distinct()
                                ->count('nombre');

            $conteoMinutos = AuditoriaAQL::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->where('planta', $planta)
                                ->count('minutos_paro');

            $conteParoModular = AuditoriaAQL::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->where('planta', $planta)
                                ->count('minutos_paro_modular');

            $sumaMinutos = AuditoriaAQL::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->where('planta', $planta)
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
        $query = AseguramientoCalidad::whereBetween('created_at', [$fechaInicio, $fechaFin])->where('planta', $planta);

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
                                    ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                    ->where('planta', $planta);

            if (!is_null($planta)) {
                $queryModulo->where('planta', $planta);
            }

            $modulosUnicos = AseguramientoCalidad::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->where('planta', $planta)
                                ->distinct()
                                ->count('modulo');

            $sumaAuditadaProceso = AseguramientoCalidad::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->where('planta', $planta)
                                ->sum('cantidad_auditada');

            $sumaRechazadaProceso = AseguramientoCalidad::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->where('planta', $planta)
                                ->sum('cantidad_rechazada');

            $porcentajeErrorProceso = ($sumaAuditadaProceso != 0) ? ($sumaRechazadaProceso / $sumaAuditadaProceso) * 100 : 0;

            $conteoOperario = AseguramientoCalidad::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->where('planta', $planta)
                                ->where('utility', null)
                                ->distinct()
                                ->count('nombre');

            $conteoUtility = AseguramientoCalidad::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->where('planta', $planta)
                                ->where('utility', 1)
                                ->distinct()
                                ->count('nombre');

            $conteoMinutos = AseguramientoCalidad::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->where('planta', $planta)
                                ->count('minutos_paro');

            $sumaMinutos = AseguramientoCalidad::where('modulo', $modulo)
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                                ->where('planta', $planta)
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


    public function Top3Defectos(Request $request)
    {
        $tipoModelo = $request->input('tipo');

        if (!in_array($tipoModelo, ['TpAuditoriaAQL', 'TpAseguramientoCalidad'])) {
            return Response::json(['success' => false, 'error' => 'Tipo de modelo inválido'], 400);
        }

        $modelo = $tipoModelo === 'TpAuditoriaAQL' ? TpAuditoriaAQL::query() : TpAseguramientoCalidad::query();

        // Consulta para usar la columna 'tp'
        $defectos = $modelo->select('tp AS defecto', DB::raw('COUNT(*) as cantidad'))
            ->groupBy('tp')
            ->orderByDesc('cantidad')
            ->limit(3)
            ->get();

        // Formateo de resultados (ajustado para usar 'defecto' como alias)
        $data = [];
        foreach ($defectos as $defecto) {
            $data[] = [
                'defecto' => $defecto->defecto, // Usamos el alias 'defecto'
                'cantidad' => $defecto->cantidad
            ];
        }

        return Response::json(['success' => true, 'data' => $data]);
    }

    public function detalleXModuloPlanta1(Request $request)
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
            ->where('planta', 'Intimark1')
            ->get();

        $audtidoriaProceso = AseguramientoCalidad::where('cliente', $clienteBusqueda)
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('planta', 'Intimark1')
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

        return view('dashboar.detalleXModuloPlanta1', compact('title', 'clienteBusqueda', 'semanas', 'datosCombinados', 'promediosGenerales'));
    }

    
}
