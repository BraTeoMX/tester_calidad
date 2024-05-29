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
            $fechaInicio1 = $request->input('fecha_inicio', Carbon::now()->format('Y-m-d') . ' 00:00:00');
            $fechaInicio = $fechaInicio1 . ' 00:00:00';
            $fechaFin1 = $request->input('fecha_fin', Carbon::now()->format('Y-m-d') . ' 23:59:59');
            $fechaFin = $fechaFin1 . ' 23:59:59';
            $fechaActual = Carbon::now()->toDateString();

            // Convertir a instancias de Carbon
            $fechaInicio = Carbon::parse($fechaInicio);
            $fechaFin = Carbon::parse($fechaFin);
            
            // Obtener el rango de semanas
            $startWeek = $fechaInicio->copy()->startOfWeek();
            $endWeek = $fechaFin->copy()->endOfWeek();
            
            $semanas = collect();
            $currentWeek = $startWeek->copy();

            while ($currentWeek <= $endWeek) {
                $semanas->push($currentWeek->format('Y-W')); // Formato Año-Semana
                $currentWeek->addWeek();
            }
        }else{
            $fechaActual = Carbon::now()->toDateString();
            $fechaInicio = Carbon::now()->subDays(15)->toDateString(); // Cambia el rango de fechas según necesites
            $fechaFin = Carbon::now()->toDateString();
        }
        
        //dd($fechaInicio, $fechaFin);
        //dd($request->all());
        // Obtener el rango de fechas
        $fechas = collect();
        $period = Carbon::parse($fechaInicio)->daysUntil(Carbon::parse($fechaFin));
        foreach ($period as $date) {
            $fechas->push($date->format('Y-m-d'));
        }


        // Función para calcular el porcentaje de error
        function calcularPorcentaje($modelo, $fecha) {
            $data = $modelo::whereDate('created_at', $fecha)
                        ->selectRaw('SUM(cantidad_auditada) as cantidad_auditada, SUM(cantidad_rechazada) as cantidad_rechazada')
                        ->first();
            return $data->cantidad_auditada != 0 ? number_format(($data->cantidad_rechazada / $data->cantidad_auditada) * 100, 2) : 0;
        }

        // Calcular porcentajes AQL y Proceso para cada fecha
        $porcentajesAQL = $fechas->map(function($fecha) {
            return calcularPorcentaje(AuditoriaAQL::class, $fecha);
        });
        $porcentajesProceso = $fechas->map(function($fecha) {
            return calcularPorcentaje(AseguramientoCalidad::class, $fecha);
        });

        // Datos para las gráficas de clientes
        $dataGrafica = $this->obtenerDatosClientesPorRangoFechas($fechaInicio, $fechaFin);
        $clientesGrafica = collect($dataGrafica['clientesUnicos'])->toArray();
        $fechasGrafica = collect($dataGrafica['dataCliente'][0]['fechas'])->toArray();
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

        //dd($gerentesProduccionAQL, $gerentesProduccionProceso, $gerentesProduccion, $data);
        return view('dashboar.dashboarAProcesoAQL', compact('title', 'fechas', 'porcentajesAQL', 'porcentajesProceso',
        'fechasGrafica', 'datasetsAQL', 'datasetsProceso', 'clientesGrafica'));

    }


    private function obtenerDatosClientesPorRangoFechas($fechaInicio, $fechaFin, $planta = null)
    {
        $clientesUnicos = collect();
        $dataCliente = [];

        // Iterar sobre cada día en el rango
        $fechas = CarbonPeriod::create($fechaInicio, '1 day', $fechaFin)->toArray();
        $fechasStr = array_map(function ($fecha) {
            return $fecha->toDateString();
        }, $fechas);

        foreach ($fechas as $fecha) {
            $fechaStr = $fecha->toDateString();

            // Obtener clientes únicos para la fecha actual
            $queryAQL = AuditoriaAQL::whereNotNull('cliente')->whereDate('created_at', $fechaStr);
            $queryProceso = AseguramientoCalidad::whereNotNull('cliente')->whereDate('created_at', $fechaStr);

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
                        'fechas' => $fechasStr,
                        'porcentajesErrorAQL' => array_fill(0, count($fechasStr), 0),
                        'porcentajesErrorProceso' => array_fill(0, count($fechasStr), 0)
                    ];
                }

                // Obtener datos de AQL
                $sumaAuditadaAQL = AuditoriaAQL::where('cliente', $cliente)
                    ->whereDate('created_at', $fechaStr)
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->sum('cantidad_auditada');
                $sumaRechazadaAQL = AuditoriaAQL::where('cliente', $cliente)
                    ->whereDate('created_at', $fechaStr)
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->sum('cantidad_rechazada');

                $porcentajeErrorAQL = ($sumaAuditadaAQL != 0) ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100 : 0;

                // Obtener datos de Procesos
                $sumaAuditadaProceso = AseguramientoCalidad::where('cliente', $cliente)
                    ->whereDate('created_at', $fechaStr)
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->sum('cantidad_auditada');
                $sumaRechazadaProceso = AseguramientoCalidad::where('cliente', $cliente)
                    ->whereDate('created_at', $fechaStr)
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->sum('cantidad_rechazada');

                $porcentajeErrorProceso = ($sumaAuditadaProceso != 0) ? ($sumaRechazadaProceso / $sumaAuditadaProceso) * 100 : 0;

                // Encontrar el índice correspondiente a la fecha
                $index = array_search($fechaStr, $fechasStr);

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
