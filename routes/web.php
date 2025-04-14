<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\CalidadScreenPrintController;
use App\Http\Controllers\AuditoriaCorteController;
use App\Http\Controllers\EvaluacionCorteController;
use App\Http\Controllers\CalidadProcesoPlancha;
use App\Http\Controllers\DatosAuditoriaEtiquetas;
use App\Http\Controllers\InspeccionEstampadoHorno;
use  App\Http\Controllers\AuditoriaProcesoCorteController;
use App\Http\Controllers\AuditoriaProcesoController;
use App\Http\Controllers\AuditoriaProcesoV2Controller;
use App\Http\Controllers\AuditoriaAQLController;
use App\Http\Controllers\Maquila;
use App\Http\Controllers\viewlistaFormularios;
use App\Http\Controllers\CorteFinalController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\DashboardPlanta1Controller;
use App\Http\Controllers\DashboardPlanta1PorDiaController;
use App\Http\Controllers\DashboardPlanta2Controller;

use App\Http\Controllers\DashboardPlanta1DetalleController;
use App\Http\Controllers\DashboardPlanta2DetalleController;
use App\Http\Controllers\reporteriaInternaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AltaYBajaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\redireccionar;
use App\Http\Controllers\Segundas;
use App\Http\Controllers\Terceras;
use App\Http\Controllers\DashboardCostosController;
use App\Http\Controllers\DashboardComparativoModuloPlanta1Controller;
use App\Http\Controllers\GestionController;
use App\Http\Controllers\AuditoriaAQL_v2Controller;
use App\Http\Controllers\ConsutlaEstatusController;
use App\Http\Controllers\EtiquetasV2Controller;
use App\Http\Controllers\GestionUsuarioController;
use App\Http\Controllers\ScreenV2Controller;
use App\Http\Controllers\DashboardPorDiaV2Controller;
use App\Http\Controllers\DashboardBusquedaOPController;
use App\Http\Controllers\AuditoriaKanBanController;
use App\Http\Controllers\BultosNoFinalizadosController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Rutas de autenticación
Auth::routes();

// Ruta para la página principal
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return view('auth.login');
});

// Ruta para la página de inicio después de iniciar sesión
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

// Sobrescribir la ruta de login para usar el método personalizado
Route::post('login', [LoginController::class, 'login'])->name('login');

