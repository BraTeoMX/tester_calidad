<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
if (!function_exists('obtenerSegundasTerceras')) {
    /**
     * Obtiene datos de la vista SegundasTerceras_View.
     *
     * @return Collection
     */
    function obtenerSegundasTerceras(): Collection
    {
        try {
            return Cache::remember('segundas_terceras', 60, function() {
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
