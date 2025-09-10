<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CategoriaAuditor;
use App\Models\CategoriaTecnico;
use App\Models\CategoriaColor;
use App\Models\CategoriaEstilo;
use App\Models\CategoriaNoRecibo;
use App\Models\CategoriaTallaCantidad;
use App\Models\CategoriaTamañoMuestra;
use App\Models\CategoriaDefecto;
use App\Models\CategoriaTipoDefecto;
use App\Models\CategoriaMaterialRelajado;
use App\Models\CategoriaDefectoCorte;
use App\Models\EncabezadoAuditoriaCorte;
use App\Models\AuditoriaMarcada;
use App\Models\AuditoriaTendido;
use App\Models\Lectra;
use App\Models\AuditoriaBulto;
use App\Models\AuditoriaFinal;
use App\Models\CategoriaAccionCorrectiva;
//aqui incluire los nuevos modelos de la actualizacion v2
use App\Models\EncabezadoAuditoriaCorteV2;
use App\Models\AuditoriaCorteMarcada;
use App\Models\AuditoriaCorteTendido;
use App\Models\AuditoriaCorteLectra;
use App\Models\AuditoriaCorteBulto;
use App\Models\AuditoriaCorteFinal;
use Illuminate\Support\Facades\DB; // Importa la clase DB

use App\Exports\DatosExport;
use App\Models\DatoAX;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon; // Asegúrate de importar la clase Carbon
use Illuminate\Support\Facades\Log;

class AuditoriaCorteController extends Controller
{

    // Método privado para cargar las categorías
    private function cargarCategorias()
    {
        return [
            'CategoriaColor' => CategoriaColor::where('estado', 1)->get(),
            'CategoriaEstilo' => CategoriaEstilo::where('estado', 1)->get(),
            'CategoriaNoRecibo' => CategoriaNoRecibo::where('estado', 1)->get(),
            'CategoriaTallaCantidad' => CategoriaTallaCantidad::where('estado', 1)->get(),
            'CategoriaTamañoMuestra' => CategoriaTamañoMuestra::where('estado', 1)->get(),
            'CategoriaMaterialRelajado' => CategoriaMaterialRelajado::where('estado', 1)->get(),
            'CategoriaAuditor' => CategoriaAuditor::where('estado', 1)->get(),
            'CategoriaTecnico' => CategoriaTecnico::where('estado', 1)->get(),
            'CategoriaDefectoCorte' => CategoriaDefectoCorte::where('estado', 1)->get(),
            'CategoriaDefectoCorteTendido' => CategoriaDefectoCorte::where('estado', 1)->where('area', "tendido")->get(),
            'CategoriaDefectoCorteLectra' => CategoriaDefectoCorte::where('estado', 1)->where('area', "corte lectra")->get(),
            'CategoriaDefectoCorteSellado' => CategoriaDefectoCorte::where('estado', 1)->where('area', "sellado")->get(),
            'EncabezadoAuditoriaCorteFiltro' => EncabezadoAuditoriaCorteV2::all(),
            'auditoriasMarcadas' => AuditoriaMarcada::all(),
            'CategoriaAccionCorrectiva' => CategoriaAccionCorrectiva::where('estado', 1)->where('area', '0')->get(),
        ];
    }

    // Método privado para cargar las categorías
    private function cargarCategoriasSinAX()
    {
        return [
            'CategoriaColor' => CategoriaColor::where('estado', 1)->get(),
            'CategoriaEstilo' => CategoriaEstilo::where('estado', 1)->get(),
            'CategoriaNoRecibo' => CategoriaNoRecibo::where('estado', 1)->get(),
            'CategoriaTallaCantidad' => CategoriaTallaCantidad::where('estado', 1)->get(),
            'CategoriaTamañoMuestra' => CategoriaTamañoMuestra::where('estado', 1)->get(),
            'CategoriaMaterialRelajado' => CategoriaMaterialRelajado::where('estado', 1)->get(),
            'CategoriaAuditor' => CategoriaAuditor::where('estado', 1)->get(),
            'CategoriaTecnico' => CategoriaTecnico::where('estado', 1)->get(),
            'CategoriaDefectoCorte' => CategoriaDefectoCorte::where('estado', 1)->get(),
            'CategoriaDefectoCorteTendido' => CategoriaDefectoCorte::where('estado', 1)->where('area', "tendido")->get(),
            'CategoriaDefectoCorteLectra' => CategoriaDefectoCorte::where('estado', 1)->where('area', "corte lectra")->get(),
            'CategoriaDefectoCorteSellado' => CategoriaDefectoCorte::where('estado', 1)->where('area', "sellado")->get(),
            'EncabezadoAuditoriaCorteFiltro' => EncabezadoAuditoriaCorte::all(),
            'auditoriasMarcadas' => AuditoriaMarcada::all(),
            'CategoriaAccionCorrectiva' => CategoriaAccionCorrectiva::where('estado', 1)->where('area', '0')->get(),
        ];
    }

    public function inicioAuditoriaCorte(Request $request)
    {
        $pageSlug = '';
        $categorias = $this->cargarCategorias();

        //dd($filteredEncabezados);

        $mesesEnEspanol = [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre'
        ];

        $query = DatoAX::select('estilo', 'op')
            ->distinct()
            ->whereNotNull('op')
            ->whereNotNull('sizename')
            ->where('sizename', '<>', '')
            ->whereNotIn('id', function ($query) {
                $query->select('id')
                    ->from('datos_auditorias')
                    ->whereIn('op', function ($subquery) {
                        $subquery->select('orden_id')
                            ->from('auditoria_corte_encabezado');
                    });
            })
            ->whereNull('estatus')
            ->where('period', '>', '202312');

        if ($request->has('search')) {
            $query->where('op', 'LIKE', '%' . $request->search . '%');
        }

        $DatoAXNoIniciado = $query->get();

        return view('auditoriaCorte.inicioAuditoriaCorte', array_merge($categorias, [
            'mesesEnEspanol' => $mesesEnEspanol,
            'pageSlug' => $pageSlug,
            'DatoAXNoIniciado' => $DatoAXNoIniciado
        ]));
    }

    public function searchEnProceso(Request $request)
    {
        $search = $request->get('search');

        // Iniciamos la consulta
        $query = EncabezadoAuditoriaCorteV2::query();

        if (!$search || strlen($search) < 4) {
            // Si no hay búsqueda o es menor a 4 caracteres, se obtienen solo los registros del día
            $today = Carbon::today()->toDateString();
            $query->whereDate('created_at', $today);
        } else {
            // Con 4 o más caracteres, se realiza búsqueda global en el campo "orden_id"
            $query->where('orden_id', 'like', '%' . $search . '%');
        }

        $encabezados = $query->get();

        // Agrupamos por 'orden_id' y filtramos grupos donde TODOS los registros tengan estatus 'fin'
        $grouped = $encabezados->groupBy('orden_id')->filter(function ($group) {
            return !$group->every(function ($item) {
                return $item->estatus === 'fin';
            });
        });

        // Definición de mapeo para los estatus
        $statusMapping = [
            'proceso'                 => 'No iniciado',
            'estatusAuditoriaMarcada' => 'Marcada',
            'estatusAuditoriaTendido' => 'Tendido',
            'estatusLectra'           => 'Lectra',
            'estatusAuditoriaBulto'   => 'Bulto',
            'estatusAuditoriaFinal'   => 'Auditoria Final',
        ];

        $html = '';
        if ($grouped->isNotEmpty()) {
            foreach ($grouped as $orden_id => $items) {
                $encabezadoCorte = $items->first();
                $html .= '<div class="card proceso-card" data-proceso="' . $orden_id . '">';
                $html .= '  <div class="card-header" id="heading' . $orden_id . '">';
                $html .= '      <h2 class="mb-0">';
                $html .= '          <button class="btn estado-proceso btn-block" type="button" data-toggle="collapse" data-target="#collapse' . $orden_id . '" aria-expanded="true" aria-controls="collapse' . $orden_id . '">';
                $html .=                 $orden_id;
                $html .= '          </button>';
                $html .= '      </h2>';
                $html .= '  </div>';
                $html .= '  <div id="collapse' . $orden_id . '" class="collapse" aria-labelledby="heading' . $orden_id . '" data-parent="#accordionExample">';
                $html .= '      <div class="card-body">';
                $html .= '          <div>';
                $html .= '              <form method="POST" action="' . route('auditoriaCorte.agregarEventoCorteV2') . '">';
                $html .= '                  ' . csrf_field();
                $html .= '                  <input type="hidden" name="orden_id" value="' . $orden_id . '">';
                $html .= '                  <input type="hidden" name="estilo_id" value="' . $encabezadoCorte->estilo_id . '">';
                $html .= '                  <input type="hidden" name="planta_id" value="' . $encabezadoCorte->planta_id . '">';
                $html .= '                  <input type="hidden" name="temporada_id" value="' . $encabezadoCorte->temporada_id . '">';
                $html .= '                  <input type="hidden" name="cliente_id" value="' . $encabezadoCorte->cliente_id . '">';
                $html .= '                  <input type="hidden" name="color_id" value="' . $encabezadoCorte->color_id . '">';
                $html .= '                  <input type="hidden" name="estatus_evaluacion_corte" value="' . $encabezadoCorte->estatus_evaluacion_corte . '">';
                $html .= '                  <input type="hidden" name="material" value="' . $encabezadoCorte->material . '">';
                $html .= '                  <input type="hidden" name="pieza" value="' . $encabezadoCorte->pieza . '">';
                $html .= '                  <input type="hidden" name="trazo" value="' . $encabezadoCorte->trazo . '">';
                $html .= '                  <input type="hidden" name="lienzo" value="' . $encabezadoCorte->lienzo . '">';
                $html .= '                  <button type="submit" class="btn btn-info">Agregar 1 evento</button>';
                $html .= '              </form>';
                $html .= '          </div>';
                $html .= '          <table class="table">';
                $html .= '              <thead>';
                $html .= '                  <tr>';
                $html .= '                      <th>Acceso</th>';
                $html .= '                      <th>Evento</th>';
                $html .= '                      <th>Color</th>';
                $html .= '                      <th>Estatus</th>';
                $html .= '                  </tr>';
                $html .= '              </thead>';
                $html .= '              <tbody>';
                // Se muestran solo los eventos con estatus distinto de "fin"
                $itemsFiltrados = $items->filter(function ($item) {
                    return $item->estatus !== 'fin';
                });
                foreach ($itemsFiltrados as $evento) {
                    $displayStatus = isset($statusMapping[$evento->estatus]) ? $statusMapping[$evento->estatus] : $evento->estatus;
                    $html .= '<tr>';
                    $html .= '  <td><a href="' . route('auditoriaCorte.auditoriaCorteV2', ['id' => $evento->id, 'orden' => $evento->orden_id]) . '" class="btn btn-primary">Acceder</a></td>';
                    $html .= '  <td>' . $evento->evento . '</td>';
                    $html .= '  <td>' . $evento->color_id . '</td>';
                    $html .= '  <td>' . $displayStatus . '</td>';
                    $html .= '</tr>';
                }
                $html .= '              </tbody>';
                $html .= '          </table>';
                $html .= '      </div>';
                $html .= '  </div>';
                $html .= '</div>';
            }
        } else {
            $html = '<p>No se encontraron registros.</p>';
        }

        return response()->json(['html' => $html]);
    }


