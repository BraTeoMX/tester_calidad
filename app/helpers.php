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
            $result = DB::connection('sqlsrv')
                ->table('CountsApprov_views')
                ->select('Total_QTY', 'QUALITY', 'PRODPOOLID', 'TRANSDATE')
                ->get();

            // Loguea la cantidad de registros obtenidos
            Log::info('Cantidad de registros obtenidos en CountsApprov_views: ' . $result);

            return $result;
        } catch (\Exception $e) {
            // Manejar la excepción, por ejemplo, loguear el error
            Log::error('Error al obtener SegundasTerceras de CountsApprov_views: ' . $e->getMessage());

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
                    ->select('OPRMODULEID_AT', 'PRODPOOLID', 'CUSTOMERNAME', 'DIVISIONNAME', 'TipoSegunda', 'DescripcionCalidad', 'PRODTICKETID', 'QTY', 'TRANSDATE')
                    ->where('Calidad', 'Segunda') // Filtrar por Calidad = 'Segunda'
                    ->get();

                // Mapear los datos para aplicar la transformación a PRODPOOLID
                $segundasTransformadas = $segundas->map(function ($item) {
                    if (strcasecmp($item->PRODPOOLID, 'intimark1') === 0) {
                        $item->PRODPOOLID = 'Planta Ixtlahuaca';
                    } elseif (strcasecmp($item->PRODPOOLID, 'intimark2') === 0) {
                        $item->PRODPOOLID = 'Planta San Bartolo';
                    }
                    return $item;
                });

                // Loguea la cantidad de registros obtenidos
                Log::info('Cantidad de registros obtenidos en SegundasTerceras_View: ' . $segundasTransformadas->count());

                return $segundasTransformadas;
            });
        } catch (\Exception $e) {
            // Manejar la excepción, por ejemplo, loguear el error
            Log::error('Error al obtener SegundasTerceras: ' . $e->getMessage());

            // Retornar una colección vacía o lanzar una excepción personalizada
            return collect();
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
                    ->distinct()
                    ->select('OPRMODULEID_AT')
                    ->where('Calidad', 'Segunda')
                    ->orderBy('OPRMODULEID_AT')
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
                    ->select('CUSTOMERNAME', 'DIVISIONNAME', 'OPRMODULEID_AT')
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

