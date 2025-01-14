<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Carbon\CarbonPeriod; // Asegúrate de importar la clase Carbon
use Illuminate\Support\Facades\DB; // Importa la clase DB
use Illuminate\Http\Request;

class EtiquetasV2Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Función para mostrar la vista principal
    public function etiquetas_v2()
    {
        return view('etiquetas.etiquetas_v2', ['title' => '']);
    }

    // Procesar el formulario y buscar estilos
    public function procesarFormularioEtiqueta(Request $request)
    {
        $tipoBusqueda = $request->input('tipoEtiqueta');
        $orden = $request->input('valorEtiqueta');

        //Log::info("Datos ingresados: $orden, $tipoBusqueda");

        $conexion = null;
        $campoBusqueda = null;

        // Definir la conexión y el campo de búsqueda según el tipo de búsqueda
        if ($tipoBusqueda === 'OC') {
            $campoBusqueda = 'ordenCompra';
            $conexion = DB::connection('sqlsrv_ax')->table('EtiquetasOC_View');
        } elseif ($tipoBusqueda === 'OP') {
            $campoBusqueda = 'OP';
            $conexion = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
        } elseif ($tipoBusqueda === 'PO') {
            $campoBusqueda = 'CPO';
            $conexion = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
        } elseif ($tipoBusqueda === 'OV') {
            $campoBusqueda = 'SALESID';
            $conexion = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
        }

        // Ejecutar la consulta para buscar estilos
        $estilos = $conexion
            ->where($campoBusqueda, $orden)
            ->select('Estilos')
            ->distinct()
            ->get();

        // Si no se encontraron resultados y el tipo de búsqueda es OC, realizar una búsqueda secundaria
        if ($tipoBusqueda === 'OC' && $estilos->isEmpty()) {
            //Log::info('No se encontraron resultados en EtiquetasOC_View, buscando en EtiquetasOC2_View...');
            $campoBusqueda2 = 'OrdenCompra';
            $conexion2 = DB::connection('sqlsrv_ax')->table('EtiquetasOC2_View');

            $estilos = $conexion2
                ->where($campoBusqueda2, $orden)
                ->select('Estilos')
                ->distinct()
                ->get();
        }

        // Redirigir a la misma vista con los estilos encontrados
        return view('etiquetas.etiquetas_v2', ['title' => '', 'estilos' => $estilos]);
    }
}