    public function searchFinal(Request $request)
    {
        $search = $request->get('search');

        // Iniciar consulta para registros con estatus 'fin'
        $query = EncabezadoAuditoriaCorteV2::query()->where('estatus', 'fin');

        if (!$search || strlen($search) < 4) {
            // Sin búsqueda o con menos de 4 caracteres: filtrar por el día de hoy
            $today = Carbon::today()->toDateString();
            $query->whereDate('created_at', $today);
        } else {
            // Con 4 o más caracteres: búsqueda global por 'orden_id'
            $query->where('orden_id', 'like', '%' . $search . '%');
        }

        $encabezadosFinal = $query->get();

        // Agrupar por 'orden_id' para evitar duplicados
        $grouped = $encabezadosFinal->groupBy('orden_id');

        // Generar el HTML del nested accordion para FINAL
        $html = '';
        if ($grouped->isNotEmpty()) {
            foreach ($grouped as $orden_id => $items) {
                // Tomamos el primer registro para algunos datos
                $encabezadoCorte = $items->first();

                $html .= '<div class="card proceso-card-final" data-proceso="' . $orden_id . '">';
                $html .= '  <div class="card-header" id="headingFinal' . $orden_id . '">';
                $html .= '      <h2 class="mb-0">';
                $html .= '          <button class="btn btn-success btn-block" type="button" data-toggle="collapse" data-target="#collapseFinal' . $orden_id . '" aria-expanded="true" aria-controls="collapseFinal' . $orden_id . '">';
                $html .=                 $orden_id;
                $html .= '          </button>';
                $html .= '      </h2>';
                $html .= '  </div>';
                $html .= '  <div id="collapseFinal' . $orden_id . '" class="collapse" aria-labelledby="headingFinal' . $orden_id . '" data-parent="#accordionExampleFinalSub">';
                $html .= '      <div class="card-body">';
                $html .= '          <table class="table">';
                $html .= '              <thead>';
                $html .= '                  <tr>';
                $html .= '                      <th>Acceso</th>';
                $html .= '                      <th>Evento</th>';
                $html .= '                      <th>Estilo</th>';
                $html .= '                  </tr>';
                $html .= '              </thead>';
                $html .= '              <tbody>';
                // Se itera sobre todos los eventos para este grupo
                foreach ($items as $evento) {
                    $html .= '<tr>';
                    $html .= '  <td><a href="' . route('auditoriaCorte.auditoriaCorteV2', ['id' => $evento->id, 'orden' => $evento->orden_id]) . '" class="btn btn-primary">Acceder</a></td>';
                    $html .= '  <td>' . $evento->evento . '</td>';
                    $html .= '  <td>' . $evento->estilo_id . '</td>';
                    $html .= '</tr>';
                }
                $html .= '              </tbody>';
                $html .= '          </table>';
                $html .= '      </div>';
                $html .= '  </div>';
                $html .= '</div>';
            }
        } else {
            $html = '<p>No se encontraron registros.</p>';
        }

        return response()->json(['html' => $html]);
    }


    public function auditoriaCorte($id, $orden)
    {
        $pageSlug = '';
        $categorias = $this->cargarCategorias();
        $auditorDato = Auth::user()->name;
        //dd($userName);
        // Obtener el dato con el id seleccionado y el valor de la columna "orden"
        $datoAX = DatoAX::where('op', $orden)->first();
        //dd($datoAX);
        $mesesEnEspanol = [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre'
        ];
        // Obtener el registro correspondiente en la tabla AuditoriaMarcada si existe
        $encabezadoAuditoriaCorte = EncabezadoAuditoriaCorte::where('id', $id)->first();
        $auditoriaMarcada = AuditoriaMarcada::where('id', $id)->first();
        $auditoriaTendido = AuditoriaTendido::where('id', $id)->first();
        $Lectra = Lectra::where('id', $id)->first();
        $auditoriaBulto = AuditoriaBulto::where('id', $id)->first();
        $auditoriaFinal = AuditoriaFinal::where('id', $id)->first();
        $auditoriaMarcadaTalla = DB::connection('sqlsrv')->table('OrdenesCorte_View')->where('op', $orden)
            ->whereNotNull('sizename') // Descartar valores NULL
            ->where('sizename', '<>', '') // Descartar valores vacíos
            ->select('sizename')
            ->distinct()
            ->pluck('sizename');

        // apartado para validar los checbox

        $mostrarFinalizarMarcada = $auditoriaMarcada ? session('estatus_checked_AuditoriaMarcada') : false;

        // Verifica si los campos específicos son NULL
        if (
            $auditoriaMarcada && is_null($auditoriaMarcada->yarda_orden_estatus) &&
            is_null($auditoriaMarcada->yarda_marcada_estatus) &&
            is_null($auditoriaMarcada->yarda_tendido_estatus)
        ) {
            $mostrarFinalizarMarcada = false;
        }

        //dd($auditoriaMarcada, $mostrarFinalizarMarcada);
        $mostrarFinalizarTendido = $auditoriaTendido ? session('estatus_checked_AuditoriaTendido') : false;
        $mostrarFinalizarLectra = $Lectra ? session('estatus_checked_Lectra') : false;
        $mostrarFinalizarBulto = $auditoriaBulto ? session('estatus_checked_AuditoriaBulto') : false;
        $mostrarFinalizarFinal = $auditoriaFinal ? session('estatus_checked_AuditoriaFinal') : false;
        return view('auditoriaCorte.auditoriaCorte', array_merge($categorias, [
            'mesesEnEspanol' => $mesesEnEspanol,
            'pageSlug' => $pageSlug,
            'datoAX' => $datoAX,
            'auditoriaMarcada' => $auditoriaMarcada,
            'auditoriaTendido' => $auditoriaTendido,
            'Lectra' => $Lectra,
            'auditoriaBulto' => $auditoriaBulto,
            'auditoriaFinal' => $auditoriaFinal,
            'mostrarFinalizarMarcada' => $mostrarFinalizarMarcada,
            'mostrarFinalizarTendido' => $mostrarFinalizarTendido,
            'mostrarFinalizarLectra' => $mostrarFinalizarLectra,
            'mostrarFinalizarBulto' => $mostrarFinalizarBulto,
            'mostrarFinalizarFinal' => $mostrarFinalizarFinal,
            'encabezadoAuditoriaCorte' => $encabezadoAuditoriaCorte,
            'auditoriaMarcadaTalla' => $auditoriaMarcadaTalla,
            'auditorDato' => $auditorDato
        ]));
    }

    public function altaAuditoriaCorte($orden, $color)
    {
        $pageSlug = '';
        $categorias = $this->cargarCategorias();
        $auditorDato = Auth::user()->name;
        //dd($userName);
        // Obtener el dato con el id seleccionado y el valor de la columna "orden"
        // Prepara la consulta base filtrando por 'op'
        $query = DB::connection('sqlsrv')->table('OrdenesCorte_View')->where('op', $orden);

        // Si $color tiene algún valor, se añade la condición correspondiente
        if (!empty($color)) {
            $query->where('inventcolorid', $color);
        }

        $datoAX = $query->first();
        //dd($datoAX);
        $mesesEnEspanol = [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre'
        ];

        return view('auditoriaCorte.altaAuditoriaCorte', array_merge($categorias, [
            'mesesEnEspanol' => $mesesEnEspanol,
            'pageSlug' => $pageSlug,
            'datoAX' => $datoAX,
            'auditorDato' => $auditorDato
        ]));
    }




