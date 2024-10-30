<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\AuditoriaProcesoCorte;
use App\Models\AseguramientoCalidad;
use App\Models\TpAseguramientoCalidad;
use App\Models\TpAuditoriaAQL;
use App\Models\AuditoriaAQL;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod; // Asegúrate de importar la clase Carbon
use Illuminate\Support\Facades\DB; // Importa la clase DB


class DashboardPlanta1PorDiaController extends Controller
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

    public function dashboardPanta1PorDia(Request $request)
    {
        $title = "";
        //$fechaActual = Carbon::now()->toDateString();
        //$fechaActual = date('15/08/2024');
        //$fechaActual = Carbon::create(2024, 8, 19);
        //dd($fechaActual, $fechaEspecifica);
        // Verifica si hay una fecha en la solicitud; si la hay, la convierte en un objeto Carbon, si no, usa la fecha actual
        $fechaActual = $request->has('fecha_inicio') 
            ? Carbon::parse($request->input('fecha_inicio')) 
            : Carbon::now();  // Aquí no se usa toDateString(), así que $fechaActual es un objeto Carbon
        //dd($fechaActual);
        $plantaConsulta = "Intimark1";
        $fechaInicio = Carbon::now()->subMonth()->toDateString(); // Cambia el rango de fechas según necesites
        $fechaFin = Carbon::now()->toDateString();


        function calcularPorcentaje($modelo, $fecha, $planta = null)
        {
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

        
        // Obtención y cálculo de datos generales para AQL y Proceso
        $dataGerentesAQLGeneral = $this->getDataGerentesProduccionAQL($fechaActual, 'Intimark1');
        $dataGerentesProcesoGeneral = $this->getDataGerentesProduccionProceso($fechaActual, 'Intimark1');

        // Combinar los datos
        $dataGerentesGeneral = $this->combineDataGerentes($dataGerentesAQLGeneral, $dataGerentesProcesoGeneral);



        // Datos generales
        $dataGeneral = $this->obtenerDatosClientesPorFiltro($fechaActual);
        $totalGeneral = $this->calcularTotales($dataGeneral['dataCliente']);

        // Datos planta Intimark1
        $dataPlanta1 = $this->obtenerDatosClientesPorFiltro($fechaActual, 'Intimark1');
        $totalPlanta1 = $this->calcularTotales($dataPlanta1['dataCliente']);

        // Datos planta Intimark2
        $dataPlanta2 = $this->obtenerDatosClientesPorFiltro($fechaActual, 'Intimark2');
        $totalPlanta2 = $this->calcularTotales($dataPlanta2['dataCliente']);


        //dd($dataGrafica, $clientesGrafica, $datasetsAQL, $datasetsProceso);
        //apartado para mostrar datos de gerente de prodduccion, en este caso por dia AseguramientoCalidad y AuditoriaAQL
        // Obtención y cálculo de datos generales para AQL y Proceso
        $dataModuloAQLGeneral = $this->getDataModuloAQL($fechaActual, 'Intimark1', null);   
        $dataModuloProcesoGeneral = $this->getDataModuloProceso($fechaActual, 'Intimark1', null);   

        // Para obtener los datos con tiempo_extra = 1
        $dataModuloAQLGeneralTE = $this->getDataModuloAQL($fechaActual, 'Intimark1', 1); 
        $dataModuloProcesoGeneralTE = $this->getDataModuloProceso($fechaActual, 'Intimark1', 1);  

        //dd($dataModuloAQLGeneral, $dataModuloAQLGeneralTE);

        // Obtención y cálculo de datos por planta para Auditoria AQL
        $dataModuloAQLPlanta1 = $this->getDataModuloAQL($fechaActual, 'Intimark1');
        $dataModuloAQLPlanta2 = $this->getDataModuloAQL($fechaActual, 'Intimark2');

        // Obtención y cálculo de datos por planta para Aseguramiento Calidad
        $dataModuloProcesoPlanta1 = $this->getDataModuloProceso($fechaActual, 'Intimark1');
        $dataModuloProcesoPlanta2 = $this->getDataModuloProceso($fechaActual, 'Intimark2');

        // Combinar los datos
        $dataModulosGeneral = $this->combineDataModulos($dataModuloAQLGeneral, $dataModuloProcesoGeneral);

        //aqui empieza lo bueno desde 0
        // Llamadas a la función para obtener los datos
        $datosModuloEstiloProceso = $this->getDatosModuloEstiloProceso($fechaActual, $plantaConsulta, null);
        $datosModuloEstiloProcesoTE = $this->getDatosModuloEstiloProceso($fechaActual, $plantaConsulta, 1);

        // Verificar si existen datos y asignar null si están vacíos
        $datosModuloEstiloProceso = count($datosModuloEstiloProceso) > 0 ? $datosModuloEstiloProceso : null;
        $datosModuloEstiloProcesoTE = count($datosModuloEstiloProcesoTE) > 0 ? $datosModuloEstiloProcesoTE : null;

        return view('dashboar.dashboardPanta1PorDia', compact(
            'title', 'fechaActual', 'dataModuloAQLGeneral',
            'dataModuloProcesoGeneral', 'generalAQL', 'generalAQLPlanta1',
            'generalProceso', 'generalProcesoPlanta1', 'dataModuloAQLGeneralTE',
            'dataModuloProcesoGeneralTE',
            'datosModuloEstiloProceso', 'datosModuloEstiloProcesoTE',
        ));
    }


    private function obtenerDatosClientesPorFiltro($fechaActual, $planta = null)
    {
        $queryAQL = AuditoriaAQL::whereNotNull('cliente')->whereDate('created_at', $fechaActual)->where('planta', 'Intimark1');
        $queryProceso = AseguramientoCalidad::whereNotNull('cliente')->whereDate('created_at', $fechaActual)->where('planta', 'Intimark1');

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
                ->where('planta', 'Intimark1')
                ->sum('cantidad_auditada');
            $sumaRechazadaAQL = AuditoriaAQL::where('cliente', $cliente)
                ->whereDate('created_at', $fechaActual)
                ->when($planta, function ($query) use ($planta) {
                    return $query->where('planta', $planta);
                })
                ->where('planta', 'Intimark1')
                ->sum('cantidad_rechazada');

            $porcentajeErrorAQL = ($sumaAuditadaAQL != 0) ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100 : 0;

            $sumaAuditadaProceso = AseguramientoCalidad::where('cliente', $cliente)
                ->whereDate('created_at', $fechaActual)
                ->when($planta, function ($query) use ($planta) {
                    return $query->where('planta', $planta);
                })
                ->where('planta', 'Intimark1')
                ->sum('cantidad_auditada');
            $sumaRechazadaProceso = AseguramientoCalidad::where('cliente', $cliente)
                ->whereDate('created_at', $fechaActual)
                ->when($planta, function ($query) use ($planta) {
                    return $query->where('planta', $planta);
                })
                ->where('planta', 'Intimark1')
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
            $queryAQL = AuditoriaAQL::whereNotNull('cliente')->whereDate('created_at', $fechaStr)->where('planta', 'Intimark1');
            $queryProceso = AseguramientoCalidad::whereNotNull('cliente')->whereDate('created_at', $fechaStr)->where('planta', 'Intimark1');

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
                    ->where('planta', 'Intimark1')
                    ->sum('cantidad_auditada');
                $sumaRechazadaAQL = AuditoriaAQL::where('cliente', $cliente)
                    ->whereDate('created_at', $fechaStr)
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->where('planta', 'Intimark1')
                    ->sum('cantidad_rechazada');

                $porcentajeErrorAQL = ($sumaAuditadaAQL != 0) ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100 : 0;

                // Obtener datos de Procesos
                $sumaAuditadaProceso = AseguramientoCalidad::where('cliente', $cliente)
                    ->whereDate('created_at', $fechaStr)
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->where('planta', 'Intimark1')
                    ->sum('cantidad_auditada');
                $sumaRechazadaProceso = AseguramientoCalidad::where('cliente', $cliente)
                    ->whereDate('created_at', $fechaStr)
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->where('planta', 'Intimark1')
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

    private function calcularTotales($dataClientes)
    {
        $totalAuditadaAQL = array_sum(array_map(function ($data) {
            return AuditoriaAQL::where('cliente', $data['cliente'])->where('planta', 'Intimark1')->sum('cantidad_auditada');
        }, $dataClientes));

        $totalRechazadaAQL = array_sum(array_map(function ($data) {
            return AuditoriaAQL::where('cliente', $data['cliente'])->where('planta', 'Intimark1')->sum('cantidad_rechazada');
        }, $dataClientes));

        $totalAuditadaProceso = array_sum(array_map(function ($data) {
            return AseguramientoCalidad::where('cliente', $data['cliente'])->where('planta', 'Intimark1')->sum('cantidad_auditada');
        }, $dataClientes));

        $totalRechazadaProceso = array_sum(array_map(function ($data) {
            return AseguramientoCalidad::where('cliente', $data['cliente'])->where('planta', 'Intimark1')->sum('cantidad_rechazada');
        }, $dataClientes));

        return [
            'totalPorcentajeErrorAQL' => ($totalAuditadaAQL != 0) ? ($totalRechazadaAQL / $totalAuditadaAQL) * 100 : 0,
            'totalPorcentajeErrorProceso' => ($totalAuditadaProceso != 0) ? ($totalRechazadaProceso / $totalAuditadaProceso) * 100 : 0,
        ];
    }



    private function getDataModuloAQL($fecha, $planta = null, $tiempoExtra = null)
    {
        $query = AuditoriaAQL::whereDate('created_at', $fecha);

        if (!is_null($planta)) {
            $query->where('planta', $planta);
        }
        if (!is_null($tiempoExtra)) {
            $query->where('tiempo_extra', $tiempoExtra);
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
                ->where('tiempo_extra', $tiempoExtra)
                ->distinct()
                ->count('modulo');

            $sumaAuditadaAQL = AuditoriaAQL::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->sum('cantidad_auditada');

            $sumaRechazadaAQL = AuditoriaAQL::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->sum('cantidad_rechazada');

            $porcentajeErrorAQL = ($sumaAuditadaAQL != 0) ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100 : 0;

            $conteoOperario = AuditoriaAQL::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->whereNotNull('nombre')
                ->where('nombre', '!=', '')
                ->select(DB::raw('
                    SUM(
                        CHAR_LENGTH(nombre) - CHAR_LENGTH(REPLACE(nombre, ",", "")) + 1
                    ) as total_nombres
                '))
                ->first()
                ->total_nombres ?? 0;

            $conteoMinutos = AuditoriaAQL::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->count('minutos_paro');

            $conteParoModular = AuditoriaAQL::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->count('minutos_paro_modular');

            $sumaMinutos = AuditoriaAQL::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->sum('minutos_paro');

            $promedioMinutos = $conteoMinutos != 0 ? $sumaMinutos / $conteoMinutos : 0;
            $promedioMinutosEntero = ceil($promedioMinutos);

            $detalles = AuditoriaAQL::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->get();
            //dd($detalles);
            $sumaPiezasBulto = AuditoriaAQL::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->sum('pieza');
            //dd($sumaPiezasBulto);
            $cantidadBultosEncontrados = AuditoriaAQL::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->count();
            //dd($cantidadBultosEncontrados);
            $cantidadBultosRechazados = AuditoriaAQL::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->where('cantidad_rechazada', '>', 0)
                ->count();
            //dd($cantidadBultosRechazados);
            $estilosUnicos = AuditoriaAQL::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->distinct()
                ->pluck('estilo')
                ->implode(', ');
            //dd($estilosUnicos);
            $auditorUnicos = AuditoriaAQL::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->distinct()
                ->pluck('auditor')
                ->implode(', ');
            $defectosUnicos = AuditoriaAQL::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->whereHas('tpAuditoriaAQL', function ($query) {
                    $query->where('tp', '!=', 'NINGUNO');
                })
                ->with(['tpAuditoriaAQL' => function ($query) {
                    $query->where('tp', '!=', 'NINGUNO');
                }])
                ->get()
                ->pluck('tpAuditoriaAQL.*.tp')
                ->flatten()
                //->unique()
                ->implode(', ');
            $defectosUnicos = $defectosUnicos ?: 'N/A';
            //dd($defectosUnicos);
            $accionesCorrectivasUnicos = AuditoriaAQL::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->distinct()
                ->pluck('ac')
                ->implode(', ');
            //dd($accionesCorrectivasUnicos);
            $accionesCorrectivasUnicos = $accionesCorrectivasUnicos ?: 'N/A';

            $operariosUnicos = AuditoriaAQL::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->distinct()
                ->pluck('nombre')
                ->implode(', ');
            //dd($accionesCorrectivasUnicos);
            $operariosUnicos = $operariosUnicos ?: 'N/A';
            $sumaReparacionRechazo = AuditoriaAQL::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->sum('reparacion_rechazo');
            $sumaReparacionRechazo = $sumaReparacionRechazo ?: 'N/A';
            //dd();
            $piezasRechazadasUnicas = AuditoriaAQL::where('modulo', $modulo)
                    ->whereDate('created_at', $fecha)
                    ->where('tiempo_extra', $tiempoExtra)
                    ->where('cantidad_rechazada', '>', 0)
                    ->distinct()
                    ->pluck('pieza')
                    ->implode(', ');
            $piezasRechazadasUnicas = $piezasRechazadasUnicas ?: 'N/A';

            $sumaParoModular = AuditoriaAQL::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->sum('minutos_paro_modular');
            $sumaParoModular = $sumaParoModular ?: 'N/A';

            $dataModuloAQL[] = [
                'modulo' => $modulo,
                'auditorUnicos' => $auditorUnicos,
                'modulos_unicos' => $modulosUnicos,
                'porcentaje_error_aql' => $porcentajeErrorAQL,
                'conteoOperario' => $conteoOperario,
                'conteoMinutos' => $conteoMinutos,
                'sumaMinutos' => $sumaMinutos,
                'promedioMinutosEntero' => $promedioMinutosEntero,
                'conteParoModular' => $conteParoModular,
                'detalles' => $detalles,
                'sumaRechazadaAQL' => $sumaRechazadaAQL,
                'sumaAuditadaAQL' => $sumaAuditadaAQL, 
                'sumaPiezasBulto' => $sumaPiezasBulto,
                'cantidadBultosEncontrados' => $cantidadBultosEncontrados, 
                'cantidadBultosRechazados' => $cantidadBultosRechazados,
                'estilosUnicos' => $estilosUnicos,
                'defectosUnicos' => $defectosUnicos,
                'accionesCorrectivasUnicos' => $accionesCorrectivasUnicos,
                'operariosUnicos' => $operariosUnicos,
                'sumaReparacionRechazo' => $sumaReparacionRechazo,
                'piezasRechazadasUnicas' => $piezasRechazadasUnicas, 
                'sumaParoModular' => $sumaParoModular,
            ];

            
        }

        return $dataModuloAQL;
    }

    private function getDataModuloProceso($fecha, $planta = null, $tiempoExtra = null)
    {
        $query = AseguramientoCalidad::whereDate('created_at', $fecha);

        if (!is_null($planta)) {
            $query->where('planta', $planta);
        }
        if (!is_null($tiempoExtra)) {
            $query->where('tiempo_extra', $tiempoExtra);
        }

        $modulosProceso = $query->select('modulo')
            ->distinct()
            ->pluck('modulo')
            ->all();

        $dataModuloProceso = [];
        foreach ($modulosProceso as $modulo) {
            $queryModulo = AseguramientoCalidad::where('modulo', $modulo)
                ->where('tiempo_extra', $tiempoExtra)
                ->whereDate('created_at', $fecha);

            if (!is_null($planta)) {
                $queryModulo->where('planta', $planta);
            }

            $auditorUnicos = AseguramientoCalidad::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->distinct()
                ->pluck('auditor')
                ->implode(', ');

            $sumaAuditadaProceso = AseguramientoCalidad::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->sum('cantidad_auditada');

            $sumaRechazadaProceso = AseguramientoCalidad::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->sum('cantidad_rechazada');

            $porcentajeErrorProceso = ($sumaAuditadaProceso != 0) ? ($sumaRechazadaProceso / $sumaAuditadaProceso) * 100 : 0;

            $conteoOperario = AseguramientoCalidad::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('utility', null)
                ->where('tiempo_extra', $tiempoExtra)
                ->distinct()
                ->count('nombre');

            $conteoUtility = AseguramientoCalidad::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('utility', 1)
                ->where('tiempo_extra', $tiempoExtra)
                ->distinct()
                ->count('nombre');

            $conteoMinutos = AseguramientoCalidad::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->count('minutos_paro');

            $sumaMinutos = AseguramientoCalidad::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->sum('minutos_paro');

            $promedioMinutos = $conteoMinutos != 0 ? $sumaMinutos / $conteoMinutos : 0;
            $promedioMinutosEntero = ceil($promedioMinutos);

            $detalles = AseguramientoCalidad::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->get();

            $estilosUnicos = AseguramientoCalidad::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->distinct()
                ->pluck('estilo')
                ->implode(', ');
            //dd($estilosUnicos);
            $defectosUnicos = AseguramientoCalidad::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->whereHas('TpAseguramientoCalidad', function ($query) {
                    $query->where('tp', '!=', 'NINGUNO');
                })
                ->with(['TpAseguramientoCalidad' => function ($query) {
                    $query->where('tp', '!=', 'NINGUNO');
                }])
                ->get()
                ->pluck('TpAseguramientoCalidad.*.tp')
                ->flatten()
                //->unique()
                //->sort()  // Ordenar alfabéticamente
                ->implode(', ');
            $defectosUnicos = $defectosUnicos ?: 'N/A';
            $accionesCorrectivasUnicos = AseguramientoCalidad::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->distinct()
                ->pluck('ac')
                ->implode(', ');
            //dd($accionesCorrectivasUnicos);
            $accionesCorrectivasUnicos = $accionesCorrectivasUnicos ?: 'N/A';
            $cantidadRecorridos = AseguramientoCalidad::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->selectRaw('nombre, COUNT(*) as cantidad_registros')
                ->groupBy('nombre')
                ->orderByDesc('cantidad_registros')
                ->limit(1)  // Solo necesitamos el primero, el de mayor repetición
                ->value('cantidad_registros');
            //dd($cantidadRecorridos);
            $operariosUnicos = AseguramientoCalidad::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->where('cantidad_rechazada','>', 0)
                ->distinct()
                ->pluck('nombre')
                ->implode(', ');
            $operariosUnicos = $operariosUnicos ?: 'N/A';

            $conteParoModular = AseguramientoCalidad::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->count('minutos_paro_modular');

            $sumaParoModular = AseguramientoCalidad::where('modulo', $modulo)
                ->whereDate('created_at', $fecha)
                ->where('tiempo_extra', $tiempoExtra)
                ->sum('minutos_paro_modular');
            $sumaParoModular = $sumaParoModular ?: 'N/A';

            $dataModuloProceso[] = [
                'modulo' => $modulo,
                'auditorUnicos' => $auditorUnicos,
                'porcentaje_error_proceso' => $porcentajeErrorProceso,
                'conteoOperario' => $conteoOperario,
                'conteoUtility' => $conteoUtility,
                'conteoMinutos' => $conteoMinutos,
                'sumaMinutos' => $sumaMinutos,
                'promedioMinutosEntero' => $promedioMinutosEntero,
                'detalles' => $detalles,
                'sumaRechazadaProceso' => $sumaRechazadaProceso,
                'sumaAuditadaProceso' => $sumaAuditadaProceso, 
                'estilosUnicos' => $estilosUnicos, 
                'defectosUnicos' => $defectosUnicos,
                'accionesCorrectivasUnicos' => $accionesCorrectivasUnicos,
                'cantidadRecorridos' => $cantidadRecorridos,
                'operariosUnicos' => $operariosUnicos,
                'sumaParoModular' => $sumaParoModular,
                'conteParoModular' => $conteParoModular,
            ];

            

        }

        return $dataModuloProceso;
    }

    private function getDataGerentesProduccionAQL($fecha, $planta = null)
    {
        $query = AuditoriaAQL::whereDate('created_at', $fecha)->where('planta', 'Intimark1');

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
                ->where('planta', 'Intimark1')
                ->select('modulo')
                ->distinct()
                ->get()
                ->pluck('modulo');

            $modulosUnicos = $modulosUnicosAQL->count();

            $sumaAuditadaAQL = AuditoriaAQL::where('team_leader', $gerente)
                ->whereDate('created_at', $fecha)
                ->where('planta', 'Intimark1')
                ->sum('cantidad_auditada');

            $sumaRechazadaAQL = AuditoriaAQL::where('team_leader', $gerente)
                ->whereDate('created_at', $fecha)
                ->where('planta', 'Intimark1')
                ->sum('cantidad_rechazada');

            $porcentajeErrorAQL = ($sumaAuditadaAQL != 0) ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100 : 0;

            $conteoOperario = AuditoriaAQL::where('team_leader', $gerente)
                ->whereDate('created_at', $fecha)
                ->where('planta', 'Intimark1')
                ->distinct('nombre')
                ->count('nombre');

            $conteoMinutos = AuditoriaAQL::where('team_leader', $gerente)
                ->whereDate('created_at', $fecha)
                ->where('planta', 'Intimark1')
                ->count('minutos_paro');

            $conteParoModular = AuditoriaAQL::where('team_leader', $gerente)
                ->whereDate('created_at', $fecha)
                ->where('planta', 'Intimark1')
                ->count('minutos_paro_modular');

            $sumaMinutos = AuditoriaAQL::where('team_leader', $gerente)
                ->whereDate('created_at', $fecha)
                ->where('planta', 'Intimark1')
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
        $query = AseguramientoCalidad::whereDate('created_at', $fecha)->where('planta', 'Intimark1');

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
                ->where('planta', 'Intimark1')
                ->select('modulo')
                ->distinct()
                ->get()
                ->pluck('modulo');

            $modulosUnicos = $modulosUnicosProceso->count();

            $sumaAuditadaProceso = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereDate('created_at', $fecha)
                ->where('planta', 'Intimark1')
                ->sum('cantidad_auditada');

            $sumaRechazadaProceso = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereDate('created_at', $fecha)
                ->where('planta', 'Intimark1')
                ->sum('cantidad_rechazada');

            $porcentajeErrorProceso = ($sumaAuditadaProceso != 0) ? ($sumaRechazadaProceso / $sumaAuditadaProceso) * 100 : 0;

            $conteoOperario = AseguramientoCalidad::where('team_leader', $gerente)
                ->where('utility', null)
                ->whereDate('created_at', $fecha)
                ->where('planta', 'Intimark1')
                ->distinct('nombre')
                ->count('nombre');

            $conteoUtility = AseguramientoCalidad::where('team_leader', $gerente)
                ->where('utility', 1)
                ->whereDate('created_at', $fecha)
                ->where('planta', 'Intimark1')
                ->distinct('nombre')
                ->count('nombre');

            $conteoMinutos = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereDate('created_at', $fecha)
                ->where('planta', 'Intimark1')
                ->where('planta', 'Intimark1')
                ->count('minutos_paro');

            $sumaMinutos = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereDate('created_at', $fecha)
                ->where('planta', 'Intimark1')
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
            $queryAQL = AuditoriaAQL::whereNotNull('modulo')->whereDate('created_at', $fechaStr)->where('planta', 'Intimark1');
            $queryProceso = AseguramientoCalidad::whereNotNull('modulo')->whereDate('created_at', $fechaStr)->where('planta', 'Intimark1');

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
                    ->where('planta', 'Intimark1')
                    ->sum('cantidad_auditada');
                $sumaRechazadaAQL = AuditoriaAQL::where('modulo', $modulo)
                    ->whereDate('created_at', $fechaStr)
                    ->where('planta', 'Intimark1')
                    ->sum('cantidad_rechazada');

                $porcentajeErrorAQL = ($sumaAuditadaAQL != 0) ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100 : 0;

                // Obtener datos de Procesos
                $sumaAuditadaProceso = AseguramientoCalidad::where('modulo', $modulo)
                    ->whereDate('created_at', $fechaStr)
                    ->where('planta', 'Intimark1')
                    ->sum('cantidad_auditada');
                $sumaRechazadaProceso = AseguramientoCalidad::where('modulo', $modulo)
                    ->whereDate('created_at', $fechaStr)
                    ->where('planta', 'Intimark1')
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

            // Obtener auditores únicos para la combinación actual de módulo y estilo
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
                ->implode(', ');  // Combina los auditores únicos con comas

            // Obtener el valor de cantidadRecorridos basado en la frecuencia máxima de "nombre" en la combinación actual
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
                ->limit(1)  // Obtener solo el valor más alto de las repeticiones
                ->value('cantidad_repeticiones');  // Obtener el valor de la frecuencia más alta

            // Almacenar los resultados en el arreglo principal
            $dataModuloEstiloProceso[] = [
                'modulo' => $modulo,
                'estilo' => $estilo,
                'auditoresUnicos' => $auditoresUnicos,
                'cantidadRecorridos' => $cantidadRecorridos,
            ];
        }

        // Retornar los datos procesados
        return $dataModuloEstiloProceso;
    }

}
