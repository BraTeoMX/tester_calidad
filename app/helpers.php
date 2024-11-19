<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;


if (!function_exists('obtenerSegundasTerceras')) {
    /**
     * Obtiene datos de la vista SegundasTerceras_View.
     *
     * @return Collection
     */
    function obtenerSegundasTerceras(): Collection
    {
        try {
            return Cache::remember('segundas_terceras', 1800, function() {
                $result = DB::connection('sqlsrv')
                    ->table('SegundasTerceras_View')
                    ->select('Calidad', 'QTY')
                    ->get();

                // Loguea la cantidad de registros obtenidos
                Log::info('Cantidad de registros obtenidos en SegundasTerceras_View: ' . $result);

                return $result;
            });
        } catch (\Exception $e) {
            // Manejar la excepción, por ejemplo, loguear el error
            Log::error('Error al obtener SegundasTerceras: ' . $e->getMessage());

            // Retornar una colección vacía o lanzar una excepción personalizada
            return collect();
        }
    }

}
if (!function_exists('ObtenerSegundas')) {
    /**
     * Obtiene datos de la vista SegundasTerceras_View.
     *
     * @return Collection
     */
    function ObtenerSegundas(): Collection
    {
        try {
            return Cache::remember('ObtenerSegundas', 1800, function() {
                $segundas = DB::connection('sqlsrv')
                    ->table('SegundasTerceras_View')
                    ->select('OPRMODULEID_AT','CUSTOMERNAME','DIVISIONNAME','TipoSegunda','DescripcionCalidad','PRODTICKETID', 'QTY','TRANSDATE')
                    ->where('Calidad', 'Segunda') // Filtrar por Calidad = 'Segunda'
                    ->get();

                // Loguea la cantidad de registros obtenidos
                Log::info('Cantidad de registros obtenidos en SegundasTerceras_View: ' . $segundas);

                return $segundas;
            });
        } catch (\Exception $e) {
            // Manejar la excepción, por ejemplo, loguear el error
            Log::error('Error al obtener SegundasTerceras: ' . $e->getMessage());

            // Retornar una colección vacía o lanzar una excepción personalizada
            return collect();
        }
    }
}
if (!function_exists('ObtenerPlantas')) {
    /**
     * Obtiene nombres de plantas únicos basados en la columna [PRODPOOLID] de la vista SegundasTerceras_View.
     *
     * @return array
     */
    function ObtenerPlantas(): array
    {
        try {
            return Cache::remember('ObtenerPlantas', 1800, function () {
                $resultados = DB::connection('sqlsrv')
                    ->table('SegundasTerceras_View')
                    ->select('PRODPOOLID')
                    ->where('Calidad', 'Segunda')
                    ->pluck('PRODPOOLID');

                Log::info('Resultados de la consulta: ' . json_encode($resultados));

                $plantas = $resultados->unique()->map(function ($item) {
                    $lowerItem = strtolower($item);
                    switch ($lowerItem) {
                        case 'intimark1':
                            return 'Planta Ixtlahuca';
                        case 'intimark2':
                            return 'Planta San Bartolo';
                        default:
                            return null;
                    }
                })->filter();

                Log::info('Datos plantas: ' . json_encode($plantas));

                return $plantas->unique()->values()->toArray();
            });
        } catch (QueryException $e) {
            Log::error('Error en la consulta SQL al obtener Plantas: ' . $e->getMessage());
            throw new \Exception('Error al obtener los datos de Plantas.');
        } catch (\Exception $e) {
            Log::error('Error al obtener Plantas: ' . $e->getMessage());
            throw new \Exception('Error al obtener los datos de Plantas.');
        }
    }
}
if (!function_exists('ObtenerModulos')) {
    /**
     * Obtiene módulos únicos basados en la columna OPRMODULEID_AT de la vista correspondiente.
     *
     * @return array
     */
    function ObtenerModulos(): array
    {
        try {
            return Cache::remember('ObtenerModulos', 1800, function () {
                // Consulta optimizada con DISTINCT
                $resultados = DB::connection('sqlsrv')
                    ->table('SegundasTerceras_View')
                    ->selectRaw('DISTINCT OPRMODULEID_AT')
                    ->where('Calidad', 'Segunda')
                    ->pluck('OPRMODULEID_AT');

                Log::info('Resultados únicos de la consulta módulos: ' . json_encode($resultados));

                return $resultados->toArray();
            });
        } catch (QueryException $e) {
            Log::error('Error en la consulta SQL al obtener Módulos: ' . $e->getMessage());
            throw new \Exception('Error al obtener los datos de Módulos.');
        } catch (\Exception $e) {
            Log::error('Error al obtener Módulos: ' . $e->getMessage());
            throw new \Exception('Error al obtener los datos de Módulos.');
        }
    }
}
if (!function_exists('ObtenerClientes')) {
    /**
     * Obtiene clientes y sus divisiones basados en las columnas CUSTOMERNAME y DIVISIONNAME.
     *
     * @return array
     */
    function ObtenerClientes(): array
    {
        try {
            return Cache::remember('ObtenerClientes', 1800, function () {
                $Clientes = DB::connection('sqlsrv')
                    ->table('SegundasTerceras_View')
                    ->distinct()
                    ->select('CUSTOMERNAME', 'DIVISIONNAME')
                    ->where('Calidad', 'Segunda')
                    ->get();

                Log::info('Resultados de la consulta Clientes: ' . json_encode($Clientes));

                $ClientesDivisiones = $Clientes->groupBy('CUSTOMERNAME')->map(function ($items) {
                    return $items->pluck('DIVISIONNAME')->unique()->values()->toArray();
                })->toArray();

                Log::info('Clientes y sus divisiones: ' . json_encode($ClientesDivisiones));

                return $ClientesDivisiones;
            });
        } catch (QueryException $e) {
            Log::error('Error en la consulta SQL al obtener Clientes: ' . $e->getMessage());
            throw new \Exception('Error al obtener los datos de Clientes.');
        } catch (\Exception $e) {
            Log::error('Error al obtener Clientes: ' . $e->getMessage());
            throw new \Exception('Error al obtener los datos de Clientes.');
        }
    }
}
if (!function_exists('ObtenerTipoSegundas')) {
    /**
     * Obtiene clientes únicos basados en la columna CUSTOMERNAME de la vista correspondiente.
     *
     * @return array
     */
    function ObtenerTipoSegundas(): array
    {
        try {
            return Cache::remember('ObtenerTipoSegundas', 1800, function () {
                $TipoSegundas = DB::connection('sqlsrv')
                    ->table('SegundasTerceras_View')
                    ->select('TipoSegunda')
                    ->where('Calidad', 'Segunda')
                    ->pluck('TipoSegunda');

                Log::info('Resultados de Tipos Segundas: ' . json_encode($TipoSegundas));

                $ObtenerTipoSegundas = $TipoSegundas->unique()->values()->toArray();

                Log::info('Clientes únicos filtrados de Tipos Segundas:: ' . json_encode($ObtenerTipoSegundas));

                return $ObtenerTipoSegundas;
            });
        } catch (QueryException $e) {
            Log::error('Error en la consulta SQL al obtener Tipos Segundas: ' . $e->getMessage());
            throw new \Exception('Error al obtener de Tipos Segundas.');
        } catch (\Exception $e) {
            Log::error('Error al obtenerde Tipos Segundas: ' . $e->getMessage());
            throw new \Exception('Error al obtener los datos de Tipos Segundas.');
        }
    }
}
if (!function_exists('ObtenerDescriptionSegundas')) {
    /**
     * Obtiene clientes únicos basados en la columna CUSTOMERNAME de la vista correspondiente.
     *
     * @return array
     */
    function ObtenerDescriptionSegundas(): array
    {
        try {
            return Cache::remember('ObtenerDescriptionSegundas', 1800, function () {
                $DescriptionSegundas = DB::connection('sqlsrv')
                    ->table('SegundasTerceras_View')
                    ->select('DescripcionCalidad')
                    ->where('Calidad', 'Segunda')
                    ->pluck('DescripcionCalidad');

                Log::info('Resultados DescriptionSegundas: ' . json_encode($DescriptionSegundas));

                $ObtenerDescriptionSegundas = $DescriptionSegundas->unique()->values()->toArray();

                Log::info('Clientes únicos filtrados DescriptionSegundas: ' . json_encode($ObtenerDescriptionSegundas));

                return $ObtenerDescriptionSegundas;
            });
        } catch (QueryException $e) {
            Log::error('Error en la consulta SQL al obtener DescriptionSegundas: ' . $e->getMessage());
            throw new \Exception('Error al obtener DescriptionSegundas');
        } catch (\Exception $e) {
            Log::error('Error al obtener DescriptionSegundas: ' . $e->getMessage());
            throw new \Exception('Error al obtener DescriptionSegundas.');
        }
    }
}
if (!function_exists('ObtenerTickets')) {
    /**
     * Obtiene clientes únicos basados en la columna CUSTOMERNAME de la vista correspondiente.
     *
     * @return array
     */
    function ObtenerTickets(): array
    {
        try {
            return Cache::remember('ObtenerTickets', 1800, function () {
                $Tickets = DB::connection('sqlsrv')
                    ->table('SegundasTerceras_View')
                    ->select('PRODTICKETID')
                    ->where('Calidad', 'Segunda')
                    ->pluck('PRODTICKETID');

                Log::info('Resultados de ObtenerTickets: ' . json_encode($Tickets));

                $ObtenerTickets= $Tickets->unique()->values()->toArray();

                Log::info('Clientes únicos filtrados de ObtenerTickets: ' . json_encode($ObtenerTickets));

                return $ObtenerTickets;
            });
        } catch (QueryException $e) {
            Log::error('Error en la consulta SQL al ObtenerTickets: ' . $e->getMessage());
            throw new \Exception('Error al obtener de ObtenerTickets.');
        } catch (\Exception $e) {
            Log::error('Error al obtener de ObtenerTickets: ' . $e->getMessage());
            throw new \Exception('Error al obtener los datos ObtenerTickets.');
        }
    }
}