    public function formEncabezadoAuditoriaCorte(Request $request)
    {
        $pageSlug = '';
        // Validar los datos del formulario si es necesario
        // Validar los datos del formulario si es necesario
        // Obtener el ID seleccionado desde el formulario
        //$idSeleccionado = $request->input('id');
        $idEncabezadoAuditoriaCorte = $request->input('idEncabezadoAuditoriaCorte');
        $orden = $request->input('orden');
        $estilo = $request->input('estilo');
        $planta = $request->input('planta');
        $temporada = $request->input('temporada');
        $cliente = $request->input('cliente');
        $color = $request->input('color');
        $eventoInicial = $request->input('evento');

        //dd($request->all());
        $encabezadoAuditoriaCorte = EncabezadoAuditoriaCorte::where('id', $idEncabezadoAuditoriaCorte)->first();
        //dd($encabezadoAuditoriaCorte);
        // Verificar si ya existen datos para el dato_ax_id especificado
        if ($encabezadoAuditoriaCorte) {
            //dd($request->all());
            $encabezadoAuditoriaCorte->pieza = $request->input('pieza');
            if ($request->input('color_id')) {
                $encabezadoAuditoriaCorte->color_id = $request->input('color_id');
            }
            $encabezadoAuditoriaCorte->lienzo = $request->input('lienzo');
            $encabezadoAuditoriaCorte->qtysched_id = $request->input('qtysched_id');
            $encabezadoAuditoriaCorte->estatus = 'estatusAuditoriaMarcada';
            $encabezadoAuditoriaCorte->save();

            return back()->with('sobre-escribir', 'Ya existen datos para este registro.');
        }

        //$datoAX = DatoAX::findOrFail($idSeleccionado);
        // Actualizar el valor de la columna deseada
        //$datoAX->estatus = 'estatusAuditoriaMarcada';
        //$datoAX->evento = $request->input('evento');
        //$datoAX->save();
        //dd($request->all());
        // Generar múltiples registros en auditoria_marcadas según el valor de evento
        for ($i = 1; $i <= $request->input('total_evento'); $i++) {

            // Realizar la actualización en la base de datos
            $auditoria = new EncabezadoAuditoriaCorte();
            $auditoria->orden_id = $orden;
            $auditoria->estilo_id = $estilo;
            $auditoria->planta_id = $planta;
            $auditoria->temporada_id = $temporada;
            $auditoria->cliente_id = $cliente;
            $auditoria->color_id = $request->input('color_id') ?? $color;
            $auditoria->material = $request->input('material');
            $auditoria->pieza = $request->input('pieza');
            $auditoria->qtysched_id = $request->input('qtysched_id');
            $auditoria->trazo = $request->input('trazo');
            $auditoria->lienzo = $request->input('lienzo');
            $auditoria->total_evento = $request->input('total_evento');
            $auditoria->evento = $i;

            if ($i == $eventoInicial) {
                $auditoria->estatus = "estatusAuditoriaMarcada"; // Cambiar estatus solo para el primer registro
            } else {
                $auditoria->estatus = "proceso"; // Mantener el valor "proceso" para los demás registros
            }
            $auditoria->save();


            $auditoriaMarcada = new AuditoriaMarcada();
            //$auditoriaMarcada->dato_ax_id = $idSeleccionado;
            $auditoriaMarcada->orden_id = $orden;
            if ($i == $eventoInicial) {
                $auditoriaMarcada->estatus = "estatusAuditoriaMarcada"; // Cambiar estatus solo para el primer registro
            } else {
                $auditoriaMarcada->estatus = "proceso"; // Mantener el valor "proceso" para los demás registros
            }
            $auditoriaMarcada->evento = $i;
            // Otros campos que necesites para cada registro...

            $auditoriaMarcada->save();
            if ($i == $eventoInicial) {
                $idEvento1 = $auditoriaMarcada->id;
            }

            $auditoriaTendido = new AuditoriaTendido();
            $auditoriaTendido->orden_id = $orden;
            $auditoriaTendido->estatus = "proceso";
            $auditoriaTendido->evento = $i;
            $auditoriaTendido->save();

            $lectra = new Lectra();
            $lectra->orden_id = $orden;
            $lectra->cliente_id = $cliente;
            $lectra->evento = $i;
            $lectra->save();

            $auditoriaBulto = new AuditoriaBulto();
            $auditoriaBulto->orden_id = $orden;
            $auditoriaBulto->cliente_id = $cliente;
            $auditoriaBulto->estatus = "proceso";
            $auditoriaBulto->evento = $i;
            $auditoriaBulto->save();

            $auditoriaFinal = new AuditoriaFinal();
            $auditoriaFinal->orden_id = $orden;
            $auditoriaFinal->cliente_id = $cliente;
            $auditoriaFinal->estatus = "proceso";
            $auditoriaFinal->evento = $i;
            $auditoriaFinal->save();
        }
        //dd($idEvento1);

        return redirect()->route('auditoriaCorte.auditoriaCorte', ['id' => $idEvento1, 'orden' => $orden])->with('success', 'Datos guardados correctamente.')->with('pageSlug', $pageSlug);
    }

    public function agregarEventoCorte(Request $request)
    {
        $pageSlug = '';
        $orden_id = $request->input('orden_id');
        $cliente_id = $request->input('cliente_id');

        // Obtener el máximo evento actual para la orden_id
        $maxEvento = EncabezadoAuditoriaCorte::where('orden_id', $orden_id)->max('evento');
        $nuevoEvento = $maxEvento + 1;

        // Actualizar los registros existentes
        EncabezadoAuditoriaCorte::where('orden_id', $orden_id)->update(['total_evento' => $nuevoEvento]);

        // Agregar un nuevo registro
        EncabezadoAuditoriaCorte::create([
            'orden_id' => $orden_id,
            'evento' => $nuevoEvento,
            'total_evento' => $nuevoEvento,
            'estatus' => "proceso",
            'estilo_id' => $request->input('estilo_id'),
            'planta_id' => $request->input('planta_id'),
            'temporada_id' => $request->input('temporada_id'),
            'cliente_id' => $request->input('cliente_id'),
            'color_id' => $request->input('color_id'),
            'estatus_evaluacion_corte' => $request->input('estatus_evaluacion_corte'),
            'material' => $request->input('material'),
            'pieza' => $request->input('pieza'),
            'trazo' => $request->input('trazo'),
            'lienzo' => $request->input('lienzo'),
        ]);

        $auditoriaMarcada = new AuditoriaMarcada();
        $auditoriaMarcada->orden_id = $orden_id;
        $auditoriaMarcada->estatus = "proceso"; // Mantener el valor "proceso" para los demás registros
        $auditoriaMarcada->evento = $nuevoEvento;
        // Otros campos que necesites para cada registro...

        $auditoriaMarcada->save();


        $auditoriaTendido = new AuditoriaTendido();
        $auditoriaTendido->orden_id = $orden_id;
        $auditoriaTendido->estatus = "proceso";
        $auditoriaTendido->evento = $nuevoEvento;
        $auditoriaTendido->save();

        $lectra = new Lectra();
        $lectra->orden_id = $orden_id;
        $lectra->cliente_id = $cliente_id;
        $lectra->evento = $nuevoEvento;
        $lectra->save();

        $auditoriaBulto = new AuditoriaBulto();
        $auditoriaBulto->orden_id = $orden_id;
        $auditoriaBulto->cliente_id = $cliente_id;
        $auditoriaBulto->estatus = "proceso";
        $auditoriaBulto->evento = $nuevoEvento;
        $auditoriaBulto->save();

        $auditoriaFinal = new AuditoriaFinal();
        $auditoriaFinal->orden_id = $orden_id;
        $auditoriaFinal->cliente_id = $cliente_id;
        $auditoriaFinal->estatus = "proceso";
        $auditoriaFinal->evento = $nuevoEvento;
        $auditoriaFinal->save();


        // Redireccionar a la página anterior
        return redirect()->route('auditoriaCorte.inicioAuditoriaCorte',)->with('success', '  Evento agregado correctamente.')->with('pageSlug', $pageSlug);
    }

    public function formRechazoCorte(Request $request)
    {
        $pageSlug = '';
        // Obtener el ID seleccionado desde el formulario
        $idSeleccionado = $request->input('id');


        $datoAX = DatoAX::findOrFail($idSeleccionado);
        // Actualizar el valor de la columna deseada
        $datoAX->estatus = 'rechazado';
        $datoAX->save();


        return redirect()->route('auditoriaCorte.inicioAuditoriaCorte',)->with('error', 'Rechazo guardado correctamente.')->with('pageSlug', $pageSlug);
    }

    public function formAprobarCorte(Request $request)
    {
        $pageSlug = '';

        // Obtener el ID seleccionado desde el formulario
        $idSeleccionado = $request->input('id');


        $datoAX = DatoAX::findOrFail($idSeleccionado);
        // Actualizar el valor de la columna deseada
        $datoAX->estatus = null; // Establecer el valor a NULL
        $datoAX->save();


        return redirect()->route('auditoriaCorte.inicioAuditoriaCorte',)->with('success', 'Aprobado guardado correctamente.')->with('pageSlug', $pageSlug);
    }



