<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\AuditoriaProcesoCorte; 
use App\Models\AseguramientoCalidad;   
use App\Models\AuditoriaAQL;   
use Illuminate\Http\Request;
use Carbon\Carbon;


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

        $fechaInicio1 = $request->input('fecha_inicio', Carbon::now()->format('Y-m-d') . ' 00:00:00');
        $fechaInicio = $fechaInicio1 . ' 00:00:00';
        $fechaFin1 = $request->input('fecha_fin', Carbon::now()->format('Y-m-d') . ' 23:59:59');
        $fechaFin = $fechaFin1 . ' 23:59:59';

        //dd($fechaInicio, $fechaFin);

        //Inicio apartado para detalles generales
        // Obtener clientes y porcentajes de error por cliente
        $clientes = AuditoriaAQL::whereNotNull('cliente')
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->orderBy('cliente')
            ->pluck('cliente')
            ->unique();
        $porcentajesError = [];

        foreach ($clientes as $cliente) {
            $sumaAuditada = AuditoriaAQL::where('cliente', $cliente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('cantidad_auditada');
            $sumaRechazada = AuditoriaAQL::where('cliente', $cliente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('cantidad_rechazada');

            $porcentajeError = ($sumaAuditada != 0) ? ($sumaRechazada / $sumaAuditada) * 100 : 0;

            $porcentajesError[$cliente] = $porcentajeError;
        }
        arsort($porcentajesError);

        // Obtener operarios de máquina, porcentajes de error por operario y otras relaciones por operario
        $nombres = AuditoriaAQL::whereNotNull('modulo')
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->orderBy('modulo')
            ->pluck('modulo')
            ->unique();
        $porcentajesErrorNombre = [];
        $operacionesPorNombre = [];
        $teamLeaderPorNombre = [];
        $moduloPorNombre = [];

        foreach ($nombres as $nombre) {
            $sumaAuditadaNombre = AuditoriaAQL::where('modulo', $nombre)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('cantidad_auditada');
            $sumaRechazadaNombre = AuditoriaAQL::where('modulo', $nombre)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('cantidad_rechazada');

            $porcentajeErrorNombre = ($sumaAuditadaNombre != 0) ? ($sumaRechazadaNombre / $sumaAuditadaNombre) * 100 : 0;

            $porcentajesErrorNombre[$nombre] = $porcentajeErrorNombre;

            // Obtener la operación, el team leader y el módulo correspondientes al operario de máquina
            $operacion = AuditoriaAQL::where('modulo', $nombre)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->value('op');
            $operacionesPorNombre[$nombre] = $operacion;

            $teamleader = AuditoriaAQL::where('modulo', $nombre)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->value('team_leader');
            $teamLeaderPorNombre[$nombre] = $teamleader;

            $moduloPorNombre[$nombre] = $nombre;
        }
        arsort($porcentajesErrorNombre);

        // Obtener team leaders y porcentajes de error por team leader
        $teamLeaders = AuditoriaAQL::where(function($query) {
                $query->whereNull('jefe_produccion')
                    ->orWhere('jefe_produccion', '0');
            })
            ->whereNotNull('team_leader')
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->orderBy('team_leader')
            ->pluck('team_leader')
            ->unique();
        $porcentajesErrorTeamLeader = [];

        foreach ($teamLeaders as $teamLeader) {
            $sumaAuditadaTeamLeader = AuditoriaAQL::where('team_leader', $teamLeader)
                //->whereNull('jefe_produccion')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('cantidad_auditada');
            $sumaRechazadaTeamLeader = AuditoriaAQL::where('team_leader', $teamLeader)
                //->whereNull('jefe_produccion')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('cantidad_rechazada');

            $porcentajeErrorTeamLeader = ($sumaAuditadaTeamLeader != 0) ? ($sumaRechazadaTeamLeader / $sumaAuditadaTeamLeader) * 100 : 0;

            $porcentajesErrorTeamLeader[$teamLeader] = $porcentajeErrorTeamLeader;
        }
        arsort($porcentajesErrorTeamLeader);

        //para jefes de produccion
        // Obtener team leaders y porcentajes de error por team leader
        $jefesProduccion = AuditoriaAQL::whereNotNull('team_leader')
            ->where('jefe_produccion', 1)
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->orderBy('team_leader')
            ->pluck('team_leader')
            ->unique();
        $porcentajesErrorJefeProduccion = [];

        foreach ($jefesProduccion as $jefeProduccion) {
            $sumaAuditadaJefeProduccion = AuditoriaAQL::where('team_leader', $jefeProduccion)
                ->where('jefe_produccion', 1)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('cantidad_auditada');
            $sumaRechazadaJefeProduccion = AuditoriaAQL::where('team_leader', $jefeProduccion)
                ->where('jefe_produccion', 1)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('cantidad_rechazada');

            $porcentajeErrorJefeProduccion = ($sumaAuditadaJefeProduccion != 0) ? ($sumaRechazadaJefeProduccion / $sumaAuditadaJefeProduccion) * 100 : 0;

            $porcentajesErrorJefeProduccion[$jefeProduccion] = $porcentajeErrorJefeProduccion;
        }
        arsort($porcentajesErrorJefeProduccion);

        //Fin de apartado para detalles generales


        //Inicio apartado para detalles para Planta 1
        // Obtener clientesPlanta1 y porcentajes de error por cliente
        //apartado para mostrar datos de clientes de prodduccion, en este caso por dia AseguramientoCalidad y AuditoriaAQL
        $clientesAQLPlanta1 = AuditoriaAQL::whereNotNull('cliente')
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('planta', 'Intimark1')
            ->pluck('cliente');

        $clientesProcesoPlanta1 = AseguramientoCalidad::whereNotNull('cliente')
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('planta', 'Intimark1')
            ->pluck('cliente');

        $clientesPlanta1 = $clientesAQLPlanta1->merge($clientesProcesoPlanta1)->unique();

        
        $dataClientePlanta1 = [];
        $totalPorcentajeErrorAQL = 0;
        $totalPorcentajeErrorProceso =0;
        //dd($clientesAQLPlanta1, $clientesProcesoPlanta1, $clientesPlanta1);
        foreach ($clientesPlanta1 as $cliente) {
            $sumaAuditadaAQL = AuditoriaAQL::where('cliente', $cliente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark1')
                ->sum('cantidad_auditada');
            $sumaRechazadaAQL = AuditoriaAQL::where('cliente', $cliente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark1')
                ->sum('cantidad_rechazada');
        
            $porcentajeErrorAQL = ($sumaAuditadaAQL != 0) ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100 : 0;
        
            $sumaAuditadaProceso = AseguramientoCalidad::where('cliente', $cliente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark1')
                ->sum('cantidad_auditada');
            $sumaRechazadaProceso = AseguramientoCalidad::where('cliente', $cliente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark1')
                ->sum('cantidad_rechazada');
        
            $porcentajeErrorProceso = ($sumaAuditadaProceso != 0) ? ($sumaRechazadaProceso / $sumaAuditadaProceso) * 100 : 0;


            $totalAuditadaAQL = $clientesAQLPlanta1->sum(function ($cliente) use ($fechaInicio, $fechaFin) {
                return AuditoriaAQL::where('cliente', $cliente)
                    ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                    ->where('planta', 'Intimark1')
                    ->sum('cantidad_auditada');
            });
            
            $totalRechazadaAQL = $clientesAQLPlanta1->sum(function ($cliente) use ($fechaInicio, $fechaFin) {
                return AuditoriaAQL::where('cliente', $cliente)
                    ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                    ->where('planta', 'Intimark1')
                    ->sum('cantidad_rechazada');
            });
            
            $totalAuditadaProceso = $clientesProcesoPlanta1->sum(function ($cliente) use ($fechaInicio, $fechaFin) {
                return AseguramientoCalidad::where('cliente', $cliente)
                    ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                    ->where('planta', 'Intimark1')
                    ->sum('cantidad_auditada');
            });
            
            $totalRechazadaProceso = $clientesProcesoPlanta1->sum(function ($cliente) use ($fechaInicio, $fechaFin) {
                return AseguramientoCalidad::where('cliente', $cliente)
                    ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                    ->where('planta', 'Intimark1')
                    ->sum('cantidad_rechazada');
            });
            
            $totalPorcentajeErrorAQL = ($totalAuditadaAQL != 0) ? ($totalRechazadaAQL / $totalAuditadaAQL) * 100 : 0;
            $totalPorcentajeErrorProceso = ($totalAuditadaProceso != 0) ? ($totalRechazadaProceso / $totalAuditadaProceso) * 100 : 0;
            

            $dataClientePlanta1[] = [
                'cliente' => $cliente,
                'porcentajeErrorProceso' => $porcentajeErrorProceso,
                'porcentajeErrorAQL' => $porcentajeErrorAQL,
            ];

        }

        // Obtener operarios de máquina, porcentajes de error por operario y otras relaciones por operario
        $modulosPlanta1 = AuditoriaAQL::whereNotNull('modulo')
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('planta', 'Intimark1')
            ->orderBy('modulo')
            ->pluck('modulo')
            ->unique();
        $porcentajesErrorModuloPlanta1 = [];
        $operacionesPorModuloPlanta1 = [];
        $teamLeaderPorModuloPlanta1 = [];
        $moduloPorModuloPlanta1 = [];

        foreach ($modulosPlanta1 as $modulo) {
            $sumaAuditadaModulo = AuditoriaAQL::where('modulo', $modulo)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark1')
                ->sum('cantidad_auditada');
            $sumaRechazadaModulo = AuditoriaAQL::where('modulo', $modulo)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('cantidad_rechazada');

            $porcentajeErrorModuloPlanta1 = ($sumaAuditadaModulo != 0) ? ($sumaRechazadaModulo / $sumaAuditadaModulo) * 100 : 0;

            $porcentajesErrorModuloPlanta1[$modulo] = $porcentajeErrorModuloPlanta1;

            // Obtener la operación, el team leader y el módulo correspondientes al operario de máquina
            $operacion = AuditoriaAQL::where('modulo', $modulo)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark1')
                ->value('op');
            $operacionesPorModuloPlanta1[$modulo] = $operacion;

            $teamleader = AuditoriaAQL::where('modulo', $modulo)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark1')
                ->value('team_leader');
            $teamLeaderPorModuloPlanta1[$modulo] = $teamleader;

            $moduloPorModuloPlanta1[$modulo] = $modulo;
        }
        arsort($porcentajesErrorModuloPlanta1);

        // Obtener team leaders y porcentajes de error por team leader
        $teamLeadersPlanta1 = AuditoriaAQL::where(function($query) {
                $query->whereNull('jefe_produccion')
                    ->orWhere('jefe_produccion', '0');
            })
            ->whereNotNull('team_leader')
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('planta', 'Intimark1')
            ->orderBy('team_leader')
            ->pluck('team_leader')
            ->unique();
        $porcentajesErrorTeamLeaderPlanta1 = [];

        foreach ($teamLeadersPlanta1 as $teamLeader) {
            $sumaAuditadaTeamLeader = AuditoriaAQL::where('team_leader', $teamLeader)
                //->whereNull('jefe_produccion')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark1')
                ->sum('cantidad_auditada');
            $sumaRechazadaTeamLeader = AuditoriaAQL::where('team_leader', $teamLeader)
                //->whereNull('jefe_produccion')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark1')
                ->sum('cantidad_rechazada');

            $porcentajeErrorTeamLeaderPlanta1 = ($sumaAuditadaTeamLeader != 0) ? ($sumaRechazadaTeamLeader / $sumaAuditadaTeamLeader) * 100 : 0;

            $porcentajesErrorTeamLeaderPlanta1[$teamLeader] = $porcentajeErrorTeamLeaderPlanta1;
        }
        arsort($porcentajesErrorTeamLeaderPlanta1);

        //para jefes de produccion
        // Obtener team leaders y porcentajes de error por team leader
        $jefesProduccionPlanta1 = AuditoriaAQL::whereNotNull('team_leader')
            ->where('jefe_produccion', 1)
            ->where('planta', 'Intimark1')
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->orderBy('team_leader')
            ->pluck('team_leader')
            ->unique();
        $porcentajesErrorJefeProduccionPlanta1 = [];

        foreach ($jefesProduccionPlanta1 as $jefeProduccion) {
            $sumaAuditadaJefeProduccion = AuditoriaAQL::where('team_leader', $jefeProduccion)
                ->where('jefe_produccion', 1)
                ->where('planta', 'Intimark1')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('cantidad_auditada');
            $sumaRechazadaJefeProduccion = AuditoriaAQL::where('team_leader', $jefeProduccion)
                ->where('jefe_produccion', 1)
                ->where('planta', 'Intimark1')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('cantidad_rechazada');

            $porcentajeErrorJefeProduccion = ($sumaAuditadaJefeProduccion != 0) ? ($sumaRechazadaJefeProduccion / $sumaAuditadaJefeProduccion) * 100 : 0;

            $porcentajesErrorJefeProduccionPlanta1[$jefeProduccion] = $porcentajeErrorJefeProduccion;
        }
        arsort($porcentajesErrorJefeProduccionPlanta1);



        //detalles: 
        //apartado para mostrar datos de gerente de prodduccion, en este caso por dia AseguramientoCalidad y AuditoriaAQL
        $gerentesProduccionAQL = AuditoriaAQL::where('jefe_produccion', 1)
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('planta', 'Intimark1')
            ->select('team_leader')
            ->distinct()
            ->pluck('team_leader')
            ->all();

        $gerentesProduccionProceso = AseguramientoCalidad::where('jefe_produccion', 1)
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('planta', 'Intimark1')
            ->select('team_leader')
            ->distinct()
            ->pluck('team_leader')
            ->all();

        $gerentesProduccion = array_unique(array_merge($gerentesProduccionAQL, $gerentesProduccionProceso));

        
        $dataGerentes = [];
        $dataGerentesTotales = collect();
        //dd($gerentesProduccionAQL, $gerentesProduccionProceso, $gerentesProduccion);
        foreach ($gerentesProduccion as $gerente) {
            $modulosUnicosAQL = AuditoriaAQL::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark1')
                ->select('modulo')
                ->distinct()
                ->get()
                ->pluck('modulo');
        
            $modulosUnicosProceso = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark1')
                ->select('modulo')
                ->distinct()
                ->get()
                ->pluck('modulo');
        
            $modulosUnicos = count(array_unique(array_merge($modulosUnicosAQL->toArray(), $modulosUnicosProceso->toArray())));
        
            $sumaAuditadaAQL = AuditoriaAQL::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark1')
                ->sum('cantidad_auditada');
        
            $sumaRechazadaAQL = AuditoriaAQL::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark1')
                ->sum('cantidad_rechazada');
        
            $sumaAuditadaProceso = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark1')
                ->sum('cantidad_auditada');
        
            $sumaRechazadaProceso = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark1')
                ->sum('cantidad_rechazada');
        
            $porcentajeErrorAQL = ($sumaAuditadaAQL != 0) ? ($sumaRechazadaAQL / $sumaAuditadaAQL) * 100 : 0;
            $porcentajeErrorProceso = ($sumaAuditadaProceso != 0) ? ($sumaRechazadaProceso / $sumaAuditadaProceso) * 100 : 0;

            $conteoOperario = AseguramientoCalidad::where('team_leader', $gerente)
                ->where('utility', null)
                ->where('planta', 'Intimark1')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->distinct('nombre')
                ->count('nombre');
            $conteoUtility = AseguramientoCalidad::where('team_leader', $gerente)
                ->where('utility', 1)
                ->where('planta', 'Intimark1')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->distinct('nombre')
                ->count('nombre');
            $conteoMinutos = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark1')
                ->count('minutos_paro');

            $sumaMinutos = AseguramientoCalidad::where('team_leader', $gerente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark1')
                ->sum('minutos_paro');

            $promedioMinutos = $conteoMinutos != 0 ? $sumaMinutos / $conteoMinutos : 0;
            $promedioMinutosEntero = ceil($promedioMinutos);
            $dataGerentes[] = [
                'team_leader' => $gerente,
                'modulos_unicos' => $modulosUnicos,
                'porcentaje_error_aql' => $porcentajeErrorAQL,
                'porcentaje_error_proceso' => $porcentajeErrorProceso,
                'conteoOperario' => $conteoOperario,
                'conteoUtility' => $conteoUtility,
                'conteoMinutos' => $conteoMinutos,
                'sumaMinutos' => $sumaMinutos,
                'promedioMinutosEntero' => $promedioMinutosEntero
            ];

            $dataGerentesTotales = collect($dataGerentes);
        }

        //dd($dataGerentes);

        //Fin de apartado para detalles para Planta 1


        //Inicio apartado para detalles para Planta 2
        // Obtener clientesPlanta2 y porcentajes de error por cliente
        $clientesPlanta2 = AuditoriaAQL::whereNotNull('cliente')
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('planta', 'Intimark2')
            ->orderBy('cliente')
            ->pluck('cliente')
            ->unique();
        $porcentajesErrorPlanta2 = [];

        foreach ($clientesPlanta2 as $cliente) {
            $sumaAuditada = AuditoriaAQL::where('cliente', $cliente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark2')
                ->sum('cantidad_auditada');
            $sumaRechazada = AuditoriaAQL::where('cliente', $cliente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark2')
                ->sum('cantidad_rechazada');

            $porcentajeError = ($sumaAuditada != 0) ? ($sumaRechazada / $sumaAuditada) * 100 : 0;

            $porcentajesErrorPlanta2[$cliente] = $porcentajeError;
        }
        arsort($porcentajesErrorPlanta2);

        // Obtener operarios de máquina, porcentajes de error por operario y otras relaciones por operario
        $modulosPlanta2 = AuditoriaAQL::whereNotNull('modulo')
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('planta', 'Intimark2')
            ->orderBy('modulo')
            ->pluck('modulo')
            ->unique();
        $porcentajesErrorModuloPlanta2 = [];
        $operacionesPorModuloPlanta2 = [];
        $teamLeaderPorModuloPlanta2 = [];
        $moduloPorModuloPlanta2 = [];

        foreach ($modulosPlanta2 as $modulo) {
            $sumaAuditadaModulo = AuditoriaAQL::where('modulo', $modulo)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark2')
                ->sum('cantidad_auditada');
            $sumaRechazadaModulo = AuditoriaAQL::where('modulo', $modulo)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('cantidad_rechazada');

            $porcentajeErrorModuloPlanta2 = ($sumaAuditadaModulo != 0) ? ($sumaRechazadaModulo / $sumaAuditadaModulo) * 100 : 0;

            $porcentajesErrorModuloPlanta2[$modulo] = $porcentajeErrorModuloPlanta2;

            // Obtener la operación, el team leader y el módulo correspondientes al operario de máquina
            $operacion = AuditoriaAQL::where('modulo', $modulo)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark2')
                ->value('op');
            $operacionesPorModuloPlanta2[$modulo] = $operacion;

            $teamleader = AuditoriaAQL::where('modulo', $modulo)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark2')
                ->value('team_leader');
            $teamLeaderPorModuloPlanta2[$modulo] = $teamleader;

            $moduloPorModuloPlanta2[$modulo] = $modulo;
        }
        arsort($porcentajesErrorModuloPlanta2);

        // Obtener team leaders y porcentajes de error por team leader
        $teamLeadersPlanta2 = AuditoriaAQL::where(function($query) {
                $query->whereNull('jefe_produccion')
                    ->orWhere('jefe_produccion', '0');
            })
            ->whereNotNull('team_leader')
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('planta', 'Intimark2')
            ->orderBy('team_leader')
            ->pluck('team_leader')
            ->unique();
        $porcentajesErrorTeamLeaderPlanta2 = [];

        foreach ($teamLeadersPlanta2 as $teamLeader) {
            $sumaAuditadaTeamLeader = AuditoriaAQL::where('team_leader', $teamLeader)
                //->whereNull('jefe_produccion')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark2')
                ->sum('cantidad_auditada');
            $sumaRechazadaTeamLeader = AuditoriaAQL::where('team_leader', $teamLeader)
                //->whereNull('jefe_produccion')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('planta', 'Intimark2')
                ->sum('cantidad_rechazada');

            $porcentajeErrorTeamLeaderPlanta2 = ($sumaAuditadaTeamLeader != 0) ? ($sumaRechazadaTeamLeader / $sumaAuditadaTeamLeader) * 100 : 0;

            $porcentajesErrorTeamLeaderPlanta2[$teamLeader] = $porcentajeErrorTeamLeaderPlanta2;
        }
        arsort($porcentajesErrorTeamLeaderPlanta2);

        //para jefes de produccion
        // Obtener team leaders y porcentajes de error por team leader
        $jefesProduccionPlanta2 = AuditoriaAQL::whereNotNull('team_leader')
            ->where('jefe_produccion', 1)
            ->where('planta', 'Intimark2')
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->orderBy('team_leader')
            ->pluck('team_leader')
            ->unique();
        $porcentajesErrorJefeProduccionPlanta2 = [];

        foreach ($jefesProduccionPlanta2 as $jefeProduccion) {
            $sumaAuditadaJefeProduccion = AuditoriaAQL::where('team_leader', $jefeProduccion)
                ->where('jefe_produccion', 1)
                ->where('planta', 'Intimark2')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('cantidad_auditada');
            $sumaRechazadaJefeProduccion = AuditoriaAQL::where('team_leader', $jefeProduccion)
                ->where('jefe_produccion', 1)
                ->where('planta', 'Intimark2')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('cantidad_rechazada');

            $porcentajeErrorJefeProduccion = ($sumaAuditadaJefeProduccion != 0) ? ($sumaRechazadaJefeProduccion / $sumaAuditadaJefeProduccion) * 100 : 0;

            $porcentajesErrorJefeProduccionPlanta2[$jefeProduccion] = $porcentajeErrorJefeProduccion;
        }
        arsort($porcentajesErrorJefeProduccionPlanta2);

        //Fin de apartado para detalles para Planta 2

        return view('dashboar.dashboarAProcesoAQL', compact('title', 'fechaInicio', 'fechaFin',
            'clientes', 'porcentajesError',
            'nombres', 'porcentajesErrorNombre', 'operacionesPorNombre', 'teamLeaderPorNombre', 'moduloPorNombre',
            'teamLeaders', 'porcentajesErrorTeamLeader',
            'jefesProduccion', 'porcentajesErrorJefeProduccion',
            'modulosPlanta1', 'porcentajesErrorModuloPlanta1', 'operacionesPorModuloPlanta1', 'teamLeaderPorModuloPlanta1', 'moduloPorModuloPlanta1',
            'teamLeadersPlanta1', 'porcentajesErrorTeamLeaderPlanta1',
            'jefesProduccionPlanta1', 'porcentajesErrorJefeProduccionPlanta1',
            'clientesPlanta2', 'porcentajesErrorPlanta2',
            'modulosPlanta2', 'porcentajesErrorModuloPlanta2', 'operacionesPorModuloPlanta2', 'teamLeaderPorModuloPlanta2', 'moduloPorModuloPlanta2',
            'teamLeadersPlanta2', 'porcentajesErrorTeamLeaderPlanta2',
            'jefesProduccionPlanta2', 'porcentajesErrorJefeProduccionPlanta2',
            'dataGerentes', 'dataGerentesTotales', 
            'dataClientePlanta1', 'totalPorcentajeErrorAQL', 'totalPorcentajeErrorProceso'));
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
