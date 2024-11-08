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
                return DB::connection('sqlsrv')
                    ->table('SegundasTerceras_View')
                    ->get();
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
                return DB::connection('sqlsrv')
                    ->table('SegundasTerceras_View')
                    ->where('Calidad', 'Segunda') // Filtrar por Calidad = 'Segunda'
                    ->get();
            });
        } catch (\Exception $e) {
            // Manejar la excepción, por ejemplo, loguear el error
            Log::error('Error al obtener Segundas: ' . $e->getMessage());

            // Retornar una colección vacía o lanzar una excepción personalizada
            return collect();
        }
    }
}
if (!function_exists('ObtenerTerceras')) {
    /**
     * Obtiene datos de la vista SegundasTerceras_View.
     *
     * @return Collection
     */
    function ObtenerTerceras(): Collection
    {
        try {
            return Cache::remember('ObtenerTerceras', 1800, function() {
                return DB::connection('sqlsrv')
                    ->table('SegundasTerceras_View')
                    ->where('Calidad', 'Tercera') // Filtrar por Calidad = 'Tercera'
                    ->get();

            });
        } catch (QueryException $e) {
            Log::error('Error en la consulta SQL al obtener Terceras: ' . $e->getMessage());
            throw new \Exception('Error al obtener los datos de Terceras.');
        } catch (\Exception $e) {
            Log::error('Error al obtener Terceras: ' . $e->getMessage());
            throw new \Exception('Error al obtener los datos de Terceras.');
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
                    ->where('Calidad', 'Tercera')
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
                $resultados = DB::connection('sqlsrv')
                    ->table('SegundasTerceras_View')
                    ->where('Calidad', 'Tercera')
                    ->pluck('OPRMODULEID_AT');

                Log::info('Resultados de la consulta módulos: ' . json_encode($resultados));

                $modulos = $resultados->unique()->values()->toArray();

                Log::info('Módulos únicos filtrados: ' . json_encode($modulos));

                return $modulos;
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
     * Obtiene clientes únicos basados en la columna CUSTOMERNAME de la vista correspondiente.
     *
     * @return array
     */
    function ObtenerClientes(): array
    {
        try {
            return Cache::remember('ObtenerClientes', 1800, function () {
                $Clientes = DB::connection('sqlsrv')
                    ->table('SegundasTerceras_View')
                    ->where('Calidad', 'Tercera')
                    ->pluck('CUSTOMERNAME');

                Log::info('Resultados de la consulta Clientes: ' . json_encode($Clientes));

                $ObtenerClientes = $Clientes->unique()->values()->toArray();

                Log::info('Clientes únicos filtrados: ' . json_encode($ObtenerClientes));

                return $ObtenerClientes;
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

