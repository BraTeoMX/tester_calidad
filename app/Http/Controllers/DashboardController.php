<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\AuditoriaProcesoCorte; 
use App\Models\AseguramientoCalidad;  
use App\Models\TpAseguramientoCalidad; 
use App\Models\TpAuditoriaAQL; 
use App\Models\AuditoriaAQL;   
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
    public function dashboarAProceso()
    {
        $title = "";

        $clientes = AuditoriaProcesoCorte::whereNotNull('cliente')
            ->orderBy('cliente')
            ->pluck('cliente')
            ->unique();
        $porcentajesError = [];

        foreach ($clientes as $cliente) {
            $sumaAuditada = AuditoriaProcesoCorte::where('cliente', $cliente)->sum('cantidad_auditada');
            $sumaRechazada = AuditoriaProcesoCorte::where('cliente', $cliente)->sum('cantidad_rechazada');

            if ($sumaAuditada != 0) {
                $porcentajeError = ($sumaRechazada / $sumaAuditada) * 100;
            } else {
                $porcentajeError = 0;
            }

            $porcentajesError[$cliente] = $porcentajeError;
        }
        
        return view('dashboar.dashboarAProceso', compact('title', 'clientes', 'porcentajesError'));
    }

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

        return view('dashboar.dashboarAProcesoAQL', compact('title', 'semanas', 'porcentajesAQL', 'porcentajesProceso',
        'semanasGrafica', 'datasetsAQL', 'datasetsProceso', 'clientesGrafica'));
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
            }
        }

        // Convertir dataCliente a la estructura esperada
        $dataCliente = array_values($dataCliente);

        return [
            'clientesUnicos' => $clientesUnicos,
            'dataCliente' => $dataCliente
        ];
    }


    public function detalleXModuloAQL(Request $request)
    {
        $title = "";
        //dd($request->all());
        $rangoInicialShort = substr($request->fecha_inicio, 0, 19); // Obtener los primeros 19 caracteres
        $rangofinShort = substr($request->fecha_fin, 0, 19); // Obtener los primeros 19 caracteres

        $nombreModulo = $request->modulo;

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


        $mostrarRegistro = AuditoriaAQL::whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])
            ->where('modulo', $request->modulo)
            ->where('op', $request->op)
            ->where('team_leader', $request->team_leader)
            ->get();

        // Actualiza las consultas para los datos que necesitas mostrar en la vista
        $registrosIndividual = AuditoriaAQL::whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])
            ->where('modulo', $request->modulo)
            ->where('op', $request->op)
            ->where('team_leader', $request->team_leader)
            ->selectRaw('COALESCE(SUM(cantidad_auditada), 0) as total_auditada, COALESCE(SUM(cantidad_rechazada), 0) as total_rechazada')
            ->get();

        $registrosIndividualPieza = AuditoriaAQL::whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])
            ->where('modulo', $request->modulo)
            ->where('op', $request->op)
            ->where('team_leader', $request->team_leader)
            ->selectRaw('SUM(pieza) as total_pieza, SUM(cantidad_rechazada) as total_rechazada')
            ->get();

        $conteoBultos = AuditoriaAQL::whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])
            ->where('modulo', $request->modulo)
            ->where('op', $request->op)
            ->where('team_leader', $request->team_leader)
            ->count();

        $conteoPiezaConRechazo = AuditoriaAQL::whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])
            ->where('modulo', $request->modulo)
            ->where('op', $request->op)
            ->where('team_leader', $request->team_leader)
            ->where('cantidad_rechazada', '>', 0)
            ->count('pieza');

        $porcentajeBulto = $conteoBultos != 0 ? ($conteoPiezaConRechazo / $conteoBultos) * 100 : 0;

        return view('dashboar.detalleXModuloAQL', compact('title', 'mostrarRegistro', 'rangoInicial', 'rangoFinal', 
                'registrosIndividual', 'registrosIndividualPieza', 'conteoBultos', 'conteoPiezaConRechazo', 'porcentajeBulto',
                'nombreModulo'));
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


}
