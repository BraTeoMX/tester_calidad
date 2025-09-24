<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\EncabezadoAuditoriaCorteV2;
use App\Models\AuditoriaCorteMarcada;
use App\Models\AuditoriaCorteTendido;
use App\Models\AuditoriaCorteLectra;
use App\Models\AuditoriaCorteBulto;
use App\Models\AuditoriaCorteFinal;
use Carbon\Carbon;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class ReporteAuditoriaCorteController extends Controller
{
    /**
     * Vista principal del reporte de auditoría de corte
     */
    public function index(Request $request)
    {
        try {
            $pageSlug = 'reporte_auditoria_corte';

            return view('reporteAuditoriaCorte.index', compact('pageSlug'));
        } catch (Exception $e) {
            Log::error('Error al cargar vista de reporte de auditoría de corte: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar el reporte: ' . $e->getMessage());
        }
    }

    /**
     * Método de prueba para verificar la funcionalidad de la tabla
     */
    public function datosPrueba(Request $request)
    {
        try {
            // Crear datos de prueba
            $datosPrueba = [
                [
                    'id' => 1,
                    'orden_id' => 'OP-TEST-001',
                    'estilo_id' => 'EST-001',
                    'cliente_id' => 'CLIENTE-001',
                    'color_id' => 'ROJO',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'estatus' => 'proceso',
                    'evento' => 1,
                    'total_evento' => 3,
                    'yarda_orden' => 'TEST-001',
                    'tallas' => 'S,M,L',
                    'total_piezas' => 100,
                    'bultos' => '10',
                    'porcentaje' => 2.5,
                    'cantidad_defecto' => 3,
                    'codigo_material' => 'MAT-001'
                ],
                [
                    'id' => 2,
                    'orden_id' => 'OP-TEST-002',
                    'estilo_id' => 'EST-002',
                    'cliente_id' => 'CLIENTE-002',
                    'color_id' => 'AZUL',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'estatus' => 'estatusLectra',
                    'evento' => 2,
                    'total_evento' => 3,
                    'yarda_orden' => 'TEST-002',
                    'tallas' => 'M,L,XL',
                    'total_piezas' => 150,
                    'bultos' => '15',
                    'porcentaje' => 1.2,
                    'cantidad_defecto' => 2,
                    'codigo_material' => 'MAT-002'
                ]
            ];

            // Calcular KPIs con datos de prueba
            $kpis = [
                'total_op' => 2,
                'total_piezas' => 250,
                'concentracion_promedio' => 1.85,
                'defectos_criticos' => 0,
                'eficiencia_general' => 100.00
            ];

            // Preparar datos para gráficos
            $graficos = [
                'concentracion_por_op' => [
                    ['op' => 'OP-TEST-001', 'concentracion' => 2.5],
                    ['op' => 'OP-TEST-002', 'concentracion' => 1.2]
                ],
                'distribucion_estatus' => [
                    ['estatus' => 'proceso', 'cantidad' => 1, 'nombre' => 'En Proceso'],
                    ['estatus' => 'estatusLectra', 'cantidad' => 1, 'nombre' => 'Lectra']
                ]
            ];

            // Formatear registros para la tabla
            $registrosFormateados = $this->formatearRegistrosParaTabla(collect($datosPrueba));

            return response()->json([
                'success' => true,
                'kpis' => $kpis,
                'graficos' => $graficos,
                'registros' => $registrosFormateados,
                'total_registros' => 2,
                'message' => 'Datos de prueba generados correctamente'
            ]);
        } catch (Exception $e) {
            Log::error('Error al generar datos de prueba: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al generar datos de prueba: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Consultar datos para el reporte con filtros
     */
    public function consultarDatos(Request $request)
    {
        try {
            $fechaDesde = $request->get('desde');
            $fechaHasta = $request->get('hasta');
            $ordenId = $request->get('op');
            $estatus = $request->get('estatus');

            // Validar fechas
            if ($fechaDesde && $fechaHasta) {
                $fechaDesde = Carbon::createFromFormat('Y-m-d', $fechaDesde)->startOfDay();
                $fechaHasta = Carbon::createFromFormat('Y-m-d', $fechaHasta)->endOfDay();
            } else {
                // Si NO recibes parámetros → tomar semana actual
                $fechaDesde = Carbon::now()->startOfWeek()->startOfDay(); // lunes por default
                $fechaHasta = Carbon::now()->endOfWeek()->endOfDay();     // domingo por default
            }

            // Consulta simplificada para debug - probar con datos de prueba
            $query = EncabezadoAuditoriaCorteV2::query()
                ->select([
                    'auditoria_corte_encabezado.id',
                    'auditoria_corte_encabezado.orden_id',
                    'auditoria_corte_encabezado.estilo_id',
                    'auditoria_corte_encabezado.cliente_id',
                    'auditoria_corte_encabezado.color_id',
                    'auditoria_corte_encabezado.created_at',
                    'auditoria_corte_encabezado.updated_at',
                    'auditoria_corte_encabezado.estatus',
                    'auditoria_corte_encabezado.evento',
                    'auditoria_corte_encabezado.total_evento',
                    // Agregar valores por defecto para campos requeridos
                    DB::raw("'TEST-001' as yarda_orden"),
                    DB::raw("'S,M,L' as tallas"),
                    DB::raw("100 as total_piezas"),
                    DB::raw("'10' as bultos"),
                    DB::raw("2.5 as porcentaje"),
                    DB::raw("3 as cantidad_defecto"),
                    DB::raw("'MAT-001' as codigo_material")
                ]);

            // Agregar joins solo si las tablas existen y tienen datos
            $query->leftJoin('auditoria_corte_marcada', function ($join) {
                $join->on('auditoria_corte_encabezado.id', '=', 'auditoria_corte_marcada.encabezado_id');
            })
                ->leftJoin('auditoria_corte_lectra', function ($join) {
                    $join->on('auditoria_corte_encabezado.id', '=', 'auditoria_corte_lectra.encabezado_id');
                })
                ->leftJoin('auditoria_corte_final', function ($join) {
                    $join->on('auditoria_corte_encabezado.id', '=', 'auditoria_corte_final.encabezado_id');
                })
                ->addSelect([
                    'auditoria_corte_marcada.yarda_orden',
                    'auditoria_corte_marcada.tallas',
                    'auditoria_corte_marcada.total_piezas',
                    'auditoria_corte_marcada.bultos',
                    'auditoria_corte_lectra.porcentaje',
                    'auditoria_corte_lectra.cantidad_defecto',
                    'auditoria_corte_final.aceptado_rechazado',
                    'auditoria_corte_final.aceptado_condicion'
                ]);

            // Aplicar filtros
            if ($fechaDesde && $fechaHasta) {
                $query->whereBetween('auditoria_corte_encabezado.created_at', [$fechaDesde, $fechaHasta]);
            }

            if ($ordenId) {
                $query->where('auditoria_corte_encabezado.orden_id', 'LIKE', '%' . $ordenId . '%');
            }

            if ($estatus) {
                switch ($estatus) {
                    case '1':
                        $query->where('auditoria_corte_final.aceptado_rechazado', 1);
                        break;
                    case '2':
                        $query->where('auditoria_corte_final.aceptado_rechazado', 0)
                            ->whereNotNull('auditoria_corte_final.aceptado_condicion');
                        break;
                    case '3':
                        $query->where('auditoria_corte_final.aceptado_rechazado', 0)
                            ->whereNull('auditoria_corte_final.aceptado_condicion');
                        break;
                }
            }

            // Ordenar por created_at descendente (más reciente primero)
            $query->orderBy('auditoria_corte_encabezado.created_at', 'desc');

            $registros = $query->get();

            // Debug: Log de la consulta y resultados
            Log::info('Reporte Auditoría Corte - Consulta ejecutada', [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings(),
                'total_registros' => $registros->count()
            ]);

            // Calcular KPIs
            $kpis = $this->calcularKPIs($registros);

            // Preparar datos para gráficos
            $graficos = $this->prepararDatosGraficos($registros);

            // Formatear registros para la tabla con datos detallados
            $registrosFormateados = $this->formatearRegistrosParaTabla($registros);

            // Debug: Log de los datos formateados
            Log::info('Reporte Auditoría Corte - Datos formateados', [
                'total_registros_formateados' => count($registrosFormateados),
                'primer_registro' => $registrosFormateados[0] ?? null
            ]);

            $responseData = [
                'success' => true,
                'kpis' => $kpis,
                'graficos' => $graficos,
                'registros' => $registrosFormateados,
                'total_registros' => $registros->count(),
                'message' => 'Datos obtenidos correctamente'
            ];

            // Debug: Log de la respuesta completa
            Log::info('Reporte Auditoría Corte - Respuesta JSON', [
                'success' => $responseData['success'],
                'total_registros' => $responseData['total_registros'],
                'kpis' => $responseData['kpis'],
                'primer_registro_keys' => $registrosFormateados ? array_keys($registrosFormateados[0] ?? []) : []
            ]);

            return response()->json($responseData);
        } catch (Exception $e) {
            Log::error('Error al consultar datos del reporte: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los datos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar registros específicos por OP
     */
    public function buscarPorOP(Request $request)
    {
        try {
            $ordenId = $request->get('op');

            if (!$ordenId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe proporcionar un ID de orden'
                ], 400);
            }

            // Consulta mejorada para buscar por OP con todos los datos detallados
            $registros = EncabezadoAuditoriaCorteV2::where('orden_id', $ordenId)
                ->leftJoin('auditoria_corte_marcada', 'auditoria_corte_encabezado.id', '=', 'auditoria_corte_marcada.encabezado_id')
                ->leftJoin('auditoria_corte_tendido', 'auditoria_corte_encabezado.id', '=', 'auditoria_corte_tendido.encabezado_id')
                ->leftJoin('auditoria_corte_lectra', 'auditoria_corte_encabezado.id', '=', 'auditoria_corte_lectra.encabezado_id')
                ->leftJoin('auditoria_corte_bulto', 'auditoria_corte_encabezado.id', '=', 'auditoria_corte_bulto.encabezado_id')
                ->leftJoin('auditoria_corte_final', 'auditoria_corte_encabezado.id', '=', 'auditoria_corte_final.encabezado_id')
                ->select([
                    'auditoria_corte_encabezado.*',
                    'auditoria_corte_marcada.yarda_orden',
                    'auditoria_corte_marcada.tallas',
                    'auditoria_corte_marcada.total_piezas',
                    'auditoria_corte_marcada.bultos',
                    'auditoria_corte_tendido.codigo_material',
                    'auditoria_corte_tendido.codigo_color',
                    'auditoria_corte_tendido.material_relajado',
                    'auditoria_corte_tendido.empalme',
                    'auditoria_corte_tendido.cara_material',
                    'auditoria_corte_tendido.tono',
                    'auditoria_corte_tendido.yarda_marcada',
                    'auditoria_corte_lectra.porcentaje',
                    'auditoria_corte_lectra.cantidad_defecto',
                    'auditoria_corte_lectra.pieza_inspeccionada',
                    'auditoria_corte_lectra.defecto',
                    'auditoria_corte_bulto.cantidad_bulto',
                    'auditoria_corte_bulto.ingreso_ticket_estatus',
                    'auditoria_corte_bulto.sellado_paquete_estatus',
                    'auditoria_corte_final.aceptado_rechazado',
                    'auditoria_corte_final.aceptado_condicion'
                ])
                ->orderBy('auditoria_corte_encabezado.created_at')
                ->get();

            if ($registros->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron registros para la OP: ' . $ordenId
                ], 404);
            }

            $detalles = $this->formatearDetallesOP($registros);

            // Calcular estadísticas por OP
            $estadisticas = $this->calcularEstadisticasPorOP($registros);

            return response()->json([
                'success' => true,
                'op' => $ordenId,
                'detalles' => $detalles,
                'estadisticas' => $estadisticas,
                'total_eventos' => $registros->count(),
                'eventos' => $registros->pluck('evento')->unique()->values()->toArray(),
                'message' => 'OP encontrada correctamente'
            ]);
        } catch (Exception $e) {
            Log::error('Error al buscar OP: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar la OP: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar datos a Excel
     */
    public function exportarExcel(Request $request)
    {
        try {
            $fechaDesde = $request->get('desde');
            $fechaHasta = $request->get('hasta');
            $ordenId = $request->get('op');
            $estatus = $request->get('estatus');

            // Validar fechas - si no se reciben fechas, usar la semana actual
            if ($fechaDesde && $fechaHasta) {
                $fechaDesde = Carbon::createFromFormat('Y-m-d', $fechaDesde)->startOfDay();
                $fechaHasta = Carbon::createFromFormat('Y-m-d', $fechaHasta)->endOfDay();
            } else {
                // Si no se reciben fechas, usar la semana actual (lunes a domingo)
                $fechaDesde = Carbon::now()->startOfWeek()->startOfDay();
                $fechaHasta = Carbon::now()->endOfWeek()->endOfDay();
            }

            // Consulta para exportación
            $query = EncabezadoAuditoriaCorteV2::query()
                ->leftJoin('auditoria_corte_lectra', 'auditoria_corte_encabezado.id', '=', 'auditoria_corte_lectra.encabezado_id')
                ->leftJoin('auditoria_corte_marcada', 'auditoria_corte_encabezado.id', '=', 'auditoria_corte_marcada.encabezado_id')
                ->leftJoin('auditoria_corte_tendido', 'auditoria_corte_encabezado.id', '=', 'auditoria_corte_tendido.encabezado_id')
                ->leftJoin('auditoria_corte_bulto', 'auditoria_corte_encabezado.id', '=', 'auditoria_corte_bulto.encabezado_id')
                ->leftJoin('auditoria_corte_final', 'auditoria_corte_encabezado.id', '=', 'auditoria_corte_final.encabezado_id')
                ->select([
                    'auditoria_corte_encabezado.orden_id',
                    'auditoria_corte_encabezado.estilo_id',
                    'auditoria_corte_encabezado.cliente_id',
                    'auditoria_corte_encabezado.color_id',
                    'auditoria_corte_encabezado.created_at',
                    'auditoria_corte_encabezado.updated_at',
                    'auditoria_corte_encabezado.estatus',
                    'auditoria_corte_lectra.porcentaje',
                    'auditoria_corte_lectra.cantidad_defecto',
                    'auditoria_corte_marcada.yarda_orden',
                    'auditoria_corte_marcada.tallas',
                    'auditoria_corte_marcada.total_piezas',
                    'auditoria_corte_tendido.codigo_material',
                    'auditoria_corte_tendido.codigo_color',
                    'auditoria_corte_bulto.cantidad_bulto',
                    'auditoria_corte_bulto.ingreso_ticket_estatus',
                    'auditoria_corte_bulto.sellado_paquete_estatus',
                    'auditoria_corte_final.aceptado_rechazado',
                    'auditoria_corte_final.aceptado_condicion'
                ]);

            // Aplicar filtros
            if ($fechaDesde && $fechaHasta) {
                $query->whereBetween('auditoria_corte_encabezado.created_at', [$fechaDesde, $fechaHasta]);
            }

            if ($ordenId) {
                $query->where('auditoria_corte_encabezado.orden_id', 'LIKE', '%' . $ordenId . '%');
            }

            if ($estatus) {
                switch ($estatus) {
                    case '1':
                        $query->where('auditoria_corte_final.aceptado_rechazado', 1);
                        break;
                    case '2':
                        $query->where('auditoria_corte_final.aceptado_rechazado', 0)
                            ->whereNotNull('auditoria_corte_final.aceptado_condicion');
                        break;
                    case '3':
                        $query->where('auditoria_corte_final.aceptado_rechazado', 0)
                            ->whereNull('auditoria_corte_final.aceptado_condicion');
                        break;
                }
            }

            $datos = $query->get();

            // Crear archivo Excel
            $filename = 'reporte_auditoria_corte_' . date('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(
                new class($datos) implements
                    \Maatwebsite\Excel\Concerns\FromCollection,
                    \Maatwebsite\Excel\Concerns\WithHeadings,
                    \Maatwebsite\Excel\Concerns\WithMapping {
                    private $datos;

                    public function __construct($datos)
                    {
                        $this->datos = $datos;
                    }

                    public function collection()
                    {
                        return $this->datos;
                    }

                    public function headings(): array
                    {
                        return [
                            'Orden ID',
                            'Estilo',
                            'Cliente',
                            'Color',
                            'Fecha Creación',
                            'Fecha Actualización',
                            'Estatus',
                            'Porcentaje Concentración',
                            'Cantidad Defectos',
                            'Yarda Orden',
                            'Tallas',
                            'Total Piezas',
                            'Código Material',
                            'Código Color',
                            'Cantidad Bultos',
                            'Ingreso Ticket',
                            'Sellado Paquete',
                            'Aceptado/Rechazado',
                            'Condición Aceptado'
                        ];
                    }

                    public function map($row): array
                    {
                        return [
                            $row->orden_id,
                            $row->estilo_id,
                            $row->cliente_id,
                            $row->color_id,
                            $row->created_at,
                            $row->updated_at,
                            $row->estatus,
                            $row->porcentaje ?? 'N/A',
                            $row->cantidad_defecto ?? 'N/A',
                            $row->yarda_orden ?? 'N/A',
                            $row->tallas ?? 'N/A',
                            $row->total_piezas ?? 'N/A',
                            $row->codigo_material ?? 'N/A',
                            $row->codigo_color ?? 'N/A',
                            $row->cantidad_bulto ?? 'N/A',
                            $row->ingreso_ticket_estatus ?? 'N/A',
                            $row->sellado_paquete_estatus ?? 'N/A',
                            $row->aceptado_rechazado ?? 'N/A',
                            $row->aceptado_condicion ?? 'N/A'
                        ];
                    }
                },
                $filename
            );
        } catch (Exception $e) {
            Log::error('Error al exportar Excel: ' . $e->getMessage());
            return back()->with('error', 'Error al exportar los datos: ' . $e->getMessage());
        }
    }

    /**
     * Calcular KPIs del reporte
     */
    private function calcularKPIs($registros)
    {
        $totalOP = $registros->groupBy('orden_id')->count();

        // Calcular total de piezas de forma segura
        $totalPiezas = $registros->sum(function ($registro) {
            return is_numeric($registro->total_piezas) ? (float) $registro->total_piezas : 0;
        });

        // Calcular concentración promedio de forma segura
        $porcentajesValidos = $registros->filter(function ($registro) {
            return is_numeric($registro->porcentaje);
        });
        $concentracionPromedio = $porcentajesValidos->count() > 0 ? $porcentajesValidos->avg('porcentaje') : 0;

        // Calcular defectos críticos de forma segura
        $defectosCriticos = $porcentajesValidos->filter(function ($registro) {
            return (float) $registro->porcentaje > 5;
        })->count();

        $eficienciaGeneral = $totalOP > 0 ? (($totalOP - $defectosCriticos) / $totalOP) * 100 : 0;

        return [
            'total_op' => $totalOP,
            'total_piezas' => (int) $totalPiezas,
            'concentracion_promedio' => round($concentracionPromedio, 2),
            'defectos_criticos' => $defectosCriticos,
            'eficiencia_general' => round($eficienciaGeneral, 2)
        ];
    }

    /**
     * Preparar datos para gráficos
     */
    private function prepararDatosGraficos($registros)
    {
        // Concentración por OP
        $concentracionPorOP = $registros->groupBy('orden_id')->map(function ($op) {
            $porcentajesValidos = $op->filter(function ($registro) {
                return is_numeric($registro->porcentaje);
            });

            return [
                'op' => $op->first()->orden_id,
                'concentracion' => $porcentajesValidos->count() > 0 ? round($porcentajesValidos->avg('porcentaje'), 2) : 0
            ];
        })->values()->toArray();

        // Distribución de estatus
        $distribucionEstatus = $registros->groupBy('estatus')->map(function ($estatus, $key) {
            return [
                'estatus' => $key,
                'cantidad' => $estatus->count(),
                'nombre' => $this->getNombreEstatus($key)
            ];
        })->values()->toArray();

        return [
            'concentracion_por_op' => $concentracionPorOP,
            'distribucion_estatus' => $distribucionEstatus
        ];
    }

    /**
     * Formatear registros para la tabla
     */
    private function formatearRegistrosParaTabla($registros)
    {
        return $registros->map(function ($registro) {
            // Determinar el estatus más avanzado basado en los datos disponibles
            $estatusAvanzado = $this->determinarEstatusAvanzado($registro);

            return [
                'id' => $registro->id,
                'orden_id' => $registro->orden_id,
                'evento' => $registro->evento ?? 'N/A',
                'total_eventos' => $registro->total_evento ?? 'N/A',
                'estilo_id' => $registro->estilo_id,
                'cliente_id' => $registro->cliente_id,
                'color_id' => $registro->color_id,
                'estatus' => $this->getNombreEstatus($estatusAvanzado),
                'estatus_actual' => $this->getNombreEstatus($registro->estatus),

                // Datos de Auditoría Marcada
                'yarda_orden' => $registro->yarda_orden ?? 'N/A',
                'tallas' => $registro->tallas ?? 'N/A',
                'total_piezas' => is_numeric($registro->total_piezas) ? (int) $registro->total_piezas : 'N/A',
                'bultos' => $registro->bultos ?? 'N/A',
                'largo_trazo' => $registro->largo_trazo ?? 'N/A',
                'ancho_trazo' => $registro->ancho_trazo ?? 'N/A',

                // Datos de Auditoría Tendido
                'codigo_material' => $registro->codigo_material ?? 'N/A',
                'codigo_color' => $registro->codigo_color ?? 'N/A',
                'material_relajado' => $registro->material_relajado ?? 'N/A',
                'empalme' => $registro->empalme ?? 'N/A',
                'cara_material' => $registro->cara_material ?? 'N/A',
                'tono' => $registro->tono ?? 'N/A',
                'yarda_marcada' => $registro->yarda_marcada ?? 'N/A',
                'accion_correctiva' => $registro->accion_correctiva ?? 'N/A',

                // Datos de Concentración (Lectra)
                'concentracion' => is_numeric($registro->porcentaje) ? round((float) $registro->porcentaje, 2) : 'N/A',
                'defectos' => is_numeric($registro->cantidad_defecto) ? (int) $registro->cantidad_defecto : 'N/A',
                'pieza_inspeccionada' => $registro->pieza_inspeccionada ?? 'N/A',
                'defecto' => $registro->defecto ?? 'N/A',
                'estado_validacion' => $registro->estado_validacion ?? 'N/A',
                'nivel_aql' => $registro->nivel_aql ?? 'N/A',

                // Datos de Bulto
                'cantidad_bulto' => $registro->cantidad_bulto ?? 'N/A',
                'pieza_paquete' => $registro->pieza_paquete ?? 'N/A',
                'ingreso_ticket' => $registro->ingreso_ticket ?? 'N/A',
                'ingreso_ticket_estatus' => $registro->ingreso_ticket_estatus ?? 'N/A',
                'sellado_paquete' => $registro->sellado_paquete ?? 'N/A',
                'sellado_paquete_estatus' => $registro->sellado_paquete_estatus ?? 'N/A',
                'bulto_defectos' => is_numeric($registro->bulto_cantidad_defecto) ? (int) $registro->bulto_cantidad_defecto : 'N/A',
                'bulto_porcentaje' => is_numeric($registro->bulto_porcentaje) ? round((float) $registro->bulto_porcentaje, 2) : 'N/A',

                // Datos de Auditoría Final
                'aceptado_rechazado' => $registro->aceptado_rechazado ?? 'N/A',
                'aceptado_condicion' => $registro->aceptado_condicion ?? 'N/A',

                // Información adicional
                'material' => $registro->material ?? 'N/A',
                'pieza' => $registro->pieza ?? 'N/A',
                'qtysched_id' => $registro->qtysched_id ?? 'N/A',
                'trazo' => $registro->trazo ?? 'N/A',
                'lienzo' => $registro->lienzo ?? 'N/A',
                'planta_id' => $registro->planta_id ?? 'N/A',
                'temporada_id' => $registro->temporada_id ?? 'N/A',

                'fecha_creacion' => $registro->created_at ? $registro->created_at->format('d/m/Y H:i') : 'N/A',
                'fecha_actualizacion' => $registro->updated_at ? $registro->updated_at->format('d/m/Y H:i') : 'N/A',
                'acciones' => '<button class="btn btn-sm btn-info" onclick="verDetallesOP(\'' . $registro->orden_id . '\', ' . $registro->id . ')">Ver Detalles</button>'
            ];
        })->toArray();
    }

    /**
     * Calcular estadísticas por OP
     */
    private function calcularEstadisticasPorOP($registros)
    {
        $totalEventos = $registros->count();
        $eventosCompletados = $registros->where('aceptado_rechazado', '!=', null)->count();

        // Calcular concentraciones
        $concentraciones = $registros->filter(function ($registro) {
            return is_numeric($registro->porcentaje);
        });

        $concentracionPromedio = $concentraciones->count() > 0 ? $concentraciones->avg('porcentaje') : 0;
        $concentracionMaxima = $concentraciones->count() > 0 ? $concentraciones->max('porcentaje') : 0;
        $concentracionMinima = $concentraciones->count() > 0 ? $concentraciones->min('porcentaje') : 0;

        // Calcular totales
        $totalPiezas = $registros->sum(function ($registro) {
            return is_numeric($registro->total_piezas) ? (float) $registro->total_piezas : 0;
        });

        $totalBultos = $registros->sum(function ($registro) {
            return is_numeric($registro->cantidad_bulto) ? (float) $registro->cantidad_bulto : 0;
        });

        $totalDefectos = $registros->sum(function ($registro) {
            return is_numeric($registro->cantidad_defecto) ? (float) $registro->cantidad_defecto : 0;
        });

        return [
            'total_eventos' => $totalEventos,
            'eventos_completados' => $eventosCompletados,
            'progreso' => $totalEventos > 0 ? round(($eventosCompletados / $totalEventos) * 100, 2) : 0,
            'concentracion_promedio' => round($concentracionPromedio, 2),
            'concentracion_maxima' => round($concentracionMaxima, 2),
            'concentracion_minima' => round($concentracionMinima, 2),
            'total_piezas' => (int) $totalPiezas,
            'total_bultos' => (int) $totalBultos,
            'total_defectos' => (int) $totalDefectos
        ];
    }

    /**
     * Formatear detalles de OP
     */
    private function formatearDetallesOP($registros)
    {
        return $registros->map(function ($registro) {
            $estatusAvanzado = $this->determinarEstatusAvanzado($registro);

            return [
                'id' => $registro->id,
                'evento' => $registro->evento ?? 'N/A',
                'total_eventos' => $registro->total_evento ?? 'N/A',
                'estatus_actual' => $this->getNombreEstatus($registro->estatus),
                //'estatus_avanzado' => $this->getNombreEstatus($estatusAvanzado),
                'fecha_creacion' => $registro->created_at ? $registro->created_at->format('d/m/Y H:i') : 'N/A',
                'fecha_actualizacion' => $registro->updated_at ? $registro->updated_at->format('d/m/Y H:i') : 'N/A',

                // Información general
                'estilo_id' => $registro->estilo_id,
                'cliente_id' => $registro->cliente_id,
                'color_id' => $registro->color_id,
                'material' => $registro->material ?? 'N/A',
                'pieza' => $registro->pieza ?? 'N/A',
                'qtysched_id' => $registro->qtysched_id ?? 'N/A',
                'trazo' => $registro->trazo ?? 'N/A',
                'lienzo' => $registro->lienzo ?? 'N/A',
                'planta_id' => $registro->planta_id ?? 'N/A',
                'temporada_id' => $registro->temporada_id ?? 'N/A',

                // Auditoría Marcada
                'yarda_orden' => $registro->yarda_orden ?? 'N/A',
                'tallas' => $registro->tallas ?? 'N/A',
                'total_piezas' => is_numeric($registro->total_piezas) ? (int) $registro->total_piezas : 'N/A',
                'bultos' => $registro->bultos ?? 'N/A',

                // Auditoría Tendido
                'codigo_material' => $registro->codigo_material ?? 'N/A',
                'codigo_color' => $registro->codigo_color ?? 'N/A',
                'material_relajado' => $registro->material_relajado ?? 'N/A',
                'empalme' => $registro->empalme ?? 'N/A',
                'cara_material' => $registro->cara_material ?? 'N/A',
                'tono' => $registro->tono ?? 'N/A',
                'yarda_marcada' => $registro->yarda_marcada ?? 'N/A',

                // Concentración (Lectra)
                'concentracion' => is_numeric($registro->porcentaje) ? round((float) $registro->porcentaje, 2) : 'N/A',
                'defectos' => is_numeric($registro->cantidad_defecto) ? (int) $registro->cantidad_defecto : 'N/A',
                'pieza_inspeccionada' => $registro->pieza_inspeccionada ?? 'N/A',
                'defecto' => $registro->defecto ?? 'N/A',

                // Auditoría Bulto
                'cantidad_bulto' => $registro->cantidad_bulto ?? 'N/A',
                'ingreso_ticket_estatus' => $registro->ingreso_ticket_estatus ?? 'N/A',
                'sellado_paquete_estatus' => $registro->sellado_paquete_estatus ?? 'N/A',

                // Auditoría Final
                'aceptado_rechazado' => $registro->aceptado_rechazado ?? 'N/A',
                'aceptado_condicion' => $registro->aceptado_condicion ?? 'N/A',

                // Estado del proceso
                'progreso_etapa' => $this->calcularProgresoEtapa($registro)
            ];
        })->toArray();
    }

    /**
     * Calcular progreso de la etapa actual
     */
    private function calcularProgresoEtapa($registro)
    {
        $progreso = 0;
        $totalCampos = 0;

        // Determinar qué campos verificar según el estatus actual
        switch ($registro->estatus) {
            case 'estatusAuditoriaMarcada':
                $campos = ['yarda_orden', 'tallas', 'total_piezas', 'bultos'];
                break;
            case 'estatusAuditoriaTendido':
                $campos = ['codigo_material', 'codigo_color', 'material_relajado', 'empalme', 'cara_material', 'tono', 'yarda_marcada'];
                break;
            case 'estatusLectra':
                $campos = ['porcentaje', 'cantidad_defecto', 'pieza_inspeccionada', 'defecto'];
                break;
            case 'estatusAuditoriaBulto':
                $campos = ['cantidad_bulto', 'ingreso_ticket_estatus', 'sellado_paquete_estatus'];
                break;
            case 'estatusAuditoriaFinal':
                $campos = ['aceptado_rechazado', 'aceptado_condicion'];
                break;
            default:
                return 0;
        }

        foreach ($campos as $campo) {
            $totalCampos++;
            if (!empty($registro->$campo) || $registro->$campo === '1' || $registro->$campo === 1) {
                $progreso++;
            }
        }

        return $totalCampos > 0 ? round(($progreso / $totalCampos) * 100, 1) : 0;
    }

    /**
     * Determinar el estatus más avanzado basado en los datos disponibles
     */
    private function determinarEstatusAvanzado($registro)
    {
        // Si ya está finalizado, devolver fin
        if ($registro->aceptado_rechazado !== null) {
            return 'fin';
        }

        // Si hay datos de bulto, está en auditoría de bulto
        if ($registro->cantidad_bulto || $registro->ingreso_ticket_estatus || $registro->sellado_paquete_estatus) {
            return 'estatusAuditoriaBulto';
        }

        // Si hay datos de concentración/lectra, está en lectra
        if ($registro->porcentaje !== null || $registro->cantidad_defecto || $registro->pieza_inspeccionada) {
            return 'estatusLectra';
        }

        // Si hay datos de tendido, está en tendido
        if (
            $registro->codigo_material || $registro->codigo_color || $registro->material_relajado ||
            $registro->empalme || $registro->cara_material || $registro->tono || $registro->yarda_marcada
        ) {
            return 'estatusAuditoriaTendido';
        }

        // Si hay datos de marcada, está en marcada
        if ($registro->yarda_orden || $registro->tallas || $registro->total_piezas || $registro->bultos) {
            return 'estatusAuditoriaMarcada';
        }

        // Si no hay datos específicos, mantener el estatus actual
        return $registro->estatus ?? 'proceso';
    }

    /**
     * Obtener nombre del estatus
     */
    private function getNombreEstatus($estatus)
    {
        $estatusMap = [
            'proceso' => 'En Proceso',
            'estatusAuditoriaMarcada' => 'Marcada',
            'estatusAuditoriaTendido' => 'Tendido',
            'estatusLectra' => 'Lectra',
            'estatusAuditoriaBulto' => 'Bulto',
            'estatusAuditoriaFinal' => 'Auditoría Final',
            'fin' => 'Finalizado'
        ];

        return $estatusMap[$estatus] ?? $estatus;
    }
}