    public function formAuditoriaMarcada(Request $request)
    {
        $pageSlug = '';
        // Validar los datos del formulario si es necesario
        // Obtener el ID seleccionado desde el formulario
        $idSeleccionado = $request->input('id');
        $idAuditoriaMarcada = $request->input('idAuditoriaMarcada');
        //dd($idSeleccionado, $idAuditoriaMarcada);
        $orden = $request->input('orden');
        $accion = $request->input('accion'); // Obtener el valor del campo 'accion'
        // Verificar la acción y actualizar el campo 'estatus' solo si se hizo clic en el botón "Finalizar"
        //dd($accion);
        if ($accion === 'finalizar') {
            // Buscar la fila en la base de datos utilizando el modelo AuditoriaMarcada
            $auditoria = DatoAX::findOrFail($idSeleccionado);

            // Actualizar el valor de la columna deseada
            $auditoria->estatus = 'estatusAuditoriaTendido';
            $auditoria->save();
            $auditoriaMarcadaEstatus = AuditoriaMarcada::where('id', $idAuditoriaMarcada)->first();
            $auditoriaMarcadaEstatus->estatus = 'estatusAuditoriaTendido';
            // Asegúrate de llamar a save() en la variable actualizada
            $auditoriaMarcadaEstatus->save();
            $encabezadoAuditoriaCorteEstatus = EncabezadoAuditoriaCorte::where('id', $idAuditoriaMarcada)->first();
            $encabezadoAuditoriaCorteEstatus->estatus = 'estatusAuditoriaTendido';
            // Asegúrate de llamar a save() en la variable actualizada
            $encabezadoAuditoriaCorteEstatus->save();
            return back()->with('cambio-estatus', 'Se Cambio a estatus: AUDITORIA DE TENDIDO.')->with('pageSlug', $pageSlug);
        }

        $allChecked = trim($request->input('yarda_orden_estatus')) === "1";

        $request->session()->put('estatus_checked_AuditoriaMarcada', $allChecked);
        // Verificar si ya existe un registro con el mismo valor de orden_id
        $existeOrden = AuditoriaMarcada::where('id', $idAuditoriaMarcada)->first();

        // Si ya existe un registro con el mismo valor de orden_id, puedes mostrar un mensaje de error o tomar alguna otra acción
        if ($existeOrden) {
            $existeOrden->yarda_orden = $request->input('yarda_orden');
            $existeOrden->yarda_orden_estatus = $request->input('yarda_orden_estatus');
            $existeOrden->talla1 = $request->input('talla1');
            $existeOrden->talla2 = $request->input('talla2');
            $existeOrden->talla3 = $request->input('talla3');
            $existeOrden->talla4 = $request->input('talla4');
            $existeOrden->talla5 = $request->input('talla5');
            $existeOrden->talla6 = $request->input('talla6');
            $existeOrden->talla_parcial1 = $request->input('talla_parcial1');
            $existeOrden->talla_parcial2 = $request->input('talla_parcial2');
            $existeOrden->talla_parcial3 = $request->input('talla_parcial3');
            $existeOrden->talla_parcial4 = $request->input('talla_parcial4');
            $existeOrden->talla_parcial5 = $request->input('talla_parcial5');
            $existeOrden->talla_parcial6 = $request->input('talla_parcial6');
            $existeOrden->bulto1 = $request->input('bulto1');
            $existeOrden->bulto2 = $request->input('bulto2');
            $existeOrden->bulto3 = $request->input('bulto3');
            $existeOrden->bulto4 = $request->input('bulto4');
            $existeOrden->bulto5 = $request->input('bulto5');
            $existeOrden->bulto6 = $request->input('bulto6');
            $existeOrden->bulto_parcial1 = $request->input('bulto_parcial1');
            $existeOrden->bulto_parcial2 = $request->input('bulto_parcial2');
            $existeOrden->bulto_parcial3 = $request->input('bulto_parcial3');
            $existeOrden->bulto_parcial4 = $request->input('bulto_parcial4');
            $existeOrden->bulto_parcial5 = $request->input('bulto_parcial5');
            $existeOrden->bulto_parcial6 = $request->input('bulto_parcial6');
            $existeOrden->total_pieza1 = $request->input('total_pieza1');
            $existeOrden->total_pieza2 = $request->input('total_pieza2');
            $existeOrden->total_pieza3 = $request->input('total_pieza3');
            $existeOrden->total_pieza4 = $request->input('total_pieza4');
            $existeOrden->total_pieza5 = $request->input('total_pieza5');
            $existeOrden->total_pieza6 = $request->input('total_pieza6');
            $existeOrden->total_pieza_parcial1 = $request->input('total_pieza_parcial1');
            $existeOrden->total_pieza_parcial2 = $request->input('total_pieza_parcial2');
            $existeOrden->total_pieza_parcial3 = $request->input('total_pieza_parcial3');
            $existeOrden->total_pieza_parcial4 = $request->input('total_pieza_parcial4');
            $existeOrden->total_pieza_parcial5 = $request->input('total_pieza_parcial5');
            $existeOrden->total_pieza_parcial6 = $request->input('total_pieza_parcial6');
            $existeOrden->largo_trazo =  $request->input('largo_trazo');
            $existeOrden->ancho_trazo = $request->input('ancho_trazo');
            $existeOrden->save();

            return back()->with('sobre-escribir', 'Actualilzacion realizada con exito');
        }

        return back()->with('success', 'Datos guardados correctamente.')->with('pageSlug', $pageSlug);
    }

    public function formAuditoriaTendido(Request $request)
    {
        $pageSlug = '';
        // Validar los datos del formulario si es necesario
        // Obtener el ID seleccionado desde el formulario
        $idSeleccionado = $request->input('id');
        $idAuditoriaTendido = $request->input('idAuditoriaTendido');
        $orden = $request->input('orden');
        $accion = $request->input('accion'); // Obtener el valor del campo 'accion'
        //dd($accion);

        if ($accion === 'finalizar') {
            // Buscar la fila en la base de datos utilizando el modelo AuditoriaMarcada
            $auditoria = DatoAX::findOrFail($idSeleccionado);

            // Actualizar el valor de la columna deseada
            $auditoria->estatus = 'estatusLectra';
            $auditoria->save();

            $auditoriaTendido = AuditoriaTendido::where('id', $idAuditoriaTendido)->first();
            $auditoriaTendido->estatus = 'estatusLectra';
            // Asegúrate de llamar a save() en la variable actualizada
            $auditoriaTendido->save();
            $encabezadoAuditoriaCorteEstatus = EncabezadoAuditoriaCorte::where('id', $idAuditoriaTendido)->first();
            $encabezadoAuditoriaCorteEstatus->estatus = 'estatusLectra';
            // Asegúrate de llamar a save() en la variable actualizada
            $encabezadoAuditoriaCorteEstatus->save();
            return back()->with('cambio-estatus', 'Se Cambio a estatus: LECTRA.')->with('pageSlug', $pageSlug);
        }

        $allChecked = trim($request->input('informacion_trazo_estatus')) === "1" &&
            trim($request->input('material_relajado_estatus')) === "1" &&
            trim($request->input('empalme_estatus')) === "1" &&
            trim($request->input('cara_material_estatus')) === "1" &&
            trim($request->input('tono_estatus')) === "1";

        $request->session()->put('estatus_checked_AuditoriaTendido', $allChecked);
        // Verificar si ya existe un registro con el mismo valor de orden_id
        $existeOrden = AuditoriaTendido::where('id', $idAuditoriaTendido)->first();
        //dd($existeOrden);

        // Si ya existe un registro con el mismo valor de orden_id, puedes mostrar un mensaje de error o tomar alguna otra acción
        if ($existeOrden) {
            $existeOrden->nombre = implode(',', $request->input('nombre'));
            $existeOrden->mesa = $request->input('mesa');
            $existeOrden->auditor = $request->input('auditor');
            $existeOrden->codigo_material = $request->input('codigo_material');
            $existeOrden->codigo_material_estatus = $request->input('codigo_material_estatus');
            $existeOrden->codigo_color = $request->input('codigo_color');
            $existeOrden->codigo_color_estatus = $request->input('codigo_color_estatus');
            $existeOrden->informacion_trazo = $request->input('informacion_trazo');
            $existeOrden->informacion_trazo_estatus = $request->input('informacion_trazo_estatus');
            $existeOrden->cantidad_lienzo = $request->input('cantidad_lienzo');
            $existeOrden->cantidad_lienzo_estatus = $request->input('cantidad_lienzo_estatus');
            $existeOrden->longitud_tendido = $request->input('longitud_tendido');
            $existeOrden->longitud_tendido_estatus = $request->input('longitud_tendido_estatus');
            $existeOrden->ancho_tendido = $request->input('ancho_tendido');
            $existeOrden->ancho_tendido_estatus = $request->input('ancho_tendido_estatus');
            $existeOrden->material_relajado = $request->input('material_relajado');
            $existeOrden->material_relajado_estatus = $request->input('material_relajado_estatus');
            $existeOrden->empalme = $request->input('empalme');
            $existeOrden->empalme_estatus = $request->input('empalme_estatus');
            $existeOrden->cara_material = $request->input('cara_material');
            $existeOrden->cara_material_estatus = $request->input('cara_material_estatus');
            $existeOrden->tono = $request->input('tono');
            $existeOrden->tono_estatus = $request->input('tono_estatus');
            $existeOrden->alineacion_tendido = $request->input('alineacion_tendido');
            $existeOrden->alineacion_tendido_estatus = "1";
            $existeOrden->arruga_tendido = $request->input('arruga_tendido');
            $existeOrden->arruga_tendido_estatus = "1";
            $existeOrden->defecto_material = implode(',', $request->input('defecto_material'));
            $existeOrden->yarda_marcada = $request->input('yarda_marcada');
            $existeOrden->yarda_marcada_estatus = $request->input('yarda_marcada_estatus');
            $existeOrden->accion_correctiva = $request->input('accion_correctiva');
            $existeOrden->bio_tension = $request->input('bio_tension');
            $existeOrden->velocidad = $request->input('velocidad');
            $existeOrden->comentario_tendido = $request->input('comentario_tendido');
            //$existeOrden->libera_tendido = $request->input('libera_tendido');

            $existeOrden->save();
            //dd($existeOrden);
            return back()->with('sobre-escribir', 'Actualilzacion realizada con exito');
        }
        // dd($existeOrden->nombre2);

        return back()->with('success', 'Datos guardados correctamente.')->with('pageSlug', $pageSlug);
    }

