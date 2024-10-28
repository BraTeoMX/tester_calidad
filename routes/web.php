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
use App\Http\Controllers\AuditoriaAQLController;
use App\Http\Controllers\Maquila;
use App\Http\Controllers\viewlistaFormularios;
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
    return view('login');
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

    // Añade aquí el resto de tus rutas protegidas
});




Route::get('/tipoAuditorias', [UserManagementController::class, 'tipoAuditorias']);
Route::post('/AddUser', [UserManagementController::class, 'AddUser'])->name('user.AddUser');
Route::get('/puestos', [UserManagementController::class, 'puestos']);
Route::post('/editUser', [UserManagementController::class, 'editUser'])->name('users.editUser');

Route::post('/blockUser/{noEmpleado}', [UserManagementController::class, 'blockUser'])->name('blockUser');
Route::put('/blockUser/{noEmpleado}', [UserManagementController::class, 'blockUser'])->name('blockUser');


Route::get('/listaFormularios', [viewlistaFormularios::class, 'listaFormularios'])->name('viewlistaFormularios')->middleware('auth');

Route::get('/inicioAuditoriaCorte', [AuditoriaCorteController::class, 'inicioAuditoriaCorte'])->name('auditoriaCorte.inicioAuditoriaCorte')->middleware('checkroleandplant1');
Route::post('/formAuditoriaCortes', [AuditoriaCorteController::class, 'formAuditoriaCortes'])->name('auditoriaCorte.formAuditoriaCortes');
Route::post('/formRechazoCorte', [AuditoriaCorteController::class, 'formRechazoCorte'])->name('auditoriaCorte.formRechazoCorte');
Route::post('/formAprobarCorte', [AuditoriaCorteController::class, 'formAprobarCorte'])->name('auditoriaCorte.formAprobarCorte');
Route::post('/agregarEventoCorte', [AuditoriaCorteController::class, 'agregarEventoCorte'])->name('auditoriaCorte.agregarEventoCorte')->middleware('checkroleandplant1');
Route::get('/auditoriaCorte/{id}/{orden}', [AuditoriaCorteController::class, 'auditoriaCorte'])->name('auditoriaCorte.auditoriaCorte')->middleware('checkroleandplant1');
Route::get('/altaAuditoriaCorte/{orden}', [AuditoriaCorteController::class, 'altaAuditoriaCorte'])->name('auditoriaCorte.altaAuditoriaCorte')->middleware('checkroleandplant1');
Route::post('/formEncabezadoAuditoriaCorte', [AuditoriaCorteController::class, 'formEncabezadoAuditoriaCorte'])->name('auditoriaCorte.formEncabezadoAuditoriaCorte')->middleware('checkroleandplant1');
Route::post('/formAuditoriaMarcada', [AuditoriaCorteController::class, 'formAuditoriaMarcada'])->name('auditoriaCorte.formAuditoriaMarcada');
Route::post('/formAuditoriaTendido', [AuditoriaCorteController::class, 'formAuditoriaTendido'])->name('auditoriaCorte.formAuditoriaTendido');
Route::post('/formLectra', [AuditoriaCorteController::class, 'formLectra'])->name('auditoriaCorte.formLectra');
Route::post('/formAuditoriaBulto', [AuditoriaCorteController::class, 'formAuditoriaBulto'])->name('auditoriaCorte.formAuditoriaBulto');
Route::post('/formAuditoriaFinal', [AuditoriaCorteController::class, 'formAuditoriaFinal'])->name('auditoriaCorte.formAuditoriaFinal');
Route::post('/auditoriaCorte/agregarDefecto', [AuditoriaCorteController::class, 'agregarDefecto'])->name('auditoriaCorte.agregarDefecto');

// actualizacion para corte
Route::post('/formEncabezadoAuditoriaCorteV2', [AuditoriaCorteController::class, 'formEncabezadoAuditoriaCorteV2'])->name('auditoriaCorte.formEncabezadoAuditoriaCorteV2')->middleware('checkroleandplant1');
Route::get('/auditoriaCorteV2/{id}/{orden}', [AuditoriaCorteController::class, 'auditoriaCorteV2'])->name('auditoriaCorte.auditoriaCorteV2')->middleware('checkroleandplant1');
Route::post('/agregarEventoCorteV2', [AuditoriaCorteController::class, 'agregarEventoCorteV2'])->name('auditoriaCorte.agregarEventoCorteV2')->middleware('checkroleandplant1');
Route::post('/formAuditoriaMarcadaV2', [AuditoriaCorteController::class, 'formAuditoriaMarcadaV2'])->name('auditoriaCorte.formAuditoriaMarcadaV2');
Route::post('/formAuditoriaTendidoV2', [AuditoriaCorteController::class, 'formAuditoriaTendidoV2'])->name('auditoriaCorte.formAuditoriaTendidoV2');
Route::post('/formLectraV2', [AuditoriaCorteController::class, 'formLectraV2'])->name('auditoriaCorte.formLectraV2');
Route::post('/formAuditoriaBultoV2', [AuditoriaCorteController::class, 'formAuditoriaBultoV2'])->name('auditoriaCorte.formAuditoriaBultoV2');
Route::post('/formAuditoriaFinalV2', [AuditoriaCorteController::class, 'formAuditoriaFinalV2'])->name('auditoriaCorte.formAuditoriaFinalV2');
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
Route::get('/altaProceso', [AuditoriaProcesoController::class, 'altaProceso'])->name('aseguramientoCalidad.altaProceso')->middleware('auth');
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




//Inicio apartado para seccion Auditoria AQL
Route::get('/auditoriaAQL', [AuditoriaAQLController::class, 'auditoriaAQL'])->name('auditoriaAQL.auditoriaAQL')->middleware('auth');
Route::get('/altaAQL', [AuditoriaAQLController::class, 'altaAQL'])->name('auditoriaAQL.altaAQL')->middleware('auth');
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
//Segundas
Route::get('/SegundasTerceras',[HomeController::class, 'SegundasTerceras']);
Route::get('/redireccionar', [redireccionar::class, 'redireccionar']);
Route::get('/Segundas', [Segundas::class, 'Segundas']);
Route::get('/Terceras', [Terceras::class, 'Terceras']);

//apartado para vistas de nuevas tablas 
Route::get('/dashboardCostosNoCalidad', [DashboardCostosController::class, 'dashboardCostosNoCalidad'])->name('dashboardCostosNoCalidad');