// Rutas adicionales que requieren autenticación
Route::group(['middleware' => 'auth'], function () {
    // Aquí puedes añadir todas las rutas que necesitan autenticación
    Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
    Route::get('/gestionUsuario', [GestionUsuarioController::class, 'gestionUsuario'])->name('gestionUsuario');

    // Añade aquí el resto de tus rutas protegidas
    Route::get('/tipoAuditorias', [UserManagementController::class, 'tipoAuditorias']);
    Route::post('/AddUser', [UserManagementController::class, 'AddUser'])->name('user.AddUser');
    Route::get('/puestos', [UserManagementController::class, 'puestos']);
    Route::post('/editUser', [UserManagementController::class, 'editUser'])->name('users.editUser');

    Route::post('/blockUser/{noEmpleado}', [UserManagementController::class, 'blockUser'])->name('blockUser');
    Route::put('/blockUser/{noEmpleado}', [UserManagementController::class, 'blockUser'])->name('blockUser');

    //renovacion a una segunda version del controller con correcion de cargas
    Route::get('/api/porcentajesPorDiaV2-data', [HomeController::class, 'porcentajesPorDiaV2'])->name('api.porcentajesPorDiaV2');
    Route::get('/dashboard-data-dia-v2', [HomeController::class, 'getDashboardDataDiaV2'])->name('dashboard.dataDiaV2');
    Route::get('/dashboard-data-semana-v2', [HomeController::class, 'getDashboardDataSemanaV2'])->name('dashboard.dataSemanaV2');
    Route::get('/dashboard-mensual-general-v2', [HomeController::class, 'getMensualGeneralV2'])->name('dashboard.mensualGeneralV2');
    Route::get('/dashboard/mensual-por-cliente-v2', [HomeController::class, 'getMensualPorClienteV2'])->name('dashboard.mensualPorClienteV2');
    Route::get('/dashboard/mensualPorModulo-v2', [HomeController::class, 'getMensualPorModuloV2'])->name('dashboard.mensualPorModuloV2');
    Route::get('/dashboard/defecto-mensual-v2', [HomeController::class, 'getDefectoMensualV2'])->name('dashboard.defectoMensualV2');

    Route::get('/listaFormularios', [viewlistaFormularios::class, 'listaFormularios'])->name('viewlistaFormularios');

    Route::get('/inicioAuditoriaCorte', [AuditoriaCorteController::class, 'inicioAuditoriaCorte'])->name('auditoriaCorte.inicioAuditoriaCorte')->middleware('checkroleandplant1');
    Route::post('/formAuditoriaCortes', [AuditoriaCorteController::class, 'formAuditoriaCortes'])->name('auditoriaCorte.formAuditoriaCortes');
    Route::post('/formRechazoCorte', [AuditoriaCorteController::class, 'formRechazoCorte'])->name('auditoriaCorte.formRechazoCorte');
    Route::post('/formAprobarCorte', [AuditoriaCorteController::class, 'formAprobarCorte'])->name('auditoriaCorte.formAprobarCorte');
    Route::post('/agregarEventoCorte', [AuditoriaCorteController::class, 'agregarEventoCorte'])->name('auditoriaCorte.agregarEventoCorte')->middleware('checkroleandplant1');
    Route::get('/auditoriaCorte/{id}/{orden}', [AuditoriaCorteController::class, 'auditoriaCorte'])->name('auditoriaCorte.auditoriaCorte')->middleware('checkroleandplant1');
    Route::get('/altaAuditoriaCorte/{orden}/{color}', [AuditoriaCorteController::class, 'altaAuditoriaCorte'])->name('auditoriaCorte.altaAuditoriaCorte')->middleware('checkroleandplant1');
    Route::post('/formEncabezadoAuditoriaCorte', [AuditoriaCorteController::class, 'formEncabezadoAuditoriaCorte'])->name('auditoriaCorte.formEncabezadoAuditoriaCorte')->middleware('checkroleandplant1');
    Route::post('/formAuditoriaMarcada', [AuditoriaCorteController::class, 'formAuditoriaMarcada'])->name('auditoriaCorte.formAuditoriaMarcada');
    Route::post('/formAuditoriaTendido', [AuditoriaCorteController::class, 'formAuditoriaTendido'])->name('auditoriaCorte.formAuditoriaTendido');
    Route::post('/formLectra', [AuditoriaCorteController::class, 'formLectra'])->name('auditoriaCorte.formLectra');
    Route::post('/formAuditoriaBulto', [AuditoriaCorteController::class, 'formAuditoriaBulto'])->name('auditoriaCorte.formAuditoriaBulto');
    Route::post('/formAuditoriaFinal', [AuditoriaCorteController::class, 'formAuditoriaFinal'])->name('auditoriaCorte.formAuditoriaFinal');
    Route::post('/auditoriaCorte/agregarDefecto', [AuditoriaCorteController::class, 'agregarDefecto'])->name('auditoriaCorte.agregarDefecto');

    // actualizacion para corte
    Route::post('/formEncabezadoAuditoriaCorteV2', [AuditoriaCorteController::class, 'formEncabezadoAuditoriaCorteV2'])->name('auditoriaCorte.formEncabezadoAuditoriaCorteV2')->middleware('checkroleandplant1');
    Route::get('/auditoriaCorteV2/{id}/{orden}', [AuditoriaCorteController::class, 'auditoriaCorteV2'])->name('auditoriaCorte.auditoriaCorteV2');
    Route::post('/agregarEventoCorteV2', [AuditoriaCorteController::class, 'agregarEventoCorteV2'])->name('auditoriaCorte.agregarEventoCorteV2')->middleware('checkroleandplant1');
    Route::post('/formAuditoriaMarcadaV2', [AuditoriaCorteController::class, 'formAuditoriaMarcadaV2'])->name('auditoriaCorte.formAuditoriaMarcadaV2');
    Route::post('/formAuditoriaTendidoV2', [AuditoriaCorteController::class, 'formAuditoriaTendidoV2'])->name('auditoriaCorte.formAuditoriaTendidoV2');
    Route::post('/formLectraV2', [AuditoriaCorteController::class, 'formLectraV2'])->name('auditoriaCorte.formLectraV2');
    Route::post('/formAuditoriaBultoV2', [AuditoriaCorteController::class, 'formAuditoriaBultoV2'])->name('auditoriaCorte.formAuditoriaBultoV2');
    Route::post('/formAuditoriaFinalV2', [AuditoriaCorteController::class, 'formAuditoriaFinalV2'])->name('auditoriaCorte.formAuditoriaFinalV2');
    Route::get('/ordenes-corte/buscar', [AuditoriaCorteController::class, 'buscarOrdenCorte'])->name('ordenes-corte.buscar');
    // Rutas para búsqueda AJAX en el acordeón “EN PROCESO”
    Route::get('/auditoria-corte/search-en-proceso', [AuditoriaCorteController::class, 'searchEnProceso'])->name('auditoriaCorte.searchEnProceso');
    // Rutas para búsqueda AJAX en el acordeón “FINAL”
    Route::get('/auditoria-corte/search-final', [AuditoriaCorteController::class, 'searchFinal'])->name('auditoriaCorte.searchFinal');
    Route::get('/auditoria-corte/vista-final', [CorteFinalController::class, 'index'])->name('auditoriaCorte.index');
    Route::get('/auditoria-corte/reporte', [CorteFinalController::class, 'reporte'])->name('auditoriaCorte.reporte');

    //fin aprtado Auditoria Corte

    //Inicio apartado para seccion Evaluacion corte
    Route::get('/inicioEvaluacionCorte', [EvaluacionCorteController::class, 'inicioEvaluacionCorte'])->name('evaluacionCorte.inicioEvaluacionCorte')->middleware('checkroleandplant1');
    Route::post('/formRegistro', [EvaluacionCorteController::class, 'formRegistro'])->name('evaluacionCorte.formRegistro')->middleware('checkroleandplant1');
    Route::post('/formAltaEvaluacionCortes', [EvaluacionCorteController::class, 'formAltaEvaluacionCortes'])->name('evaluacionCorte.formAltaEvaluacionCortes');
    Route::get('/evaluaciondeCorte/{orden}/{evento}', [EvaluacionCorteController::class, 'evaluaciondeCorte'])->name('evaluacionCorte.evaluaciondeCorte')->middleware('checkroleandplant1');
    Route::post('/obtener-estilo', [EvaluacionCorteController::class, 'obtenerEstilo'])->name('evaluacionCorte.obtenerEstilo');
    Route::post('/formFinalizarEventoCorte', [EvaluacionCorteController::class, 'formFinalizarEventoCorte'])->name('evaluacionCorte.formFinalizarEventoCorte');
    Route::post('/formActualizacionEliminacionEvaluacionCorte/{id}', [EvaluacionCorteController::class, 'formActualizacionEliminacionEvaluacionCorte'])->name('evaluacionCorte.formActualizacionEliminacionEvaluacionCorte');
    Route::post('/crearCategoriaParteCorte', [EvaluacionCorteController::class, 'crearCategoriaParteCorte'])->name('evaluacionCorte.crearCategoriaParteCorte')->middleware('checkroleandplant1');

    //Inicio apartado para seccion Auditoria Proceso de Corte
    Route::get('/auditoriaProcesoCorte', [AuditoriaProcesoCorteController::class, 'auditoriaProcesoCorte'])->name('auditoriaProcesoCorte.auditoriaProcesoCorte')->middleware('checkroleandplant1');
    Route::get('/altaProcesoCorte', [AuditoriaProcesoCorteController::class, 'altaProcesoCorte'])->name('auditoriaProcesoCorte.altaProcesoCorte')->middleware('checkroleandplant1');
    Route::post('/formAltaProcesoCorte', [AuditoriaProcesoCorteController::class, 'formAltaProcesoCorte'])->name('auditoriaProcesoCorte.formAltaProcesoCorte');
    Route::post('/formRegistroAuditoriaProcesoCorte', [AuditoriaProcesoCorteController::class, 'formRegistroAuditoriaProcesoCorte'])->name('auditoriaProcesoCorte.formRegistroAuditoriaProcesoCorte');

    //Inicio apartado para seccion Auditoria: proceso, playera, empaque
    Route::get('/auditoriaProceso', [AuditoriaProcesoController::class, 'auditoriaProceso'])->name('aseguramientoCalidad.auditoriaProceso')->middleware('auth');
    //Route::get('/altaProceso', [AuditoriaProcesoController::class, 'altaProceso'])->name('aseguramientoCalidad.altaProceso')->middleware('auth');
    Route::post('/obtenerItemId', [AuditoriaProcesoController::class, 'obtenerItemId'])->name('obtenerItemId');
    Route::post('/obtenerCliente1', [AuditoriaProcesoController::class, 'obtenerCliente1'])->name('obtenerCliente1');
    Route::post('/formAltaProceso', [AuditoriaProcesoController::class, 'formAltaProceso'])->name('aseguramientoCalidad.formAltaProceso');
    Route::post('/formRegistroAuditoriaProceso', [AuditoriaProcesoController::class, 'formRegistroAuditoriaProceso'])->name('aseguramientoCalidad.formRegistroAuditoriaProceso');
    Route::post('/formUpdateDeleteProceso', [AuditoriaProcesoController::class, 'formUpdateDeleteProceso'])->name('aseguramientoCalidad.formUpdateDeleteProceso');
    Route::post('/formFinalizarProceso', [AuditoriaProcesoController::class, 'formFinalizarProceso'])->name('aseguramientoCalidad.formFinalizarProceso');
    Route::get('/modules', [AuditoriaProcesoController::class, 'getModules'])->name('modules.getModules');
    Route::get('/names-by-module', [AuditoriaProcesoController::class, 'getNamesByModule'])->name('modules.getNamesByModule');
    Route::get('/utilities', [AuditoriaProcesoController::class, 'getUtilities'])->name('utilities.getUtilities');
    Route::post('/cambiarEstadoInicioParo', [AuditoriaProcesoController::class, 'cambiarEstadoInicioParo'])->name('aseguramientoCalidad.cambiarEstadoInicioParo');
    Route::post('/categoria-tipo-problema', [AuditoriaProcesoController::class, 'storeCategoriaTipoProblema'])->name('categoria_tipo_problema.store');
    Route::post('/obtenerTodosLosEstilosUnicos', [AuditoriaProcesoController::class, 'obtenerTodosLosEstilosUnicos'])->name('obtenerTodosLosEstilosUnicos');
    Route::get('/obtener-supervisor', [AuditoriaProcesoController::class, 'obtenerSupervisor']);

    //secion de la segunda version de Auditoria Proceso
    Route::get('/altaProceso-v2', [AuditoriaProcesoV2Controller::class, 'altaProcesoV2'])->name('altaProcesoV2');
    Route::get('/obtener-modulos-v2', [AuditoriaProcesoV2Controller::class, 'obtenerModulosV2'])->name('obtenerModulosV2');
    Route::get('/obtener-estilos-v2', [AuditoriaProcesoV2Controller::class, 'obtenerEstilosV2'])->name('obtenerEstilosV2');
    Route::get('/obtener-supervisores-v2', [AuditoriaProcesoV2Controller::class, 'obtenerSupervisorV2'])->name('obtenerSupervisoresV2');
    Route::post('/formAltaProceso-v2', [AuditoriaProcesoV2Controller::class, 'formAltaProcesoV2'])->name('formAltaProcesoV2');
    Route::get('/auditoriaProceso-v2', [AuditoriaProcesoV2Controller::class, 'auditoriaProcesoV2'])->name('aseguramientoCalidad.auditoriaProcesoV2');
    Route::get('/obtenerProcesos-v2', [AuditoriaProcesoV2Controller::class, 'obtenerListaProcesosV2'])->name('obtenerListaProcesosV2');
    Route::get('/obtener-nombres-generales', [AuditoriaProcesoV2Controller::class, 'obtenerNombresGenerales'])->name('obtenerNombresGenerales');
    Route::get('/obtener-operaciones-generales', [AuditoriaProcesoV2Controller::class, 'obtenerOperaciones'])->name('obtenerOperaciones');
    Route::get('/accion-correctiva-proceso', [AuditoriaProcesoV2Controller::class, 'accionCorrectivaProceso'])->name('accionCorrectivaProceso');
    Route::get('/defectos-proceso-v2', [AuditoriaProcesoV2Controller::class, 'defectosProcesoV2'])->name('defectosProcesoV2');
    Route::post('/crear-defectos-proceso-v2', [AuditoriaProcesoV2Controller::class, 'crearDefectoProcesoV2'])->name('crearDefectoProcesoV2');
    Route::post('/formRegistroAuditoriaProceso-v2', [AuditoriaProcesoV2Controller::class, 'formRegistroAuditoriaProcesoV2'])->name('formRegistroAuditoriaProcesoV2');
    Route::get('/obtener-registros-turno-normal-v2', [AuditoriaProcesoV2Controller::class, 'obtenerRegistrosTurnoNormalV2'])->name('obtenerRegistrosTurnoNormalV2');
    Route::get('/obtener-registros-turno-extra-v2', [AuditoriaProcesoV2Controller::class, 'obtenerRegistrosTurnoTiempoExtraV2'])->name('obtenerRegistrosTurnoTiempoExtraV2');
    Route::post('/cambiar-estado-inicio-paro-ajax', [AuditoriaProcesoV2Controller::class, 'cambiarEstadoInicioParoTurnoNormal'])->name('cambiarEstadoInicioParoTurnoNormal');
    Route::post('/eliminar-registro-turno-normal', [AuditoriaProcesoV2Controller::class, 'eliminarRegistroTurnoNormal'])->name('eliminarRegistroTurnoNormal');
    Route::post('/buscar-ultimo-registro-proceso', [AuditoriaProcesoV2Controller::class, 'buscarUltimoRegistroProceso'])->name('buscarUltimoRegistroProceso');
    Route::get('/auditoria-proceso-v2', [AuditoriaProcesoV2Controller::class, 'auditoriaProcesoV2'])->name('auditoriaProcesoV2');
    Route::get('/api/paros-no-finalizados', [AuditoriaProcesoV2Controller::class, 'parosNoFinalizados'])->name('parosNoFinalizados');
    Route::post('/api/finalizar-paro-proceso-despues', [AuditoriaProcesoV2Controller::class, 'finalizarParoProcesodespues'])->name('finalizarParoProcesodespues');
    Route::post('/observacion-proceso-v2', [AuditoriaProcesoV2Controller::class, 'guardarObservacionProceso'])->name('guardarObservacionProceso');
    Route::post('/observacion-proceso-te-v2', [AuditoriaProcesoV2Controller::class, 'guardarObservacionProcesoTE'])->name('guardarObservacionProcesoTE');







    //Inicio apartado para seccion Auditoria AQL
    Route::get('/auditoriaAQL', [AuditoriaAQLController::class, 'auditoriaAQL'])->name('auditoriaAQL.auditoriaAQL')->middleware('auth');
    //Route::get('/altaAQL', [AuditoriaAQLController::class, 'altaAQL'])->name('auditoriaAQL.altaAQL')->middleware('auth');
    Route::post('/obtenerItemIdAQL', [AuditoriaAQLController::class, 'obtenerItemIdAQL'])->name('obtenerItemIdAQL');
    Route::post('/formAltaProcesoAQL', [AuditoriaAQLController::class, 'formAltaProcesoAQL'])->name('auditoriaAQL.formAltaProcesoAQL');
    Route::post('/formRegistroAuditoriaProcesoAQL', [AuditoriaAQLController::class, 'formRegistroAuditoriaProcesoAQL'])->name('auditoriaAQL.formRegistroAuditoriaProcesoAQL');
    Route::post('/formUpdateDeleteProcesoAQL', [AuditoriaAQLController::class, 'formUpdateDeleteProceso'])->name('auditoriaAQL.formUpdateDeleteProceso');
    Route::post('/formFinalizarProcesoAQL', [AuditoriaAQLController::class, 'formFinalizarProceso'])->name('auditoriaAQL.formFinalizarProceso');
    Route::post('/cambiarEstadoInicioParoAQL', [AuditoriaAQLController::class, 'cambiarEstadoInicioParoAQL'])->name('auditoriaAQL.cambiarEstadoInicioParoAQL');
    Route::get('/RechazosParoAQL', [AuditoriaAQLController::class, 'RechazosParoAQL'])->name('auditoriaAQL.RechazosParoAQL');
    Route::post('/cargarOrdenesOP', [AuditoriaAQLController::class, 'metodoNombre'])->name('metodoNombre');
    Route::post('/categoria-tipo-problema-aql', [AuditoriaAQLController::class, 'storeCategoriaTipoProblemaAQL'])->name('categoria_tipo_problema_aql.store');
    Route::get('/get-bultos-by-op', [AuditoriaAQLController::class, 'getBultosByOp'])->name('getBultosByOp');




    //Fin apartado para seccion Evaluacion corte

    // Ruta de Screen Print <---Inicio------>
    Route::get('/ScreenPrint', [CalidadScreenPrintController::class, 'ScreenPrint'])->name('ScreenPlanta2.ScreenPrint');
    Route::get('/Ordenes', [CalidadScreenPrintController::class, 'Ordenes']);
    Route::get('/Clientes/{ordenes}', [CalidadScreenPrintController::class, 'Clientes']);
    Route::get('/Estilo/{ordenes}', [CalidadScreenPrintController::class, 'Estilos']);
    Route::get('/Tecnicos', [CalidadScreenPrintController::class, 'Tecnicos']);
    Route::get('/TipoTecnica', [CalidadScreenPrintController::class, 'TipoTecnica']);
    Route::post('/AgregarTecnica', [CalidadScreenPrintController::class, 'AgregarTecnica']);
    Route::get('/TipoFibra', [CalidadScreenPrintController::class, 'TipoFibra']);
    Route::post('/AgregarFibra', [CalidadScreenPrintController::class, 'AgregarFibra']);
    Route::get('/viewTabl', [CalidadScreenPrintController::class, 'viewTabl']);
    Route::post('/SendScreenPrint', [CalidadScreenPrintController::class, 'SendScreenPrint']);
    Route::put('/UpdateScreenPrint/{idValue}', [CalidadScreenPrintController::class, 'UpdateScreenPrint']);
    Route::get('/obtenerOpcionesACCorrectiva',[CalidadScreenPrintController::class, 'obtenerOpcionesACCorrectiva']);
    Route::get('/obtenerOpcionesTipoProblema', [CalidadScreenPrintController::class, 'obtenerOpcionesTipoProblema']);
    Route::get('/OpcionesACCorrectiva',[CalidadScreenPrintController::class, 'OpcionesACCorrectiva']);
    Route::get('/OpcionesTipoProblema', [CalidadScreenPrintController::class, 'OpcionesTipoProblema']);
    Route::post('/actualizarStatScrin/{id}', [CalidadScreenPrintController::class, 'actualizarStatScrin']);
    Route::get('/horno_banda', [CalidadScreenPrintController::class, 'horno_banda']);
    Route::post('/savedatahorno_banda', [CalidadScreenPrintController::class, 'savedatahorno_banda']);
    Route::get('/PorcenScreen', [CalidadScreenPrintController::class, 'PorcenScreen']);
    ////// <-------Fin de Screen Print-------------->
    // Ruta de Inspeccion Estampado Despues del Horno<-----Inicio------->
    Route::get('/InspecciondHorno', [InspeccionEstampadoHorno::class, 'InsEstamHorno'])->name('ScreenPlanta2.InsEstamHorno');
    Route::get('/Ordenes', [InspeccionEstampadoHorno::class, 'Ordenes']);
    Route::get('/Clientes/{ordenes}', [InspeccionEstampadoHorno::class, 'Clientes']);
    Route::get('/Estilo/{ordenes}', [InspeccionEstampadoHorno::class, 'Estilo']);
    Route::get('/Tecnicos', [InspeccionEstampadoHorno::class, 'Tecnicos']);
    Route::get('/TipoTecnica', [InspeccionEstampadoHorno::class, 'TipoTecnica']);
    Route::post('/AgregarTecnica', [InspeccionEstampadoHorno::class, 'AgregarTecnica']);
    Route::get('/TipoFibra', [InspeccionEstampadoHorno::class, 'TipoFibra']);
    Route::post('/AgregarFibra', [InspeccionEstampadoHorno::class, 'AgregarFibra']);
    Route::get('/viewTableIns', [InspeccionEstampadoHorno::class, 'viewTableIns']);
    Route::post('/SendInspeccionEstampadoHornot', [InspeccionEstampadoHorno::class, 'SendInspeccionEstampadoHornot']);
    Route::put('/UpdateIsnpec/{idValue}', [InspeccionEstampadoHorno::class, 'UpdateIsnpec']);
    Route::get('/obtenerOpcionesACCorrectiva',[InspeccionEstampadoHorno::class, 'obtenerOpcionesACCorrectiva']);
    Route::get('/obtenerOpcionesTipoProblema', [InspeccionEstampadoHorno::class, 'obtenerOpcionesTipoProblema']);
    Route::get('/OpcionesACCorrectiva',[InspeccionEstampadoHorno::class, 'OpcionesACCorrectiva']);
    Route::get('/OpcionesTipoProblema', [InspeccionEstampadoHorno::class, 'OpcionesTipoProblema']);
    Route::post('/actualizarEstado/{id}', [InspeccionEstampadoHorno::class, 'actualizarEstado']);
    Route::get('/PorcenTotalDefec', [InspeccionEstampadoHorno::class, 'PorcenTotalDefec']);
    ////// <-------Fin de Inspeccion Estampado Despues del Horno-------------->
    // Ruta de Calidad Proceso Plancha<-----Inicio------->
    Route::get('/ProcesoPlancha', [CalidadProcesoPlancha::class, 'ProcesoPlancha'])->name('ScreenPlanta2.CalidadProcesoPlancha');
    Route::get('/Ordenes', [CalidadProcesoPlancha::class, 'Ordenes']);
    Route::get('/Clientes/{ordenes}', [CalidadProcesoPlancha::class, 'Clientes']);
    Route::get('/Estilo/{ordenes}', [CalidadProcesoPlancha::class, 'Estilo']);
    Route::get('/Tecnicos', [CalidadProcesoPlancha::class, 'Tecnicos']);
    Route::get('/viewTablePlancha', [CalidadProcesoPlancha::class, 'viewTablePlancha']);
    Route::post('/SendPlancha', [CalidadProcesoPlancha::class, 'SendPlancha']);
    Route::put('/UpdatePlancha/{idValue}', [CalidadProcesoPlancha::class, 'UpdatePlancha']);
    Route::get('/obtenerOpcionesACCorrectiva',[CalidadProcesoPlancha::class, 'obtenerOpcionesACCorrectiva']);
    Route::get('/obtenerOpcionesTipoProblema', [CalidadProcesoPlancha::class, 'obtenerOpcionesTipoProblema']);
    Route::get('/OpcionesACCorrectiva',[CalidadProcesoPlancha::class, 'OpcionesACCorrectiva']);
    Route::get('/OpcionesTipoProblema', [CalidadProcesoPlancha::class, 'OpcionesTipoProblema']);
    Route::post('/actualizarEstado/{id}', [CalidadProcesoPlancha::class, 'actualizarEstado']);
    Route::get('/PorcenTotalDefecPlancha', [CalidadProcesoPlancha::class, 'PorcenTotalDefecPlancha']);
    ////// <-------Fin de Calidad Process Plancha-------------->
    // Ruta de Maquila<-----Inicio------->
    Route::get('/Maquila', [Maquila::class, 'Maquilas'])->name('ScreenPlanta2.Maquila');
    Route::get('/Tecnicos', [Maquila::class, 'Tecnicos']);
    Route::get('/viewTableMaquila', [Maquila::class, 'viewTableMaquila']);
    Route::post('/SendMaquila', [Maquila::class, 'SendMaquila']);
    Route::put('/UpdateMaquila/{idValue}', [Maquila::class, 'UpdateMaquila']);
    Route::get('/obtenerOpcionesACCorrectiva',[Maquila::class, 'obtenerOpcionesACCorrectiva']);
    Route::get('/obtenerOpcionesTipoProblema', [Maquila::class, 'obtenerOpcionesTipoProblema']);
    Route::get('/OpcionesACCorrectiva',[Maquila::class, 'OpcionesACCorrectiva']);
    Route::get('/OpcionesTipoProblema', [Maquila::class, 'OpcionesTipoProblema']);
    Route::post('/actualizarEstado/{id}', [Maquila::class, 'actualizarEstado']);
    Route::get('/PorcenTotalDefecMaquila', [Maquila::class, 'PorcenTotalDefecMaquila']);
    ////// <-------Fin de Maquila-------------->
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']])->middleware('checkrole');
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit'])->middleware('checkrole');
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update'])->middleware('checkrole');
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password'])->middleware('checkrole');
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('/auditoriaEtiquetas',  [DatosAuditoriaEtiquetas::class, 'auditoriaEtiquetas'])->name('formulariosCalidad.auditoriaEtiquetas')->middleware('checkroleandplant1');
    Route::get('/inicioAuditoriaCorte', 'App\Http\Controllers\AuditoriaCorteController@inicioAuditoriaCorte')->name('auditoriaCorte.inicioAuditoriaCorte')->middleware('checkroleandplant1');
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('/ScreenPrint',  [CalidadScreenPrintController::class, 'ScreenPrint'])->name('ScreenPlanta2.ScreenPrint')->middleware('checkroleandplant2');
    Route::get('/InsEstamHorno', [InspeccionEstampadoHorno::class, 'InsEstamHorno'])->name('ScreenPlanta2.InsEstamHorno')->middleware('checkroleandplant2');
    Route::get('/ProcesoPlancha',  [CalidadProcesoPlancha::class, 'ProcesoPlancha'])->name('ScreenPlanta2.CalidadProcesoPlancha')->middleware('checkroleandplant2');
    Route::get('/Maquila',  [Maquila::class, 'Maquilas'])->name('ScreenPlanta2.Maquila')->middleware('checkroleandplant2');
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::view('/error', 'error')->name('error');
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('/buscarEstilos', [DatosAuditoriaEtiquetas::class, 'buscarEstilos']);
    Route::get('/buscarDatosAuditoriaPorEstilo', [DatosAuditoriaEtiquetas::class, 'buscarDatosAuditoriaPorEstilo']);
    Route::get('/obtenerTiposDefectos', [DatosAuditoriaEtiquetas::class, 'obtenerTiposDefectos']);
    Route::put('/actualizarStatus', [DatosAuditoriaEtiquetas::class, 'actualizarStatus']);
    Route::get('/datosinventario', [DatosAuditoriaEtiquetas::class, 'obtenerDatosInventario']);
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Apartado para detalles dashboard
    Route::get('/buscadorDinamico', [DashboardController::class, 'buscadorDinamico'])->name('dashboar.buscadorDinamico');
    Route::get('/buscadorDinamico/search', [DashboardController::class, 'search']);
    Route::get('/dashboarAProcesoPlayera', [DashboardController::class, 'dashboarAProcesoPlayera'])->name('dashboar.dashboarAProcesoPlayera');

    Route::get('/dashboarAProceso', [DashboardController::class, 'dashboarAProceso'])->name('dashboar.dashboarAProceso');
    Route::get('/dashboarAProcesoPlayera', [DashboardController::class, 'dashboarAProcesoPlayera'])->name('dashboar.dashboarAProcesoPlayera');

    Route::get('/dashboarAProcesoAQL', [DashboardController::class, 'dashboarAProcesoAQL'])->name('dashboar.dashboarAProcesoAQL');
    Route::get('/detalleXModulo', [DashboardController::class, 'detalleXModulo'])->name('dashboar.detalleXModulo');

    Route::get('/dashboarAProcesoAQL', [DashboardController::class, 'dashboarAProcesoAQL'])->name('dashboar.dashboarAProcesoAQL');
    Route::get('/detallePorGerente', [DashboardController::class, 'detallePorGerente'])->name('dashboar.detallePorGerente');
    Route::get('/detallePorCliente', [DashboardController::class, 'detallePorCliente'])->name('dashboar.detallePorCliente');
    //dashboard Planta 1
    Route::get('/dashboardPanta1', [DashboardPlanta1Controller::class, 'dashboardPanta1'])->name('dashboar.dashboardPlanta1');
    Route::get('/dashboardPlanta1Detalle', [DashboardPlanta1DetalleController::class, 'dashboardPlanta1Detalle'])->name('dashboar.dashboardPlanta1Detalle');
    Route::get('/detalleXModuloPlanta1', [DashboardPlanta1DetalleController::class, 'detalleXModuloPlanta1'])->name('dashboar.detalleXModuloPlanta1');
    //dashboard Planta 1 consulta por dia
    Route::get('/dashboardPanta1PorDia', [DashboardPlanta1PorDiaController::class, 'dashboardPanta1PorDia'])->name('dashboar.dashboardPanta1PorDia');
    //dashboard Planta 1 consulta por Semana
    Route::get('/dashboardPlanta1PorSemana', [DashboardPlanta1PorDiaController::class, 'dashboardPlanta1PorSemana'])->name('dashboar.dashboardPlanta1PorSemana');
    //dashboard Planta 1 consulta por MES
    Route::get('/dashboardPlanta1PorMes', [DashboardPlanta1PorDiaController::class, 'dashboardPlanta1PorMes'])->name('dashboar.dashboardPlanta1PorMes');
    //dashboard Planta 2
    Route::get('/dashboardPanta2', [DashboardPlanta2Controller::class, 'dashboardPanta2'])->name('dashboar.dashboardPlanta2');
    Route::get('/dashboardPlanta2Detalle', [DashboardPlanta2DetalleController::class, 'dashboardPlanta2Detalle'])->name('dashboar.dashboardPlanta2Detalle');
    Route::get('/detalleXModuloPlanta2', [DashboardPlanta2DetalleController::class, 'detalleXModuloPlanta2'])->name('dashboar.detalleXModuloPlanta2');

    Route::get('/reporteriaInterna',[reporteriaInternaController::class, 'reporteriaInterna'])->name('reporteriaInterna.reporteriaInterna')->middleware('checkrole');
    Route::get('/obtener_top_defectos', [DashboardController::class, 'Top3Defectos']);

    Route::get('/altaYbaja', [AltaYBajaController::class, 'altaYbaja'])->name('altaYbaja');
    Route::patch('/altaYbaja/defecto-proceso/{id}', [AltaYBajaController::class, 'actualizarEstadoDefectoProceso'])->name('actualizarEstadoDefectoProceso');
    Route::patch('/altaYbaja/defecto-playera/{id}', [AltaYBajaController::class, 'actualizarEstadoDefectoPlayera'])->name('actualizarEstadoDefectoPlayera');
    Route::patch('/altaYbaja/defecto-empaque/{id}', [AltaYBajaController::class, 'actualizarEstadoDefectoEmpaque'])->name('actualizarEstadoDefectoEmpaque');
    Route::patch('/altaYbaja/gestion-utility/{id}', [AltaYBajaController::class, 'actualizarEstadoUtility'])->name('actualizarEstadoUtility');
    Route::patch('/altaYbaja/gestion-responsable/{id}', [AltaYBajaController::class, 'actualizarEstadoResponsable'])->name('actualizarEstadoResponsable');
    Route::patch('/altaYbaja/gestion-tecnico/{id}', [AltaYBajaController::class, 'actualizarEstadoTecnico'])->name('actualizarEstadoTecnico');
    Route::post('/altaYbaja/defecto-proceso', [AltaYBajaController::class, 'crearDefectoProceso'])->name('crearDefectoProceso');
    Route::post('/altaYbaja/defecto-playera', [AltaYBajaController::class, 'crearDefectoPlayera'])->name('crearDefectoPlayera');
    Route::post('/altaYbaja/defecto-empaque', [AltaYBajaController::class, 'crearDefectoEmpaque'])->name('crearDefectoEmpaque');
    Route::post('/altaYbaja/gestion-utility', [AltaYBajaController::class, 'crearUtility'])->name('crearUtility');
    Route::post('/altaYbaja/gestion-responsable', [AltaYBajaController::class, 'crearResponsable'])->name('crearResponsable');
    Route::post('/altaYbaja/gestion-tecnico', [AltaYBajaController::class, 'crearTecnico'])->name('crearTecnico');
    Route::post('/actualizarClientesPorcentajes', [AltaYBajaController::class, 'actualizarClientesPorcentajes'])->name('actualizarClientesPorcentajes');
    //ajax para mostrar dato en dashboard principal


    //Segundas
    Route::get('/SegundasTerceras',[HomeController::class, 'SegundasTerceras']);
    Route::get('/Segundas', [Segundas::class, 'Segundas']);
    Route::get('/ObtenerSegundas', [Segundas::class, 'ObtenerSegundas']);
    Route::get('/ObtenerPlantas', [Segundas::class, 'ObtenerPlantas']);
    Route::get('/ObtenerModulos', [Segundas::class, 'ObtenerModulos']);
    Route::get('/ObtenerClientes', [Segundas::class, 'ObtenerClientes']);
    Route::get('/obtenerSegundasFiltradas', [Segundas::class, 'obtenerSegundasFiltradas']);
    //apartado para vistas de nuevas tablas
    Route::get('/dashboardCostosNoCalidad', [DashboardCostosController::class, 'dashboardCostosNoCalidad'])->name('dashboardCostosNoCalidad');

    //dashboard Planta 1 consulta por Semana del comparativo por cliente modulo
    Route::get('/planta1PorSemana', [DashboardComparativoModuloPlanta1Controller::class, 'planta1PorSemana'])->name('dashboarComparativaModulo.planta1PorSemana');
    Route::get('/semanaComparativaGeneral', [DashboardComparativoModuloPlanta1Controller::class, 'semanaComparativaGeneral'])->name('dashboarComparativaModulo.semanaComparativaGeneral');
    Route::get('/data/semana-comparativa-general', [DashboardComparativoModuloPlanta1Controller::class, 'getSemanaComparativaGeneralData'])->name('dashboarComparativaModulo.semanaComparativaGeneralData');
    Route::post('/export-semana-comparativa', [DashboardComparativoModuloPlanta1Controller::class, 'exportSemanaComparativa'])->name('export.semana');

    //seccion para gestion por parte del administrador de calidad
    Route::get('/agregarAqlProceso', [GestionController::class, 'agregarAqlProceso'])->name('gestion.agregarAqlProceso');
    Route::get('/buscarAql', [GestionController::class, 'buscarAql'])->name('gestion.buscarAql');
    Route::post('/guardarAql', [GestionController::class, 'guardarAql'])->name('gestion.guardarAql');
    Route::post('/guardarModuloEstilo', [GestionController::class, 'guardarModuloEstilo'])->name('guardarModuloEstilo');

    //Inicio apartado para seccion Auditoria AQL V2
    Route::get('/auditoriaAQL_v2', [AuditoriaAQL_v2Controller::class, 'auditoriaAQL_v2'])->name('auditoriaAQL.auditoriaAQL_v2')->middleware('auth');
    Route::get('/altaAQL_v2', [AuditoriaAQL_v2Controller::class, 'altaAQL_v2'])->name('auditoriaAQL.altaAQL_v2')->middleware('auth');
    Route::get('/obtener-opciones-op', [AuditoriaAQL_v2Controller::class, 'obtenerOpcionesOP'])->name('obtener.opciones.op');
    Route::get('/obtener-opciones-bulto', [AuditoriaAQL_v2Controller::class, 'obtenerOpcionesBulto'])->name('obtener.opciones.bulto');
    Route::get('/obtener-defectos-aql', [AuditoriaAQL_v2Controller::class, 'obtenerDefectosAQL'])->name('obtener.defectos.aql');
    Route::post('/crear-defectos-aql', [AuditoriaAQL_v2Controller::class, 'crearDefectoAQL'])->name('crear.defecto.aql');
    Route::get('/obtener-nobres-proceso', [AuditoriaAQL_v2Controller::class, 'obtenerNombresProceso'])->name('obtener.nombres.proceso');
    Route::post('/guardar-registros-aql', [AuditoriaAQL_v2Controller::class, 'guardarRegistrosAql'])->name('guardar.registro.aql');
    Route::get('/mostrar-registros-aql-dia', [AuditoriaAQL_v2Controller::class, 'mostrarRegistrosAqlDia'])->name('mostrar.registros.aql.dia');
    Route::get('/mostrar-registros-aql-dia-TE', [AuditoriaAQL_v2Controller::class, 'mostrarRegistrosAqlDiaTE'])->name('mostrar.registros.aql.dia.TE');
    Route::post('/eliminar-registro-aql', [AuditoriaAQL_v2Controller::class, 'eliminarRegistroAql'])->name('eliminar.registro.aql');
    Route::post('/buscar-ultimo-registro', [AuditoriaAQL_v2Controller::class, 'buscarUltimoRegistro'])->name('buscarUltimoRegistro');
    Route::post('/finalizar-paro-aql', [AuditoriaAQL_v2Controller::class, 'finalizarParoAQL'])->name('finalizar.paro.aql');
    Route::get('/auditoriaAQLverificarFinalizacion', [AuditoriaAQL_v2Controller::class, 'verificarFinalizacion'])->name('auditoriaAQL.verificarFinalizacion');
    Route::get('/verificarAQLFinalizacionTE', [AuditoriaAQL_v2Controller::class, 'verificarFinalizacionTE'])->name('auditoriaAQL.verificarFinalizacionTE');







    Route::post('/obtenerItemIdAQL_v2', [AuditoriaAQL_v2Controller::class, 'obtenerItemIdAQL_v2'])->name('obtenerItemIdAQL_v2');
    Route::post('/formAltaProcesoAQL_v2', [AuditoriaAQL_v2Controller::class, 'formAltaProcesoAQL_v2'])->name('auditoriaAQL.formAltaProcesoAQL_v2');
    Route::post('/formRegistroAuditoriaProcesoAQL_v2', [AuditoriaAQL_v2Controller::class, 'formRegistroAuditoriaProcesoAQL_v2'])->name('auditoriaAQL.formRegistroAuditoriaProcesoAQL_v2');
    Route::post('/formUpdateDeleteProcesoAQL_v2', [AuditoriaAQL_v2Controller::class, 'formUpdateDeleteProceso_v2'])->name('auditoriaAQL.formUpdateDeleteProceso_v2');
    Route::post('/formFinalizarProcesoAQL_v2', [AuditoriaAQL_v2Controller::class, 'formFinalizarProceso_v2'])->name('auditoriaAQL.formFinalizarProceso_v2');
    Route::post('/formFinalizarProcesoAQL_v2TE', [AuditoriaAQL_v2Controller::class, 'formFinalizarProceso_v2TE'])->name('auditoriaAQL.formFinalizarProceso_v2TE');
    Route::post('/cambiarEstadoInicioParoAQL_v2', [AuditoriaAQL_v2Controller::class, 'cambiarEstadoInicioParoAQL_v2'])->name('auditoriaAQL.cambiarEstadoInicioParoAQL_v2');
    Route::get('/RechazosParoAQL_v2', [AuditoriaAQL_v2Controller::class, 'RechazosParoAQL_v2'])->name('auditoriaAQL.RechazosParoAQL_v2');
    Route::post('/cargarOrdenesOP_v2', [AuditoriaAQL_v2Controller::class, 'metodoNombre_v2'])->name('metodoNombre_v2');
    Route::post('/categoria-tipo-problema-aql_v2', [AuditoriaAQL_v2Controller::class, 'storeCategoriaTipoProblemaAQL'])->name('categoria_tipo_problema_aql.store_v2');
    Route::get('/get-bultos-by-op_v2', [AuditoriaAQL_v2Controller::class, 'getBultosByOp_v2'])->name('getBultosByOp_v2');
    Route::post('/auditoriaAQL_v2/obtenerAQLenProceso', [AuditoriaAQL_v2Controller::class, 'obtenerAQLenProceso'])->name('auditoriaAQL.obtenerAQLenProceso');
    Route::get('/api/bultos-no-finalizados', [AuditoriaAQL_v2Controller::class, 'bultosNoFinalizados']);
    Route::post('/api/finalizar-paro-aql-despues', [AuditoriaAQL_v2Controller::class, 'finalizarParoAQLdespues']);


    //apartado para las vistas de 
    Route::get('/consultaEstatus', [ConsutlaEstatusController::class, 'consultaEstatus'])->name('consultas.consultaEstatus');


    //nuevo apartado para el desarrollo de etiquetas
    Route::get('/etiquetas_v2', [EtiquetasV2Controller::class, 'etiquetas_v2'])->name('etiquetas_v2');
    Route::post('/procesarFormularioEtiqueta', [EtiquetasV2Controller::class, 'procesarFormularioEtiqueta'])->name('procesarFormularioEtiqueta');
    // Importante: ruta para AJAX (GET) de tallas
    Route::get('/etiquetas_v2/tallas', [EtiquetasV2Controller::class, 'ajaxGetTallas'])->name('ajaxGetTallas');

    // Ruta AJAX para la cantidad y tamaño de muestra
    Route::get('/etiquetas_v2/data', [EtiquetasV2Controller::class, 'ajaxGetData'])->name('ajaxGetData');
    Route::post('/etiquetas_v2/procesar-formulario-ajax', [EtiquetasV2Controller::class, 'procesarFormularioEtiquetaAjax'])->name('etiquetas_v2.procesarAjax');
    Route::get('/obtener-defectos-etiquetas', [EtiquetasV2Controller::class, 'obtenerDefectosEtiquetas'])->name('obtenerDefectosEtiquetas');
    Route::post('/guardar-defecto-etiqueta', [EtiquetasV2Controller::class, 'guardarDefectoEtiqueta'])->name('guardarDefectoEtiqueta');
    Route::post('/guardar-auditoria-etiqueta', [EtiquetasV2Controller::class, 'guardarAuditoriaEtiqueta'])->name('guardarAuditoriaEtiqueta');
    Route::put('/reporte-etiquetas/{id}/update-status', [EtiquetasV2Controller::class, 'updateStatus'])->name('reporte-etiquetas.updateStatus');
    Route::get('/registros-del-dia-etiqueta', [EtiquetasV2Controller::class, 'getRegistrosDelDiaEtiqueta'])->name('registros.del.dia.ajax.etiqueta');
    Route::delete('/reporte-etiquetas/{id}', [EtiquetasV2Controller::class, 'eliminarRegistro'])->name('etiquetas.eliminar');


    //nuevo apartado para el desarrollo de inspeccion depues de horno
    Route::get('/inspeccionEstampadoHorno', [ScreenV2Controller::class, 'inspeccionEstampadoHorno'])->name('inspeccionEstampadoHorno');
    Route::get('/screenV2', [ScreenV2Controller::class, 'screenV2'])->name('screenV2');
    Route::get('/planchaV2', [ScreenV2Controller::class, 'planchaV2'])->name('planchaV2');
    // Rutas para buscar OPs y bultos
    Route::get('/search-ops-screen', [ScreenV2Controller::class, 'searchOpsScreen'])->name('search.ops.screen');
    Route::get('/search-bultos-op-screen', [ScreenV2Controller::class, 'searchBultosByOpScreen'])->name('search.bultos.op.screen');
    // Ruta para obtener detalles de un bulto específico
    Route::get('/get-bulto-details-screen/{id}', [ScreenV2Controller::class, 'getBultoDetailsScreen']);
    Route::get('/categoriaTecnicoScreen', [ScreenV2Controller::class, 'getCategoriaTecnicoScreen']);
    Route::get('/categoriaTipoPanel', [ScreenV2Controller::class, 'getCategoriaTipoPanel']);
    Route::get('/categoriaTipoMaquina', [ScreenV2Controller::class, 'getCategoriaTipoMaquina']);
    Route::get('/tipoTecnicaScreen', [ScreenV2Controller::class, 'getTipoTecnicaScreen']);
    Route::get('/tipoFibraScreen', [ScreenV2Controller::class, 'getTipoFibraScreen']);
    Route::post('/guardarNuevoValor', [ScreenV2Controller::class, 'guardarNuevoValor']);
    Route::get('/defectoScreen', [ScreenV2Controller::class, 'getDefectoScreen']);
    Route::get('/accionCorrectivaScreen', [ScreenV2Controller::class, 'getAccionCorrectivaScreen']);
    Route::get('/defectoPlancha', [ScreenV2Controller::class, 'getDefectoPlancha']);
    Route::get('/accionCorrectivaPlancha', [ScreenV2Controller::class, 'getAccionCorrectivaPlancha']);
    Route::post('/guardarNuevoValorDA', [ScreenV2Controller::class, 'guardarNuevoValorDA']);
    Route::post('/inspeccionEstampadoHorno/store', [ScreenV2Controller::class, 'store'])->name('inspeccionEstampadoHorno.store');
    Route::get('/screenV2/data', [ScreenV2Controller::class, 'getScreenData'])->name('screenV2.data');
    Route::get('/screenV2/strart', [ScreenV2Controller::class, 'getScreenStats'])->name('screenV2.strart');
    Route::get('/planchaV2/data', [ScreenV2Controller::class, 'getPlanchaData'])->name('planchaV2.data');
    Route::get('/planchaV2/strart', [ScreenV2Controller::class, 'getPlanchaStats'])->name('planchaV2.strart');
    Route::get('/bultosPorDia', [ScreenV2Controller::class, 'bultosPorDia'])->name('bultosPorDia');
    Route::post('/formControlHorno', [ScreenV2Controller::class, 'formControlHorno'])->name('formControlHorno');
    Route::delete('/inspeccion/{id}', [ScreenV2Controller::class, 'eliminarBulto'])->name('eliminarBulto');

    //apartado para dashboard con la segunda version
    Route::get('/dashboardPlanta1V2', [DashboardPorDiaV2Controller::class, 'dashboardPlanta1V2'])->name('dashboardPlanta1V2');
    Route::get('/dashboardPlanta1V2/buscarAQL', [DashboardPorDiaV2Controller::class, 'buscarAQL'])->name('dashboardPlanta1V2.buscarAQL');
    Route::get('/dashboardPlanta1V2/buscarAQLTE', [DashboardPorDiaV2Controller::class, 'buscarAQLTE'])->name('dashboardPlanta1V2.buscarAQLTE');
    Route::get('/dashboardPlanta1V2/buscarProceso', [DashboardPorDiaV2Controller::class, 'buscarProceso'])->name('dashboardPlanta1V2.buscarProceso');
    Route::get('/dashboardPlanta1V2/buscarProcesoTE', [DashboardPorDiaV2Controller::class, 'buscarProcesoTE'])->name('dashboardPlanta1V2.buscarProcesoTE');
    Route::get('dashboardPlanta1V2P2/buscarAQL/detalles', [DashboardPorDiaV2Controller::class, 'obtenerDetallesAQLP1']);
    Route::get('dashboardPlanta1V2P2/buscarProceso/detalles', [DashboardPorDiaV2Controller::class, 'obtenerDetallesProcesoP1']);
    Route::get('dashboardPlanta1V2/buscarAQL/detalles', [DashboardPorDiaV2Controller::class, 'obtenerDetallesAQLP1']); 
    Route::get('dashboardPlanta1V2/buscarProceso/detalles', [DashboardPorDiaV2Controller::class, 'obtenerDetallesProcesoP1']);
    Route::get('/dashboardPlanta2V2', [DashboardPorDiaV2Controller::class, 'dashboardPlanta2V2'])->name('dashboardPlanta2V2');
    Route::get('/dashboardPlanta1V2P2/buscarAQL', [DashboardPorDiaV2Controller::class, 'buscarAQLP2'])->name('dashboardPlanta2V2.buscarAQLP2');
    Route::get('/dashboardPlanta1V2P2/buscarAQLTE', [DashboardPorDiaV2Controller::class, 'buscarAQLTEP2'])->name('dashboardPlanta2V2.buscarAQLTEP2');
    Route::get('/dashboardPlanta1V2P2/buscarProceso', [DashboardPorDiaV2Controller::class, 'buscarProcesoP2'])->name('dashboardPlanta2V2.buscarProcesoP2');
    Route::get('/dashboardPlanta1V2P2/buscarProcesoTE', [DashboardPorDiaV2Controller::class, 'buscarProcesoTEP2'])->name('dashboardPlanta2V2.buscarProcesoTEP2');
    Route::get('dashboardPlanta2V2P2/buscarAQL/detalles', [DashboardPorDiaV2Controller::class, 'obtenerDetallesAQLP2']);
    Route::get('dashboardPlanta2V2P2/buscarProceso/detalles', [DashboardPorDiaV2Controller::class, 'obtenerDetallesProcesoP2']);

    //Seccion para buscar por OP 
    Route::get('busqueda_OP', [DashboardBusquedaOPController::class, 'index'])->name('busqueda_OP.index');
    Route::post('busqueda_OP/general', [DashboardBusquedaOPController::class, 'buscar'])->name('busqueda_OP.buscarGeneral');
    Route::post('busqueda_OP/buscar-proceso-relacionado', [DashboardBusquedaOPController::class, 'buscarProcesoRelacionado'])->name('busqueda_OP.buscarProcesoRelacionado');


    //Seccion auditoria KanBan
    Route::get('/kanban', [AuditoriaKanBanController::class, 'index'])->name('kanban.index');
    Route::get('/kanban/reporte', [AuditoriaKanBanController::class, 'reporte'])->name('kanban.reporte');
    Route::get('/kanban/comentarios', [AuditoriaKanBanController::class, 'obtenerComentarios'])->name('kanban.comentarios');
    Route::get('/kanban/opciones', [AuditoriaKanBanController::class, 'getOpciones'])->name('kanban.opciones');
    Route::post('/kanban/comentario/crear', [AuditoriaKanBanController::class, 'crearComentario'])->name('kanban.comentario.crear');
    Route::post('/kanban/guardar', [AuditoriaKanBanController::class, 'guardar'])->name('kanban.guardar');
    Route::post('/kanban/actualizar', [AuditoriaKanBanController::class, 'actualizar'])->name('kanban.actualizar');
    Route::get('/kanban/parciales', [AuditoriaKanBanController::class, 'obtenerParciales'])->name('kanban.parciales');
    Route::post('/kanban/parcial/liberar', [AuditoriaKanBanController::class, 'liberarParcial'])->name('kanban.parcial.liberar');
    Route::get('/kanban/registros-hoy', [AuditoriaKanBanController::class, 'obtenerRegistrosHoy'])->name('kanban.registrosHoy');
    Route::post('/kanban/eliminar', [AuditoriaKanBanController::class, 'eliminar'])->name('kanban.eliminar');

    //Seccion bultos no finalizados
    Route::get('/bnf', [BultosNoFinalizadosController::class, 'index'])->name('bnf.index');
    Route::get('/bnf/bultos-no-finalizados-general', [BultosNoFinalizadosController::class, 'bultosNoFinalizadosGeneral']);
    Route::post('/bnf/finalizar-paro-aql-despues', [BultosNoFinalizadosController::class, 'finalizarParoAQLgeneral']);
    Route::get('/bnf/paros-no-finalizados-general', [BultosNoFinalizadosController::class, 'parosNoFinalizadosGeneral']);
    Route::post('/bnf/finalizar-paro-proceso-despues', [BultosNoFinalizadosController::class, 'finalizarParoProcesodespues']);



});