    public function formLectra(Request $request)
    {
        $pageSlug = '';
        //dd($request->all());
        // Validar los datos del formulario si es necesario
        // Obtener el ID seleccionado desde el formulario
        $idSeleccionado = $request->input('id');
        $idLectra = $request->input('idLectra');
        $orden = $request->input('orden');
        $accion = $request->input('accion'); // Obtener el valor del campo 'accion'

        if ($accion === 'finalizar') {
            // Buscar la fila en la base de datos utilizando el modelo AuditoriaMarcada
            $auditoria = DatoAX::findOrFail($idSeleccionado);

            // Actualizar el valor de la columna deseada
            $auditoria->estatus = 'estatusAuditoriaBulto';
            $auditoria->save();

            $encabezadoAuditoriaCorteEstatus = EncabezadoAuditoriaCorte::where('id', $idLectra)->first();
            $encabezadoAuditoriaCorteEstatus->estatus = 'estatusAuditoriaBulto';
            // Asegúrate de llamar a save() en la variable actualizada
            $encabezadoAuditoriaCorteEstatus->save();
            return back()->with('cambio-estatus', 'Se Cambio a estatus: AUDITORIA EN BULTOS.')->with('pageSlug', $pageSlug);
        }

        $allChecked = trim($request->input('pieza_contrapatron_estatus')) === "1";

        $request->session()->put('estatus_checked_Lectra', $allChecked);
        // Verificar si ya existe un registro con el mismo valor de orden_id
        $existeOrden = Lectra::where('id', $idLectra)->first();
        //dd($request->input('x1'), $request->input('y1'));

        // Si ya existe un registro con el mismo valor de orden_id, puedes mostrar un mensaje de error o tomar alguna otra acción
        if ($existeOrden) {
            $existeOrden->nombre = implode(',', $request->input('nombre'));
            $existeOrden->mesa = $request->input('mesa');
            $existeOrden->auditor = $request->input('auditor');
            $existeOrden->simetria_pieza1 = $request->input('simetria_pieza1');
            $existeOrden->panel1_x1 = $request->input('panel1_x1');
            $existeOrden->panel1_x2 = $request->input('panel1_x2');
            $existeOrden->panel1_y1 = $request->input('panel1_y1');
            $existeOrden->panel1_y2 = $request->input('panel1_y2');
            $existeOrden->simetria_pieza2 = $request->input('simetria_pieza2');
            $existeOrden->panel2_x1 = $request->input('panel2_x1');
            $existeOrden->panel2_x2 = $request->input('panel2_x2');
            $existeOrden->panel2_y1 = $request->input('panel2_y1');
            $existeOrden->panel2_y2 = $request->input('panel2_y2');
            $existeOrden->simetria_pieza3 = $request->input('simetria_pieza3');
            $existeOrden->panel3_x1 = $request->input('panel3_x1');
            $existeOrden->panel3_x2 = $request->input('panel3_x2');
            $existeOrden->panel3_y1 = $request->input('panel3_y1');
            $existeOrden->panel3_y2 = $request->input('panel3_y2');
            $existeOrden->simetria_pieza4 = $request->input('simetria_pieza4');
            $existeOrden->panel4_x1 = $request->input('panel4_x1');
            $existeOrden->panel4_x2 = $request->input('panel4_x2');
            $existeOrden->panel4_y1 = $request->input('panel4_y1');
            $existeOrden->panel4_y2 = $request->input('panel4_y2');
            //$existeOrden->pieza_contrapatron = $request->input('pieza_contrapatron');
            $existeOrden->pieza_contrapatron_estatus = $request->input('pieza_contrapatron_estatus');
            $existeOrden->pieza_inspeccionada = $request->input('pieza_inspeccionada');
            $existeOrden->cantidad_defecto = $request->input('cantidad_defecto');
            $existeOrden->defecto = implode(',', $request->input('defecto'));
            $existeOrden->porcentaje = $request->input('porcentaje');
            $existeOrden->estado_validacion = $request->input('estado_validacion');
            $existeOrden->nivel_aql = $request->input('nivel_aql');


            $existeOrden->save();
            //dd($existeOrden);
            return back()->with('sobre-escribir', 'Actualilzacion realizada con exito');
        }

        return back()->with('success', 'Datos guardados correctamente.')->with('pageSlug', $pageSlug);
    }

    public function formAuditoriaBulto(Request $request)
    {
        $pageSlug = '';
        // Validar los datos del formulario si es necesario
        // Obtener el ID seleccionado desde el formulario
        $idSeleccionado = $request->input('id');
        $idBulto = $request->input('idBulto');
        $orden = $request->input('orden');
        $accion = $request->input('accion'); // Obtener el valor del campo 'accion'
        //dd($request->input());
        if ($accion === 'finalizar') {
            // Buscar la fila en la base de datos utilizando el modelo AuditoriaMarcada
            $auditoria = DatoAX::findOrFail($idSeleccionado);

            // Actualizar el valor de la columna deseada
            $auditoria->estatus = 'estatusAuditoriaFinal';
            $auditoria->save();


            $encabezadoAuditoriaCorteEstatus = EncabezadoAuditoriaCorte::where('id', $idBulto)->first();
            $encabezadoAuditoriaCorteEstatus->estatus = 'estatusAuditoriaFinal';
            // Asegúrate de llamar a save() en la variable actualizada
            $encabezadoAuditoriaCorteEstatus->save();
            return back()->with('cambio-estatus', 'Se Cambio a estatus: AUDITORIA FINAL.')->with('pageSlug', $pageSlug);
        }

        // Verificar si todos los checkboxes tienen el valor deseado
        $allChecked = trim($request->input('ingreso_ticket_estatus')) === "1" &&
            trim($request->input('sellado_paquete_estatus')) === "1";

        $request->session()->put('estatus_checked_AuditoriaBulto', $allChecked);

        // Verificar si ya existe un registro con el mismo valor de orden_id
        $existeOrden = AuditoriaBulto::where('id', $idBulto)->first();
        //dd($existeOrden);
        // Si ya existe un registro con el mismo valor de orden_id, puedes mostrar un mensaje de error o tomar alguna otra acción
        if ($existeOrden) {
            $existeOrden->nombre = implode(',', $request->input('nombre'));
            $existeOrden->mesa = $request->input('mesa');
            $existeOrden->auditor = $request->input('auditor');
            $existeOrden->cantidad_bulto = $request->input('cantidad_bulto');
            $existeOrden->pieza_paquete = $request->input('pieza_paquete');
            $existeOrden->ingreso_ticket = $request->input('ingreso_ticket');
            $existeOrden->ingreso_ticket_estatus = $request->input('ingreso_ticket_estatus');
            $existeOrden->sellado_paquete = $request->input('sellado_paquete');
            $existeOrden->sellado_paquete_estatus = $request->input('sellado_paquete_estatus');
            $existeOrden->defecto = $request->input('defecto');
            $existeOrden->cantidad_defecto = $request->input('cantidad_defecto');
            $existeOrden->porcentaje = $request->input('porcentaje');
            $existeOrden->comentario = $request->input('comentario_bulto');


            $existeOrden->save();
            //dd($existeOrden);
            return back()->with('sobre-escribir', 'Actualilzacion realizada con exito');
        }

        return back()->with('success', 'Datos guardados correctamente.')->with('pageSlug', $pageSlug);
    }

    public function formAuditoriaFinal(Request $request)
    {
        $pageSlug = '';
        // Validar los datos del formulario si es necesario
        // Obtener el ID seleccionado desde el formulario
        $idSeleccionado = $request->input('id');
        $idAuditoriaFinal = $request->input('idAuditoriaFinal');
        $orden = $request->input('orden');
        $accion = $request->input('accion'); // Obtener el valor del campo 'accion'


        if ($accion === 'finalizar') {
            // Buscar la fila en la base de datos utilizando el modelo AuditoriaMarcada
            $auditoria = DatoAX::findOrFail($idSeleccionado);

            // Actualizar el valor de la columna deseada
            $auditoria->estatus = 'fin';
            $auditoria->save();
            $auditoriaFinal = AuditoriaFinal::where('id', $idAuditoriaFinal)->first();
            $auditoriaFinal->estatus = 'fin';
            // Asegúrate de llamar a save() en la variable actualizada
            $auditoriaFinal->save();

            $encabezadoAuditoriaCorteEstatus = EncabezadoAuditoriaCorte::where('id', $idAuditoriaFinal)->first();
            $encabezadoAuditoriaCorteEstatus->estatus = 'fin';
            // Asegúrate de llamar a save() en la variable actualizada
            $encabezadoAuditoriaCorteEstatus->save();
            return back()->with('cambio-estatus', 'Fin 👋.')->with('pageSlug', $pageSlug);
        }


        // Verificar si ya existe un registro con el mismo valor de orden_id
        $existeOrden = AuditoriaFinal::where('id', $idAuditoriaFinal)->first();
        // Verificar si todos los checkboxes tienen el valor de "1"
        $allChecked = trim($request->input('aceptado_rechazado')) === "1";
        // Guardar el estado del checkbox en la sesión
        $request->session()->put('estatus_checked_AuditoriaFinal', $allChecked);
        // Si ya existe un registro con el mismo valor de orden_id, puedes mostrar un mensaje de error o tomar alguna otra acción
        if ($existeOrden) {
            //$existeOrden->supervisor_corte = $request->input('supervisor_corte');
            $existeOrden->aceptado_condicion = $request->input('aceptado_condicion');
            $existeOrden->aceptado_rechazado = $request->input('aceptado_rechazado');

            $existeOrden->save();
            //dd($existeOrden);

            return back()->with('sobre-escribir', 'Actualilzacion realizada con exito');
        }

        return back()->with('success', 'Datos guardados correctamente.')->with('pageSlug', $pageSlug);
    }

    public function agregarDefecto(Request $request)
    {
        $nuevoDefecto = new CategoriaDefectoCorte();
        $nuevoDefecto->nombre = $request->nombre;
        $nuevoDefecto->area = $request->area;
        $nuevoDefecto->estado = $request->estado;
        $nuevoDefecto->save();

        return response()->json(['success' => true]);
    }

