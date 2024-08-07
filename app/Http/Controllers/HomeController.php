<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\AseguramientoCalidad;  
use App\Models\AuditoriaAQL;  
use App\Models\TpAseguramientoCalidad;  
use App\Models\TpAuditoriaAQL;  
use Carbon\Carbon; // Asegúrate de importar la clase Carbon  
use Carbon\CarbonPeriod; // Asegúrate de importar la clase Carbon  
use Illuminate\Support\Facades\DB; // Importa la clase DB

class HomeController extends Controller
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
    public function index()
    {
        $title = "";
        $fechaActual = Carbon::now()->toDateString();
        $fechaInicio = Carbon::now()->subMonth()->toDateString(); // Cambia el rango de fechas según necesites
        $fechaFin = Carbon::now()->toDateString();


        // Verifica si el usuario tiene los roles 'Administrador' o 'Gerente de Calidad'
        if (Auth::check() && (Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Gerente de Calidad'))) {

            function calcularPorcentaje($modelo, $fecha, $planta = null) {
                $query = $modelo::whereDate('created_at', $fecha);
                if ($planta) {
                    $query->where('planta', $planta);
                }
                $data = $query->selectRaw('SUM(cantidad_auditada) as cantidad_auditada, SUM(cantidad_rechazada) as cantidad_rechazada')
                              ->first();
                return $data->cantidad_auditada != 0 ? number_format(($data->cantidad_rechazada / $data->cantidad_auditada) * 100, 2) : 0;
            }
            
            // Información General
            $generalProceso = calcularPorcentaje(AseguramientoCalidad::class, $fechaActual);
            $generalAQL = calcularPorcentaje(AuditoriaAQL::class, $fechaActual);
            
            // Planta 1 Ixtlahuaca
            $generalProcesoPlanta1 = calcularPorcentaje(AseguramientoCalidad::class, $fechaActual, 'Intimark1');
            $generalAQLPlanta1 = calcularPorcentaje(AuditoriaAQL::class, $fechaActual, 'Intimark1');
            
            // Planta 2 San Bartolo
            $generalProcesoPlanta2 = calcularPorcentaje(AseguramientoCalidad::class, $fechaActual, 'Intimark2');
            $generalAQLPlanta2 = calcularPorcentaje(AuditoriaAQL::class, $fechaActual, 'Intimark2');

            // Nueva consulta para obtener datos por fecha
            $fechas = collect();
            $period = Carbon::parse($fechaInicio)->daysUntil(Carbon::parse($fechaFin));

            foreach ($period as $date) {
                $fechas->push($date->format('Y-m-d'));
            }

            $porcentajesAQL = $fechas->map(function($fecha) {
                return calcularPorcentaje(AuditoriaAQL::class, $fecha);
            });

            $porcentajesProceso = $fechas->map(function($fecha) {
                return calcularPorcentaje(AseguramientoCalidad::class, $fecha);
            });


            // Obtención y cálculo de datos generales para AQL y Proceso
            $dataGerentesAQLGeneral = $this->getDataGerentesProduccionAQL($fechaActual);
            $dataGerentesProcesoGeneral = $this->getDataGerentesProduccionProceso($fechaActual);

            // Obtención y cálculo de datos por planta para gerentes de producción
            $dataGerentesAQLPlanta1 = $this->getDataGerentesProduccionAQL($fechaActual, 'Intimark1');
            $dataGerentesAQLPlanta2 = $this->getDataGerentesProduccionAQL($fechaActual, 'Intimark2');
            $dataGerentesProcesoPlanta1 = $this->getDataGerentesProduccionProceso($fechaActual, 'Intimark1');
            $dataGerentesProcesoPlanta2 = $this->getDataGerentesProduccionProceso($fechaActual, 'Intimark2');

            // Combinar los datos
            $dataGerentesGeneral = $this->combineDataGerentes($dataGerentesAQLGeneral, $dataGerentesProcesoGeneral);
 


            // Datos generales
            $dataGeneral = $this->obtenerDatosClientesPorFiltro($fechaActual);

            // Datos planta Intimark1
            $dataPlanta1 = $this->obtenerDatosClientesPorFiltro($fechaActual, 'Intimark1');

            // Datos planta Intimark2
            $dataPlanta2 = $this->obtenerDatosClientesPorFiltro($fechaActual, 'Intimark2');

            // Datos para las gráficas usando el rango de fechas
            $dataGrafica = $this->obtenerDatosClientesPorRangoFechas($fechaInicio, $fechaFin);
            //$clientesGrafica = collect($dataGrafica['clientesUnicos'])->toArray();
            //$fechasGrafica = collect($dataGrafica['dataCliente'][0]['fechas'])->toArray();
            // Verificar si los datos están vacíos y asignar valores por defecto
            $clientesGrafica = !empty($dataGrafica['clientesUnicos']) ? collect($dataGrafica['clientesUnicos'])->toArray() : [0];
            $fechasGrafica = !empty($dataGrafica['dataCliente'][0]['fechas']) ? collect($dataGrafica['dataCliente'][0]['fechas'])->toArray() : [0];

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
            //dd($dataGrafica, $clientesGrafica, $datasetsAQL, $datasetsProceso);
            //apartado para mostrar datos de gerente de prodduccion, en este caso por dia AseguramientoCalidad y AuditoriaAQL
            // Obtención y cálculo de datos generales para AQL y Proceso
            $dataModuloAQLGeneral = $this->getDataModuloAQL($fechaActual);
            $dataModuloProcesoGeneral = $this->getDataModuloProceso($fechaActual);
            
            // Obtención y cálculo de datos por planta para Auditoria AQL
            $dataModuloAQLPlanta1 = $this->getDataModuloAQL($fechaActual, 'Intimark1');
            $dataModuloAQLPlanta2 = $this->getDataModuloAQL($fechaActual, 'Intimark2');

            // Obtención y cálculo de datos por planta para Aseguramiento Calidad
            $dataModuloProcesoPlanta1 = $this->getDataModuloProceso($fechaActual, 'Intimark1');
            $dataModuloProcesoPlanta2 = $this->getDataModuloProceso($fechaActual, 'Intimark2');

            // Combinar los datos
            $dataModulosGeneral = $this->combineDataModulos($dataModuloAQLGeneral, $dataModuloProcesoGeneral);


            //dd($dataModuloAQLGeneral, $dataModuloProcesoGeneral, $dataModuloAQLPlanta1, $dataModuloAQLPlanta2, $dataModuloProcesoPlanta1, $dataModuloProcesoPlanta2);

            // Consulta para obtener los 3 valores más repetidos de 'tp' excluyendo 'NINGUNO'
            $topDefectosAQL = TpAuditoriaAQL::select('tp', DB::raw('count(*) as total'))
                ->where('tp', '!=', 'NINGUNO')
                ->groupBy('tp')
                ->orderBy('total', 'desc')
                ->limit(3)
                ->get();
            // Consulta para obtener los 3 valores más repetidos de 'tp' excluyendo 'NINGUNO'
            $topDefectosProceso = TpAseguramientoCalidad::select('tp', DB::raw('count(*) as total'))
                ->where('tp', '!=', 'NINGUNO')
                ->groupBy('tp')
                ->orderBy('total', 'desc')
                ->limit(3)
                ->get();

            //dd($gerentesProduccionAQL, $gerentesProduccionProceso, $gerentesProduccion, $data);
            $dataGraficaModulos = $this->obtenerDatosModulosPorRangoFechas($fechaInicio, $fechaFin);
            $modulosGrafica = !empty($dataGraficaModulos['modulosUnicos']) ? collect($dataGraficaModulos['modulosUnicos'])->toArray() : [0];
            $fechasGraficaModulos = !empty($dataGraficaModulos['dataModulo'][0]['fechas']) ? collect($dataGraficaModulos['dataModulo'][0]['fechas'])->toArray() : [0];

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


            // Obtener los clientes únicos de AseguramientoCalidad 
            $clientesAseguramientoBusqueda = AseguramientoCalidad::select('cliente')
            ->distinct()
            ->pluck('cliente');

            // Obtener los clientes únicos de AuditoriaAQL
            $clientesAuditoriaBusqueda = AuditoriaAQL::select('cliente')
            ->distinct()
            ->pluck('cliente');

            // Combinar ambas listas y eliminar duplicados
            $clientesUnicosBusqueda = $clientesAseguramientoBusqueda->merge($clientesAuditoriaBusqueda)->unique();

            // Convertir la colección a un array si es necesario
            $clientesUnicosArrayBusqueda = $clientesUnicosBusqueda->values()->all();

            // Obtener los modulos únicos de AseguramientoCalidad 
            //$modulosAseguramientoBusqueda = AseguramientoCalidad::select('modulo')
            //->distinct()
            //->pluck('modulo');

            // Obtener los modulos únicos de AuditoriaAQL
            //$modulosAuditoriaBusqueda = AuditoriaAQL::select('modulo')
            //->distinct()
            //->pluck('modulo');

            // Combinar ambas listas y eliminar duplicados
            //$modulosUnicosBusqueda = $modulosAseguramientoBusqueda->merge($modulosAuditoriaBusqueda)->unique();

            // Convertir la colección a un array si es necesario
            //$modulosUnicosArrayBusqueda = $modulosUnicosBusqueda->values()->all();
            //dd($clientesUnicosArrayBusqueda);


            return view('dashboard', compact('title', 'topDefectosAQL', 'topDefectosProceso',
                                    'dataModuloAQLPlanta1', 'dataModuloAQLPlanta2', 'dataModuloProcesoPlanta1', 'dataModuloProcesoPlanta2',
                                    'dataModuloAQLGeneral', 'dataModuloProcesoGeneral',
                                    'dataGerentesAQLGeneral', 'dataGerentesProcesoGeneral', 'dataGerentesAQLPlanta1', 'dataGerentesAQLPlanta2', 'dataGerentesProcesoPlanta1', 'dataGerentesProcesoPlanta2',
                                    'generalProceso', 'generalAQL', 'generalAQLPlanta1', 'generalAQLPlanta2','generalProcesoPlanta1', 'generalProcesoPlanta2',
                                    'dataGeneral', 'dataPlanta1', 'dataPlanta2',
                                    'dataGerentesGeneral', 'dataModulosGeneral',
                                    'fechas', 'porcentajesAQL', 'porcentajesProceso',
                                    'fechasGrafica', 'datasetsAQL', 'datasetsProceso', 'clientesGrafica',
                                    'fechasGraficaModulos', 'datasetsAQLModulos', 'datasetsProcesoModulos', 'modulosGrafica',
                                    'clientesUnicosArrayBusqueda')); 
        } else {
            // Si el usuario no tiene esos roles, redirige a listaFormularios
            return redirect()->route('viewlistaFormularios');
        }
    }

    private function obtenerDatosClientesPorFiltro($fechaActual, $planta = null)
    {
        $queryAQL = AuditoriaAQL::whereNotNull('cliente')->whereDate('created_at', $fechaActual);
        $queryProceso = AseguramientoCalidad::whereNotNull('cliente')->whereDate('created_at', $fechaActual);

        if ($planta) {
            $queryAQL->where('planta', $planta);
            $queryProceso->where('planta', $planta);
        }

        $clientesAQL = $queryAQL->pluck('cliente');
        $clientesProceso = $queryProceso->pluck('cliente');
        $clientesUnicos = $clientesAQL->merge($clientesProceso)->unique();

        $dataCliente = [];
        foreach ($clientesUnicos as $cliente) {
            $sumaAuditadaAQL = AuditoriaAQL::where('cliente', $cliente)
                ->whereDate('created_at', $fechaActual)
                ->when($planta, function ($query) use ($planta) {
                    return $query->where('planta', $planta);
                })
                ->sum('cantidad_auditada');
            $sumaRechazadaAQL = AuditoriaAQL::where('cliente', $cliente)
                ->whereDate('created_at', $fechaActual)
                ->when($planta, function ($query) use ($planta) {
                    return $query->where('planta', $planta);
                })
                ->sum('cantidad_rechazada');

            $porcentajeErrorAQL = ($sumaAuditadaAQL != 0) ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100 : 0;

            $sumaAuditadaProceso = AseguramientoCalidad::where('cliente', $cliente)
                ->whereDate('created_at', $fechaActual)
                ->when($planta, function ($query) use ($planta) {
                    return $query->where('planta', $planta);
                })
                ->sum('cantidad_auditada');
            $sumaRechazadaProceso = AseguramientoCalidad::where('cliente', $cliente)
                ->whereDate('created_at', $fechaActual)
                ->when($planta, function ($query) use ($planta) {
                    return $query->where('planta', $planta);
                })
                ->sum('cantidad_rechazada');

            $porcentajeErrorProceso = ($sumaAuditadaProceso != 0) ? ($sumaRechazadaProceso / $sumaAuditadaProceso) * 100 : 0;

            // Agregar al array dataCliente
            $dataCliente[] = [
                'cliente' => $cliente,
                'porcentajeErrorProceso' => $porcentajeErrorProceso,
                'porcentajeErrorAQL' => $porcentajeErrorAQL,
            ];
        }

        return [
            'clientesAQL' => $clientesAQL,
            'clientesProceso' => $clientesProceso,
            'dataCliente' => $dataCliente
        ];
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



    private function getDataModuloAQL($fecha, $planta = null)
    {
        $query = AuditoriaAQL::whereDate('created_at', $fecha);

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
                                ->whereDate('created_at', $fecha);

            if (!is_null($planta)) {
                $queryModulo->where('planta', $planta);
            }

            $modulosUnicos = AuditoriaAQL::where('modulo', $modulo)
                                ->whereDate('created_at', $fecha)
                                ->distinct()
                                ->count('modulo');

            $sumaAuditadaAQL = AuditoriaAQL::where('modulo', $modulo)
                                ->whereDate('created_at', $fecha)
                                ->sum('cantidad_auditada');
                                
            $sumaRechazadaAQL = AuditoriaAQL::where('modulo', $modulo)
                                ->whereDate('created_at', $fecha)
                                ->sum('cantidad_rechazada');

            $porcentajeErrorAQL = ($sumaAuditadaAQL != 0) ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100 : 0;

            $conteoOperario = AuditoriaAQL::where('modulo', $modulo)
                                ->whereDate('created_at', $fecha)
                                ->distinct()
                                ->count('nombre');
                                
            $conteoMinutos = AuditoriaAQL::where('modulo', $modulo)
                                ->whereDate('created_at', $fecha)
                                ->count('minutos_paro');

            $conteParoModular = AuditoriaAQL::where('modulo', $modulo)
                                ->whereDate('created_at', $fecha)
                                ->count('minutos_paro_modular');

            $sumaMinutos = AuditoriaAQL::where('modulo', $modulo)
                                ->whereDate('created_at', $fecha)
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

    private function getDataModuloProceso($fecha, $planta = null)
    {
        $query = AseguramientoCalidad::whereDate('created_at', $fecha);

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
                                        ->whereDate('created_at', $fecha);

            if (!is_null($planta)) {
                $queryModulo->where('planta', $planta);
            }

            $modulosUnicos = AseguramientoCalidad::where('modulo', $modulo)
                                ->whereDate('created_at', $fecha)
                                ->distinct()
                                ->count('modulo');

            $sumaAuditadaProceso = AseguramientoCalidad::where('modulo', $modulo)
                                ->whereDate('created_at', $fecha)
                                ->sum('cantidad_auditada');

            $sumaRechazadaProceso = AseguramientoCalidad::where('modulo', $modulo)
                                ->whereDate('created_at', $fecha)
                                ->sum('cantidad_rechazada');

            $porcentajeErrorProceso = ($sumaAuditadaProceso != 0) ? ($sumaRechazadaProceso / $sumaAuditadaProceso) * 100 : 0;

            $conteoOperario = AseguramientoCalidad::where('modulo', $modulo)
                                ->whereDate('created_at', $fecha)
                                ->where('utility', null)
                                ->distinct()
                                ->count('nombre');

            $conteoUtility = AseguramientoCalidad::where('modulo', $modulo)
                                ->whereDate('created_at', $fecha)
                                ->where('utility', 1)
                                ->distinct()
                                ->count('nombre');

            $conteoMinutos = AseguramientoCalidad::where('modulo', $modulo)
                                ->whereDate('created_at', $fecha)
                                ->count('minutos_paro');

            $sumaMinutos = AseguramientoCalidad::where('modulo', $modulo)
                                ->whereDate('created_at', $fecha)
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

    private function getDataGerentesProduccionAQL($fecha, $planta = null)
    {
        $query = AuditoriaAQL::whereDate('created_at', $fecha);

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
                ->whereDate('created_at', $fecha)
                ->select('modulo')
                ->distinct()
                ->get()
                ->pluck('modulo');

            $modulosUnicos = $modulosUnicosAQL->count();

            $sumaAuditadaAQL = AuditoriaAQL::where('team_leader', $gerente)
                ->whereDate('created_at', $fecha)
                ->sum('cantidad_auditada');

            $sumaRechazadaAQL = AuditoriaAQL::where('team_leader', $gerente)
                ->whereDate('created_at', $fecha)
                ->sum('cantidad_rechazada');

            $porcentajeErrorAQL = ($sumaAuditadaAQL != 0) ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100 : 0;

            $conteoOperario = AuditoriaAQL::where('team_leader', $gerente)
                ->whereDate('created_at', $fecha)
                ->distinct('nombre')
                ->count('nombre');

            $conteoMinutos = AuditoriaAQL::where('team_leader', $gerente)
                ->whereDate('created_at', $fecha)
                ->count('minutos_paro');

            $conteParoModular = AuditoriaAQL::where('team_leader', $gerente)
                ->whereDate('created_at', $fecha)
                ->count('minutos_paro_modular');

            $sumaMinutos = AuditoriaAQL::where('team_leader', $gerente)
                ->whereDate('created_at', $fecha)
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

    private function getDataGerentesProduccionProceso($fecha, $planta = null)
    {
        $query = AseguramientoCalidad::whereDate('created_at', $fecha);

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
                ->whereDate('created_at', $fecha)
                ->select('modulo')
                ->distinct()
                ->get()
                ->pluck('modulo');

            $modulosUnicos = $modulosUnicosProceso->count();

            $sumaAuditadaProceso = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereDate('created_at', $fecha)
                ->sum('cantidad_auditada');

            $sumaRechazadaProceso = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereDate('created_at', $fecha)
                ->sum('cantidad_rechazada');

            $porcentajeErrorProceso = ($sumaAuditadaProceso != 0) ? ($sumaRechazadaProceso / $sumaAuditadaProceso) * 100 : 0;

            $conteoOperario = AseguramientoCalidad::where('team_leader', $gerente)
                ->where('utility', null)
                ->whereDate('created_at', $fecha)
                ->distinct('nombre')
                ->count('nombre');

            $conteoUtility = AseguramientoCalidad::where('team_leader', $gerente)
                ->where('utility', 1)
                ->whereDate('created_at', $fecha)
                ->distinct('nombre')
                ->count('nombre');

            $conteoMinutos = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereDate('created_at', $fecha)
                ->count('minutos_paro');

            $sumaMinutos = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereDate('created_at', $fecha)
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

    private function obtenerDatosModulosPorRangoFechas($fechaInicio, $fechaFin)
    {
        $modulosUnicos = collect();
        $dataModulo = [];

        // Iterar sobre cada día en el rango
        $fechas = CarbonPeriod::create($fechaInicio, '1 day', $fechaFin)->toArray();
        $fechasStr = array_map(function ($fecha) {
            return $fecha->toDateString();
        }, $fechas);

        foreach ($fechas as $fecha) {
            $fechaStr = $fecha->toDateString();

            // Obtener módulos únicos para la fecha actual
            $queryAQL = AuditoriaAQL::whereNotNull('modulo')->whereDate('created_at', $fechaStr);
            $queryProceso = AseguramientoCalidad::whereNotNull('modulo')->whereDate('created_at', $fechaStr);

            $modulosAQL = $queryAQL->pluck('modulo');
            $modulosProceso = $queryProceso->pluck('modulo');
            $modulosDelDia = $modulosAQL->merge($modulosProceso)->unique();

            $modulosUnicos = $modulosUnicos->merge($modulosDelDia)->unique();

            foreach ($modulosDelDia as $modulo) {
                // Inicializar los datos del módulo si no existen
                if (!isset($dataModulo[$modulo])) {
                    $dataModulo[$modulo] = [
                        'modulo' => $modulo,
                        'fechas' => $fechasStr,
                        'porcentajesErrorAQL' => array_fill(0, count($fechasStr), 0),
                        'porcentajesErrorProceso' => array_fill(0, count($fechasStr), 0)
                    ];
                }

                // Obtener datos de AQL
                $sumaAuditadaAQL = AuditoriaAQL::where('modulo', $modulo)
                    ->whereDate('created_at', $fechaStr)
                    ->sum('cantidad_auditada');
                $sumaRechazadaAQL = AuditoriaAQL::where('modulo', $modulo)
                    ->whereDate('created_at', $fechaStr)
                    ->sum('cantidad_rechazada');

                $porcentajeErrorAQL = ($sumaAuditadaAQL != 0) ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100 : 0;

                // Obtener datos de Procesos
                $sumaAuditadaProceso = AseguramientoCalidad::where('modulo', $modulo)
                    ->whereDate('created_at', $fechaStr)
                    ->sum('cantidad_auditada');
                $sumaRechazadaProceso = AseguramientoCalidad::where('modulo', $modulo)
                    ->whereDate('created_at', $fechaStr)
                    ->sum('cantidad_rechazada');

                $porcentajeErrorProceso = ($sumaAuditadaProceso != 0) ? ($sumaRechazadaProceso / $sumaAuditadaProceso) * 100 : 0;

                // Encontrar el índice correspondiente a la fecha
                $index = array_search($fechaStr, $fechasStr);

                // Agregar datos al array dataModulo
                $dataModulo[$modulo]['porcentajesErrorAQL'][$index] = $porcentajeErrorAQL;
                $dataModulo[$modulo]['porcentajesErrorProceso'][$index] = $porcentajeErrorProceso;
            }
        }

        // Convertir dataModulo a la estructura esperada
        $dataModulo = array_values($dataModulo);

        return [
            'modulosUnicos' => $modulosUnicos,
            'dataModulo' => $dataModulo
        ];
    }
}