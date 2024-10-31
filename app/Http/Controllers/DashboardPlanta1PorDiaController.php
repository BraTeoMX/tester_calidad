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

        // Llamadas a la función para obtener los datos Proceso
        $datosModuloEstiloProceso = $this->getDatosModuloEstiloProceso($fechaActual, $plantaConsulta, null);
        $datosModuloEstiloProcesoTE = $this->getDatosModuloEstiloProceso($fechaActual, $plantaConsulta, 1);

        // Verificar si existen datos y asignar null si están vacíos
        $datosModuloEstiloProceso = count($datosModuloEstiloProceso) > 0 ? $datosModuloEstiloProceso : null;
        $datosModuloEstiloProcesoTE = count($datosModuloEstiloProcesoTE) > 0 ? $datosModuloEstiloProcesoTE : null;

        //ahora para AQL 
        $datosModuloEstiloAQL = $this->getDatosModuloEstiloAQL($fechaActual, $plantaConsulta, null);
        $datosModuloEstiloAQLTE = $this->getDatosModuloEstiloAQL($fechaActual, $plantaConsulta, 1);

        // Verificar si existen datos y asignar null si están vacíos
        $datosModuloEstiloAQL = count($datosModuloEstiloAQL) > 0 ? $datosModuloEstiloAQL : null;
        $datosModuloEstiloAQLTE = count($datosModuloEstiloAQLTE) > 0 ? $datosModuloEstiloAQLTE : null;

        return view('dashboar.dashboardPanta1PorDia', compact(
            'title', 'fechaActual', 'dataModuloAQLGeneral',
            'dataModuloProcesoGeneral', 'generalAQL', 'generalAQLPlanta1',
            'generalProceso', 'generalProcesoPlanta1', 'dataModuloAQLGeneralTE',
            'dataModuloProcesoGeneralTE',
            'datosModuloEstiloProceso', 'datosModuloEstiloProcesoTE',
            'datosModuloEstiloAQL', 'datosModuloEstiloAQLTE',
        ));
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
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->whereHas('tpAuditoriaAQL', function ($query) {
                    $query->where('tp', '!=', 'NINGUNO');
                })
                ->with(['tpAuditoriaAQL' => function ($query) {
                    $query->where('tp', '!=', 'NINGUNO');
                }])
                ->get()
                ->pluck('tpAuditoriaAQL.*.tp')
                ->flatten()
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
                ->implode(', ') ?: 'N/A';

            $operariosUnicos = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->distinct()
                ->pluck('nombre')
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
                ->distinct()
                ->pluck('pieza')
                ->implode(', ');

            //
            // Consultar detalles para cada módulo y estilo
            $detalles = AuditoriaAQL::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->with('tpAuditoriaAQL') // Asegúrate de tener la relación tpAuditoriaAQL
                ->get();

            // Almacenar todos los resultados en el arreglo principal
            $dataModuloEstiloAQL[] = [
                'modulo' => $modulo,
                'estilo' => $estilo,
                'auditoresUnicos' => $auditoresUnicos,
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
                'detalles' => $detalles,
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
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->where('cantidad_rechazada', '>', 0)
                ->distinct()
                ->pluck('nombre')
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

            //
            // Consultar detalles para cada módulo y estilo
            $detalles = AseguramientoCalidad::where('modulo', $modulo)
                ->where('estilo', $estilo)
                ->whereDate('created_at', $fecha)
                ->when(is_null($tiempoExtra), function($query) {
                    return $query->whereNull('tiempo_extra');
                }, function($query) use ($tiempoExtra) {
                    return $query->where('tiempo_extra', $tiempoExtra);
                })
                ->with('tpAseguramientoCalidad') // Asegúrate de tener la relación 
                ->get();

            // Almacenar todos los resultados en el arreglo principal
            $dataModuloEstiloProceso[] = [
                'modulo' => $modulo,
                'estilo' => $estilo,
                'auditoresUnicos' => $auditoresUnicos,
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
                'detalles' => $detalles,
            ];
        }

        // Retornar los datos procesados
        return $dataModuloEstiloProceso;
    }


}