    // Actualizacion registros corte
    public function formEncabezadoAuditoriaCorteV2(Request $request)
    {
        $pageSlug = '';
        // Validar los datos del formulario si es necesario
        //$idSeleccionado = $request->input('id');
        $orden = $request->input('orden');
        $estilo = $request->input('estilo');
        $planta = $request->input('planta');
        $temporada = $request->input('temporada');
        $cliente = $request->input('cliente');
        $color = $request->input('color_id');
        $eventoInicial = $request->input('evento');
        $material = $request->input('material');
        $pieza = $request->input('pieza');
        $qtysched_id = $request->input('qtysched_id');
        $trazo = $request->input('trazo');
        $lienzo = $request->input('lienzo');
        $total_evento = $request->input('total_evento');
        // idEncabezadoAuditoriaCorte sirve para evalular un dato que se genero desde el apartado donde se genera un evento adicional y requiere de datos de captura 
        $idEncabezadoAuditoriaCorte = $request->input('idEncabezadoAuditoriaCorte');


        $encabezadoAuditoriaCorte = EncabezadoAuditoriaCorteV2::where('id', $idEncabezadoAuditoriaCorte)->first();
        //dd($encabezadoAuditoriaCorte);
        // Verificar si ya existen datos 
        if ($encabezadoAuditoriaCorte) {
            //dd($request->all());
            $encabezadoAuditoriaCorte->pieza = $request->input('pieza');
            if ($request->input('color_id')) {
                $encabezadoAuditoriaCorte->color_id = $request->input('color_id');
            }
            $encabezadoAuditoriaCorte->lienzo = $request->input('lienzo');
            $encabezadoAuditoriaCorte->qtysched_id = $request->input('qtysched_id');
            $encabezadoAuditoriaCorte->estatus = 'estatusAuditoriaMarcada';
            $encabezadoAuditoriaCorte->save();

            return back()->with('sobre-escribir', 'Ya existen datos para este registro.');
        }

        for ($i = 1; $i <= $total_evento; $i++) {
            // Realizar la actualización en la base de datos
            $auditoria = new EncabezadoAuditoriaCorteV2();
            $auditoria->orden_id = $orden;
            $auditoria->estilo_id = $estilo;
            $auditoria->planta_id = $planta;
            $auditoria->temporada_id = $temporada;
            $auditoria->cliente_id = $cliente;
            $auditoria->color_id = $color;
            $auditoria->material = $material;
            $auditoria->pieza = $pieza;
            $auditoria->qtysched_id = $qtysched_id;
            $auditoria->trazo = $trazo;
            $auditoria->lienzo = $lienzo;
            $auditoria->total_evento = $total_evento;
            $auditoria->evento = $i;

            if ($i == $eventoInicial) {
                $auditoria->estatus = "estatusAuditoriaMarcada"; // Cambiar estatus solo para el primer registro
            } else {
                $auditoria->estatus = "proceso"; // Mantener el valor "proceso" para los demás registros
            }
            $auditoria->save();

            //Aqui obtenemos el id del nuevo registro
            $encabezado_id = $auditoria->id;

            // Tabla AuditoriaMarcada
            $auditoriaMarcada = new AuditoriaCorteMarcada();
            $auditoriaMarcada->encabezado_id = $encabezado_id;
            $auditoriaMarcada->save();

            // Tabla AuditoriaTendido
            $auditoriaTendido = new AuditoriaCorteTendido();
            $auditoriaTendido->encabezado_id = $encabezado_id;
            $auditoriaTendido->save();

            // Tabla Lectra
            $lectra = new AuditoriaCorteLectra(); // Cambié el nombre a AuditoriaCorteLectra según el nuevo nombre de la tabla
            $lectra->encabezado_id = $encabezado_id;
            $lectra->save();

            // Tabla AuditoriaBulto
            $auditoriaBulto = new AuditoriaCorteBulto(); // Cambié el nombre a AuditoriaCorteBulto según el nuevo nombre de la tabla
            $auditoriaBulto->encabezado_id = $encabezado_id;
            $auditoriaBulto->save();

            // Tabla AuditoriaFinal
            $auditoriaFinal = new AuditoriaCorteFinal(); // Cambié el nombre a AuditoriaCorteFinal según el nuevo nombre de la tabla
            $auditoriaFinal->encabezado_id = $encabezado_id;
            $auditoriaFinal->save();

            if ($i == $eventoInicial) {
                $idEvento1 = $auditoria->id;
            }
        }
        //dd($idEvento1);
        return redirect()->route('auditoriaCorte.auditoriaCorteV2', ['id' => $idEvento1, 'orden' => $orden])->with('success', 'Datos guardados correctamente.')->with('pageSlug', $pageSlug);
    }

    public function auditoriaCorteV2($id, $orden)
    {
        $pageSlug = '';
        $categorias = $this->cargarCategoriasSinAX();
        $auditorDato = Auth::user()->name;
        $mesesEnEspanol = [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre'
        ];
        // Obtener el registro correspondiente en la tabla AuditoriaMarcada si existe
        $encabezadoAuditoriaCorte = EncabezadoAuditoriaCorteV2::where('id', $id)->first();
        $auditoriaMarcada = AuditoriaCorteMarcada::where('encabezado_id', $id)->first();
        $auditoriaTendido = AuditoriaCorteTendido::where('encabezado_id', $id)->first();
        $Lectra = AuditoriaCorteLectra::where('encabezado_id', $id)->first();
        $auditoriaBulto = AuditoriaCorteBulto::where('encabezado_id', $id)->first();
        $auditoriaFinal = AuditoriaCorteFinal::where('encabezado_id', $id)->first();
        //dd($encabezadoAuditoriaCorte, $auditoriaMarcada, $auditoriaTendido, $Lectra,  $auditoriaBulto, $auditoriaFinal);
        $auditoriaMarcadaTalla = DB::connection('sqlsrv')->table('OrdenesCorte_View')->where('op', $orden)
            ->whereNotNull('sizename') // Descartar valores NULL
            ->where('sizename', '<>', '') // Descartar valores vacíos
            ->where('period', '>', '202312')
            ->select('sizename')
            ->distinct()
            ->pluck('sizename');

        // apartado para validar los checbox

        //$mostrarFinalizarMarcada = $auditoriaMarcada ? session('estatus_checked_AuditoriaMarcada') : false; 
        $mostrarFinalizarMarcada = isset($auditoriaMarcada) && $auditoriaMarcada->yarda_orden_estatus == 1;
        //dd($mostrarFinalizarMarcada);
        //$mostrarFinalizarTendido = $auditoriaTendido ? session('estatus_checked_AuditoriaTendido') : false;
        $mostrarFinalizarTendido = isset($auditoriaTendido) && $auditoriaTendido->codigo_material_estatus == 1 &&
            $auditoriaTendido->codigo_color_estatus == 1 &&
            $auditoriaTendido->informacion_trazo_estatus == 1 &&
            $auditoriaTendido->cantidad_lienzo_estatus == 1 &&
            $auditoriaTendido->longitud_tendido_estatus == 1 &&
            $auditoriaTendido->ancho_tendido_estatus == 1 &&
            $auditoriaTendido->material_relajado_estatus == 1 &&
            $auditoriaTendido->empalme_estatus == 1 &&
            $auditoriaTendido->cara_material_estatus == 1 &&
            $auditoriaTendido->tono_estatus == 1 &&
            $auditoriaTendido->yarda_marcada_estatus == 1;

        //$mostrarFinalizarLectra = $Lectra ? session('estatus_checked_Lectra') : false; 
        $mostrarFinalizarLectra = isset($Lectra) && $Lectra->pieza_contrapatron_estatus == 1;

        //$mostrarFinalizarBulto = $auditoriaBulto ? session('estatus_checked_AuditoriaBulto') : false;
        $mostrarFinalizarBulto = isset($auditoriaBulto) && $auditoriaBulto->ingreso_ticket_estatus == 1 && $auditoriaBulto->sellado_paquete_estatus == 1;

        //$mostrarFinalizarFinal = $auditoriaFinal ? session('estatus_checked_AuditoriaFinal') : false; 
        $mostrarFinalizarFinal = isset($auditoriaFinal) && $auditoriaFinal->aceptado_rechazado == 1;
        //dd($mostrarFinalizarMarcada);
        $operarioCorte = DB::connection('sqlsrv')->table('OperariosCorte_View')->get();
        //dd($operarioCorte);
        return view('auditoriaCorte.auditoriaCorteV2', array_merge($categorias, [
            'mesesEnEspanol' => $mesesEnEspanol,
            'pageSlug' => $pageSlug,
            'auditoriaMarcada' => $auditoriaMarcada,
            'auditoriaTendido' => $auditoriaTendido,
            'Lectra' => $Lectra,
            'auditoriaBulto' => $auditoriaBulto,
            'auditoriaFinal' => $auditoriaFinal,
            'mostrarFinalizarMarcada' => $mostrarFinalizarMarcada,
            'mostrarFinalizarTendido' => $mostrarFinalizarTendido,
            'mostrarFinalizarLectra' => $mostrarFinalizarLectra,
            'mostrarFinalizarBulto' => $mostrarFinalizarBulto,
            'mostrarFinalizarFinal' => $mostrarFinalizarFinal,
            'encabezadoAuditoriaCorte' => $encabezadoAuditoriaCorte,
            'auditoriaMarcadaTalla' => $auditoriaMarcadaTalla,
            'operarioCorte' => $operarioCorte,
            'auditorDato' => $auditorDato
        ]));
    }

    public function agregarEventoCorteV2(Request $request)
    {
        $pageSlug = '';
        $orden_id = $request->input('orden_id');
        $cliente_id = $request->input('cliente_id');

        // Obtener el máximo evento actual para la orden_id
        $maxEvento = EncabezadoAuditoriaCorteV2::where('orden_id', $orden_id)->max('evento');
        $nuevoEvento = $maxEvento + 1;

        // Actualizar los registros existentes
        EncabezadoAuditoriaCorteV2::where('orden_id', $orden_id)->update(['total_evento' => $nuevoEvento]);

        // Crear una nueva instancia del modelo
        $encabezadoAuditoriaCorte = new EncabezadoAuditoriaCorteV2();
        $encabezadoAuditoriaCorte->orden_id = $orden_id;
        $encabezadoAuditoriaCorte->evento = $nuevoEvento;
        $encabezadoAuditoriaCorte->total_evento = $nuevoEvento;
        $encabezadoAuditoriaCorte->estatus = "proceso";
        $encabezadoAuditoriaCorte->estilo_id = $request->input('estilo_id');
        $encabezadoAuditoriaCorte->planta_id = $request->input('planta_id');
        $encabezadoAuditoriaCorte->temporada_id = $request->input('temporada_id');
        $encabezadoAuditoriaCorte->cliente_id = $request->input('cliente_id');
        $encabezadoAuditoriaCorte->color_id = $request->input('color_id');
        $encabezadoAuditoriaCorte->material = $request->input('material');
        $encabezadoAuditoriaCorte->pieza = $request->input('pieza');
        $encabezadoAuditoriaCorte->trazo = $request->input('trazo');
        $encabezadoAuditoriaCorte->lienzo = $request->input('lienzo');
        $encabezadoAuditoriaCorte->save();

        //Aqui obtenemos el id del nuevo registro
        $encabezado_id = $encabezadoAuditoriaCorte->id;

        //dd($encabezado_id);
        // Tabla AuditoriaMarcada
        $auditoriaMarcada = new AuditoriaCorteMarcada();
        $auditoriaMarcada->encabezado_id = $encabezado_id;
        $auditoriaMarcada->save();

        // Tabla AuditoriaTendido
        $auditoriaTendido = new AuditoriaCorteTendido();
        $auditoriaTendido->encabezado_id = $encabezado_id;
        $auditoriaTendido->save();

        // Tabla Lectra
        $lectra = new AuditoriaCorteLectra(); // Cambié el nombre a AuditoriaCorteLectra según el nuevo nombre de la tabla
        $lectra->encabezado_id = $encabezado_id;
        $lectra->save();

        // Tabla AuditoriaBulto
        $auditoriaBulto = new AuditoriaCorteBulto(); // Cambié el nombre a AuditoriaCorteBulto según el nuevo nombre de la tabla
        $auditoriaBulto->encabezado_id = $encabezado_id;
        $auditoriaBulto->save();

        // Tabla AuditoriaFinal
        $auditoriaFinal = new AuditoriaCorteFinal(); // Cambié el nombre a AuditoriaCorteFinal según el nuevo nombre de la tabla
        $auditoriaFinal->encabezado_id = $encabezado_id;
        $auditoriaFinal->save();

        // Redireccionar a la página anterior
        return redirect()->route('auditoriaCorte.inicioAuditoriaCorte',)->with('success', '  Evento agregado correctamente.')->with('pageSlug', $pageSlug);
    }

    public function formAuditoriaMarcadaV2(Request $request)
    {
        $pageSlug = '';
        $idAuditoriaMarcada = $request->input('idAuditoriaMarcada');
        $accion = $request->input('accion'); // Obtener el valor del campo 'accion' del botón finalizar
        if ($accion === 'finalizar') {
            $encabezadoAuditoriaCorteEstatus = EncabezadoAuditoriaCorteV2::where('id', $idAuditoriaMarcada)->first();
            $encabezadoAuditoriaCorteEstatus->estatus = 'estatusAuditoriaTendido';
            $encabezadoAuditoriaCorteEstatus->save();
            return back()->with('cambio-estatus', 'Se Cambió a estatus: AUDITORIA DE TENDIDO.')->with('pageSlug', $pageSlug);
        }

        $existeOrden = AuditoriaCorteMarcada::where('encabezado_id', $idAuditoriaMarcada)->first();
        //dd($request->all());
        if ($existeOrden) {
            // Actualizar otros datos
            $existeOrden->yarda_orden = $request->input('yarda_orden');
            $existeOrden->yarda_orden_estatus = $request->input('yarda_orden_estatus');

            // Almacenar tallas, siempre
            $existeOrden->tallas = implode(',', $request->input('tallas', []));
            $existeOrden->total_piezas = implode(',', $request->input('total_piezas', []));

            // Validar si tallas_parciales tiene datos válidos (no null)
            $tallasParciales = $request->input('tallas_parciales', []);
            if (!empty($tallasParciales) && $tallasParciales[0] !== null) {
                // Solo se almacena si tallas_parciales tiene datos válidos
                $existeOrden->tallas_parciales = implode(',', $tallasParciales);
                $existeOrden->bultos_parciales = implode(',', $request->input('bultos_parciales', []));
                $existeOrden->total_piezas_parciales = implode(',', $request->input('total_piezas_parciales', []));
            } else {
                // Si no hay datos válidos en tallas_parciales, no se almacenan estos campos
                $existeOrden->tallas_parciales = null;
                $existeOrden->bultos_parciales = null;
                $existeOrden->total_piezas_parciales = null;
            }

            // Almacenar los bultos, si están presentes
            $existeOrden->bultos = implode(',', $request->input('bultos', []));

            // Otros campos
            $existeOrden->largo_trazo = $request->input('largo_trazo');
            $existeOrden->ancho_trazo = $request->input('ancho_trazo');

            // Guardar los cambios
            $existeOrden->save();

            return back()->with('sobre-escribir', 'Actualización realizada con éxito');
        }

        return back()->with('success', 'Datos guardados correctamente.')->with('pageSlug', $pageSlug);
    }

    public function formAuditoriaTendidoV2(Request $request)
    {
        $pageSlug = '';
        // Validar los datos del formulario si es necesario
        $idAuditoriaTendido = $request->input('idAuditoriaTendido');
        $accion = $request->input('accion'); // Obtener el valor del campo 'accion'
        //dd($idAuditoriaTendido);

        if ($accion === 'finalizar') {
            $encabezadoAuditoriaCorteEstatus = EncabezadoAuditoriaCorteV2::where('id', $idAuditoriaTendido)->first();
            $encabezadoAuditoriaCorteEstatus->estatus = 'estatusLectra';
            // Asegúrate de llamar a save() en la variable actualizada
            $encabezadoAuditoriaCorteEstatus->save();
            return back()->with('cambio-estatus', 'Se Cambio a estatus: LECTRA.')->with('pageSlug', $pageSlug);
        }

        $allChecked = trim($request->input('codigo_material_estatus')) === "1" &&
            trim($request->input('codigo_color_estatus')) === "1" &&
            trim($request->input('informacion_trazo_estatus')) === "1" &&
            trim($request->input('cantidad_lienzo_estatus')) === "1" &&
            trim($request->input('longitud_tendido_estatus')) === "1" &&
            trim($request->input('ancho_tendido_estatus')) === "1" &&
            trim($request->input('material_relajado_estatus')) === "1" &&
            trim($request->input('empalme_estatus')) === "1" &&
            trim($request->input('cara_material_estatus')) === "1" &&
            trim($request->input('tono_estatus')) === "1" &&
            trim($request->input('yarda_marcada_estatus')) === "1";

        $request->session()->put('estatus_checked_AuditoriaTendido', $allChecked);
        // Verificar si ya existe un registro con el mismo valor de orden_id
        $existeOrden = AuditoriaCorteTendido::where('encabezado_id', $idAuditoriaTendido)->first();
        //dd($existeOrden);

        // Si ya existe un registro con el mismo valor de orden_id, puedes mostrar un mensaje de error o tomar alguna otra acción
        if ($existeOrden) {
            $existeOrden->nombre = implode(',', $request->input('nombre'));
            $existeOrden->mesa = $request->input('mesa');
            $existeOrden->auditor = $request->input('auditor');
            $existeOrden->codigo_material = $request->input('codigo_material');
            $existeOrden->codigo_material_estatus = $request->input('codigo_material_estatus');
            $existeOrden->codigo_color = $request->input('codigo_color');
            $existeOrden->codigo_color_estatus = $request->input('codigo_color_estatus');
            $existeOrden->informacion_trazo = $request->input('informacion_trazo');
            $existeOrden->informacion_trazo_estatus = $request->input('informacion_trazo_estatus');
            $existeOrden->cantidad_lienzo = $request->input('cantidad_lienzo');
            $existeOrden->cantidad_lienzo_estatus = $request->input('cantidad_lienzo_estatus');
            $existeOrden->longitud_tendido = $request->input('longitud_tendido');
            $existeOrden->longitud_tendido_estatus = $request->input('longitud_tendido_estatus');
            $existeOrden->ancho_tendido = $request->input('ancho_tendido');
            $existeOrden->ancho_tendido_estatus = $request->input('ancho_tendido_estatus');
            $existeOrden->material_relajado = $request->input('material_relajado');
            $existeOrden->material_relajado_estatus = $request->input('material_relajado_estatus');
            $existeOrden->empalme = $request->input('empalme');
            $existeOrden->empalme_estatus = $request->input('empalme_estatus');
            $existeOrden->cara_material = $request->input('cara_material');
            $existeOrden->cara_material_estatus = $request->input('cara_material_estatus');
            $existeOrden->tono = $request->input('tono');
            $existeOrden->tono_estatus = $request->input('tono_estatus');
            $existeOrden->alineacion_tendido = $request->input('alineacion_tendido');
            $existeOrden->alineacion_tendido_estatus = "1";
            $existeOrden->arruga_tendido = $request->input('arruga_tendido');
            $existeOrden->arruga_tendido_estatus = "1";
            $existeOrden->defecto_material = implode(',', $request->input('defecto_material'));
            $existeOrden->yarda_marcada = $request->input('yarda_marcada');
            $existeOrden->yarda_marcada_estatus = $request->input('yarda_marcada_estatus');
            $existeOrden->accion_correctiva = $request->input('accion_correctiva');
            $existeOrden->bio_tension = $request->input('bio_tension');
            $existeOrden->velocidad = $request->input('velocidad');
            $existeOrden->comentario_tendido = $request->input('comentario_tendido');
            //$existeOrden->libera_tendido = $request->input('libera_tendido');

            $existeOrden->save();
            //dd($existeOrden);
            return back()->with('sobre-escribir', 'Actualilzacion realizada con exito');
        }

        return back()->with('success', 'Datos guardados correctamente.')->with('pageSlug', $pageSlug);
    }


    public function formLectraV2(Request $request)
    {
        $pageSlug = '';
        //dd($request->all());
        $idLectra = $request->input('idLectra');
        $accion = $request->input('accion'); // Obtener el valor del campo 'accion'
        $simetriaPiezas = array_map('strtoupper', $request->input('simetria_piezas', []));

        if ($accion === 'finalizar') {
            $encabezadoAuditoriaCorteEstatus = EncabezadoAuditoriaCorteV2::where('id', $idLectra)->first();
            $encabezadoAuditoriaCorteEstatus->estatus = 'estatusAuditoriaBulto';
            // Asegúrate de llamar a save() en la variable actualizada
            $encabezadoAuditoriaCorteEstatus->save();
            return back()->with('cambio-estatus', 'Se Cambio a estatus: AUDITORIA EN BULTOS.')->with('pageSlug', $pageSlug);
        }

        $allChecked = trim($request->input('pieza_contrapatron_estatus')) === "1";

        $request->session()->put('estatus_checked_Lectra', $allChecked);
        // Verificar si ya existe un registro con el mismo valor de orden_id
        $existeOrden = AuditoriaCorteLectra::where('encabezado_id', $idLectra)->first();
        //dd($request->input('x1'), $request->input('y1'));

        // Si ya existe un registro con el mismo valor de orden_id, puedes mostrar un mensaje de error o tomar alguna otra acción
        if ($existeOrden) {
            $existeOrden->nombre = implode(',', $request->input('nombre'));
            $existeOrden->mesa = $request->input('mesa');
            $existeOrden->auditor = $request->input('auditor');
            $existeOrden->simetria_piezas = implode(',', $simetriaPiezas);
            $existeOrden->panel_x1 = implode(',', $request->input('panel_x1', []));
            $existeOrden->panel_x2 = implode(',', $request->input('panel_x2', []));
            $existeOrden->panel_y1 = implode(',', $request->input('panel_y1', []));
            $existeOrden->panel_y2 = implode(',', $request->input('panel_y2', []));
            //$existeOrden->pieza_contrapatron = $request->input('pieza_contrapatron');
            $existeOrden->pieza_contrapatron_estatus = $request->input('pieza_contrapatron_estatus');
            $existeOrden->pieza_inspeccionada = $request->input('pieza_inspeccionada');
            $existeOrden->cantidad_defecto = $request->input('cantidad_defecto');
            $existeOrden->defecto = implode(',', $request->input('defecto'));
            $existeOrden->porcentaje = $request->input('porcentaje');
            $existeOrden->estado_validacion = $request->input('estado_validacion');
            $existeOrden->nivel_aql = $request->input('nivel_aql');
            $existeOrden->comentario_lectra = $request->input('comentario_lectra');


            $existeOrden->save();
            //dd($existeOrden);
            return back()->with('sobre-escribir', 'Actualilzacion realizada con exito');
        }

        return back()->with('success', 'Datos guardados correctamente.')->with('pageSlug', $pageSlug);
    }

    public function formAuditoriaBultoV2(Request $request)
    {
        $pageSlug = '';
        $idBulto = $request->input('idBulto');
        $accion = $request->input('accion'); // Obtener el valor del campo 'accion'
        //dd($request->input());
        if ($accion === 'finalizar') {

            $encabezadoAuditoriaCorteEstatus = EncabezadoAuditoriaCorteV2::where('id', $idBulto)->first();
            $encabezadoAuditoriaCorteEstatus->estatus = 'estatusAuditoriaFinal';
            // Asegúrate de llamar a save() en la variable actualizada
            $encabezadoAuditoriaCorteEstatus->save();
            return back()->with('cambio-estatus', 'Se Cambio a estatus: AUDITORIA FINAL.')->with('pageSlug', $pageSlug);
        }

        // Verificar si todos los checkboxes tienen el valor deseado
        $allChecked = trim($request->input('ingreso_ticket_estatus')) === "1" &&
            trim($request->input('sellado_paquete_estatus')) === "1";

        $request->session()->put('estatus_checked_AuditoriaBulto', $allChecked);

        // Verificar si ya existe un registro con el mismo valor de orden_id
        $existeOrden = AuditoriaCorteBulto::where('encabezado_id', $idBulto)->first();
        //dd($existeOrden);
        // Si ya existe un registro con el mismo valor de orden_id, puedes mostrar un mensaje de error o tomar alguna otra acción
        if ($existeOrden) {
            $existeOrden->nombre = implode(',', $request->input('nombre'));
            $existeOrden->mesa = $request->input('mesa');
            $existeOrden->auditor = $request->input('auditor');
            $existeOrden->cantidad_bulto = $request->input('cantidad_bulto');
            $existeOrden->pieza_paquete = $request->input('pieza_paquete');
            $existeOrden->ingreso_ticket = $request->input('ingreso_ticket');
            $existeOrden->ingreso_ticket_estatus = $request->input('ingreso_ticket_estatus');
            $existeOrden->sellado_paquete = $request->input('sellado_paquete');
            $existeOrden->sellado_paquete_estatus = $request->input('sellado_paquete_estatus');
            $existeOrden->defecto = $request->input('defecto');
            $existeOrden->cantidad_defecto = $request->input('cantidad_defecto');
            $existeOrden->porcentaje = $request->input('porcentaje');
            $existeOrden->comentario = $request->input('comentario_bulto');


            $existeOrden->save();
            //dd($existeOrden);
            return back()->with('sobre-escribir', 'Actualilzacion realizada con exito');
        }

        return back()->with('success', 'Datos guardados correctamente.')->with('pageSlug', $pageSlug);
    }

    public function formAuditoriaFinalV2(Request $request)
    {
        $pageSlug = '';
        $idAuditoriaFinal = $request->input('idAuditoriaFinal');
        $accion = $request->input('accion'); // Obtener el valor del campo 'accion'
        //dd($idAuditoriaFinal);

        if ($accion === 'finalizar') {

            $encabezadoAuditoriaCorteEstatus = EncabezadoAuditoriaCorteV2::where('id', $idAuditoriaFinal)->first();
            $encabezadoAuditoriaCorteEstatus->estatus = 'fin';
            // Asegúrate de llamar a save() en la variable actualizada
            $encabezadoAuditoriaCorteEstatus->save();
            return back()->with('cambio-estatus', 'Fin 👋.')->with('pageSlug', $pageSlug);
        }


        // Verificar si ya existe un registro con el mismo valor de orden_id
        $existeOrden = AuditoriaCorteFinal::where('encabezado_id', $idAuditoriaFinal)->first();
        // Verificar si todos los checkboxes tienen el valor de "1"
        $allChecked = trim($request->input('aceptado_rechazado')) === "1";
        // Guardar el estado del checkbox en la sesión
        $request->session()->put('estatus_checked_AuditoriaFinal', $allChecked);
        // Si ya existe un registro con el mismo valor de orden_id, puedes mostrar un mensaje de error o tomar alguna otra acción
        if ($existeOrden) {
            //$existeOrden->supervisor_corte = $request->input('supervisor_corte');
            $existeOrden->aceptado_condicion = $request->input('aceptado_condicion');
            $existeOrden->aceptado_rechazado = $request->input('aceptado_rechazado');

            $existeOrden->save();
            //dd($existeOrden);

            return back()->with('sobre-escribir', 'Actualilzacion realizada con exito');
        }

        return back()->with('success', 'Datos guardados correctamente.')->with('pageSlug', $pageSlug);
    }

    public function buscarOrdenCorte(Request $request)
    {
        // Obtiene el parámetro 'search' del request
        $busqueda = $request->input('search');

        // Log para verificar que el request recibe el dato
        //Log::info("Parámetro 'search' recibido en buscarOrdenCorte", ['busqueda' => $busqueda]);

        // Conexión a la base de datos SQL Server y consulta en la vista OrdenesCorte_View
        $BusquedaOrdenCorte = DB::connection('sqlsrv')->table('OrdenesCorte_View')
            ->select('op', 'inventcolorid', 'estilo') // Selecciona solo los campos que nos interesan
            ->distinct(); // Aplica distinct para obtener combinaciones únicas

        if ($busqueda) {
            $BusquedaOrdenCorte->where('op', 'LIKE', "%{$busqueda}%");
        }

        // Limitar a 100 resultados (opcional)
        $resultados = $BusquedaOrdenCorte->limit(100)->get();

        // Por cada resultado, se consulta si ya existe un registro en EncabezadoAuditoriaCorte
        // que coincida con 'orden_id' (de OrdenesCorte_View->op) y 'color_id' (de inventcolorid)
        foreach ($resultados as $resultado) {
            // Se obtiene el registro de EncabezadoAuditoriaCorte
            $encabezado = EncabezadoAuditoriaCorteV2::where('orden_id', $resultado->op)
                ->where('color_id', $resultado->inventcolorid)
                ->first();

            // Se guarda un booleano que indica si ya existe o no
            $resultado->yaIniciada = $encabezado ? true : false;
            // Además, se puede adjuntar el registro encontrado (o null) para ver todos sus datos
            $resultado->datosEncabezado = $encabezado;
        }

        // Log para verificar los resultados obtenidos de la consulta, ahora incluyendo los datos de encabezado
        //Log::info("Resultados de la búsqueda en OrdenesCorte_View", ['resultados' => $resultados]);

        return response()->json($resultados);
    }
}
