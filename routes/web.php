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
use App\Http\Controllers\reporteriaInternaController;
use App\Http\Controllers\Auth\LoginController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
// Rutas adicionales
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');
// Sobrescribir la ruta de login para usar el método personalizado
Route::post('login', [LoginController::class, 'login'])->name('login');

Route::group(['middleware' => 'auth'], function () {
		Route::get('icons', ['as' => 'pages.icons', 'uses' => 'App\Http\Controllers\PageController@icons']);
		Route::get('maps', ['as' => 'pages.maps', 'uses' => 'App\Http\Controllers\PageController@maps']);
		Route::get('notifications', ['as' => 'pages.notifications', 'uses' => 'App\Http\Controllers\PageController@notifications']);
		Route::get('rtl', ['as' => 'pages.rtl', 'uses' => 'App\Http\Controllers\PageController@rtl']);
		Route::get('tables', ['as' => 'pages.tables', 'uses' => 'App\Http\Controllers\PageController@tables']);
		Route::get('typography', ['as' => 'pages.typography', 'uses' => 'App\Http\Controllers\PageController@typography']);
		Route::get('upgrade', ['as' => 'pages.upgrade', 'uses' => 'App\Http\Controllers\PageController@upgrade']);
		Route::get('table-list', function () {
			return view('pages.table_list');
		})->name('table');
	
		Route::get('typography', function () {
			return view('pages.typography');
		})->name('typography');
	
		Route::get('icons', function () {
			return view('pages.icons');
		})->name('icons');
	
		Route::get('map', function () {
			return view('pages.map');
		})->name('map');
	
		Route::get('notifications', function () {
			return view('pages.notifications');
		})->name('notifications');
	
		Route::get('rtl-support', function () {
			return view('pages.language');
		})->name('language');
	
		Route::get('upgrade', function () {
			return view('pages.upgrade');
		})->name('upgrade');
});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);

});

Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
		Route::get('icons', ['as' => 'pages.icons', 'uses' => 'App\Http\Controllers\PageController@icons']);
		Route::get('maps', ['as' => 'pages.maps', 'uses' => 'App\Http\Controllers\PageController@maps']);
		Route::get('notifications', ['as' => 'pages.notifications', 'uses' => 'App\Http\Controllers\PageController@notifications']);
		Route::get('rtl', ['as' => 'pages.rtl', 'uses' => 'App\Http\Controllers\PageController@rtl']);
		Route::get('tables', ['as' => 'pages.tables', 'uses' => 'App\Http\Controllers\PageController@tables']);
		Route::get('typography', ['as' => 'pages.typography', 'uses' => 'App\Http\Controllers\PageController@typography']);
		Route::get('upgrade', ['as' => 'pages.upgrade', 'uses' => 'App\Http\Controllers\PageController@upgrade']);
});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});

Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
		Route::get('icons', ['as' => 'pages.icons', 'uses' => 'App\Http\Controllers\PageController@icons']);
		Route::get('maps', ['as' => 'pages.maps', 'uses' => 'App\Http\Controllers\PageController@maps']);
		Route::get('notifications', ['as' => 'pages.notifications', 'uses' => 'App\Http\Controllers\PageController@notifications']);
		Route::get('rtl', ['as' => 'pages.rtl', 'uses' => 'App\Http\Controllers\PageController@rtl']);
		Route::get('tables', ['as' => 'pages.tables', 'uses' => 'App\Http\Controllers\PageController@tables']);
		Route::get('typography', ['as' => 'pages.typography', 'uses' => 'App\Http\Controllers\PageController@typography']);
		Route::get('upgrade', ['as' => 'pages.upgrade', 'uses' => 'App\Http\Controllers\PageController@upgrade']);
});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});


Route::get('/tipoAuditorias', [UserManagementController::class, 'tipoAuditorias']);
Route::post('/AddUser', [UserManagementController::class, 'AddUser'])->name('user.AddUser');
Route::get('/puestos', [UserManagementController::class, 'puestos']);
Route::post('/editUser', [UserManagementController::class, 'editUser'])->name('users.editUser');

Route::post('/blockUser/{noEmpleado}', [UserManagementController::class, 'blockUser'])->name('blockUser');
Route::put('/blockUser/{noEmpleado}', [UserManagementController::class, 'blockUser'])->name('blockUser');


Route::get('/listaFormularios', [viewlistaFormularios::class, 'listaFormularios'])->name('viewlistaFormularios');

Route::get('/inicioAuditoriaCorte', [AuditoriaCorteController::class, 'inicioAuditoriaCorte'])->name('auditoriaCorte.inicioAuditoriaCorte');
Route::post('/formAuditoriaCortes', [AuditoriaCorteController::class, 'formAuditoriaCortes'])->name('auditoriaCorte.formAuditoriaCortes');
Route::post('/formRechazoCorte', [AuditoriaCorteController::class, 'formRechazoCorte'])->name('auditoriaCorte.formRechazoCorte');
Route::post('/formAprobarCorte', [AuditoriaCorteController::class, 'formAprobarCorte'])->name('auditoriaCorte.formAprobarCorte');
Route::post('/agregarEventoCorte', [AuditoriaCorteController::class, 'agregarEventoCorte'])->name('auditoriaCorte.agregarEventoCorte');
Route::get('/auditoriaCorte/{id}/{orden}', [AuditoriaCorteController::class, 'auditoriaCorte'])->name('auditoriaCorte.auditoriaCorte');
Route::get('/altaAuditoriaCorte/{orden}', [AuditoriaCorteController::class, 'altaAuditoriaCorte'])->name('auditoriaCorte.altaAuditoriaCorte');
Route::post('/formEncabezadoAuditoriaCorte', [AuditoriaCorteController::class, 'formEncabezadoAuditoriaCorte'])->name('auditoriaCorte.formEncabezadoAuditoriaCorte');
Route::post('/formAuditoriaMarcada', [AuditoriaCorteController::class, 'formAuditoriaMarcada'])->name('auditoriaCorte.formAuditoriaMarcada');
Route::post('/formAuditoriaTendido', [AuditoriaCorteController::class, 'formAuditoriaTendido'])->name('auditoriaCorte.formAuditoriaTendido');
Route::post('/formLectra', [AuditoriaCorteController::class, 'formLectra'])->name('auditoriaCorte.formLectra');
Route::post('/formAuditoriaBulto', [AuditoriaCorteController::class, 'formAuditoriaBulto'])->name('auditoriaCorte.formAuditoriaBulto');
Route::post('/formAuditoriaFinal', [AuditoriaCorteController::class, 'formAuditoriaFinal'])->name('auditoriaCorte.formAuditoriaFinal');
//fin aprtado Auditoria Corte

//Inicio apartado para seccion Evaluacion corte
Route::get('/inicioEvaluacionCorte', [EvaluacionCorteController::class, 'inicioEvaluacionCorte'])->name('evaluacionCorte.inicioEvaluacionCorte');
Route::post('/formRegistro', [EvaluacionCorteController::class, 'formRegistro'])->name('evaluacionCorte.formRegistro');
Route::post('/formAltaEvaluacionCortes', [EvaluacionCorteController::class, 'formAltaEvaluacionCortes'])->name('evaluacionCorte.formAltaEvaluacionCortes');
Route::get('/evaluaciondeCorte/{orden}/{evento}', [EvaluacionCorteController::class, 'evaluaciondeCorte'])->name('evaluacionCorte.evaluaciondeCorte');
Route::post('/obtener-estilo', [EvaluacionCorteController::class, 'obtenerEstilo'])->name('evaluacionCorte.obtenerEstilo');
Route::post('/formFinalizarEventoCorte', [EvaluacionCorteController::class, 'formFinalizarEventoCorte'])->name('evaluacionCorte.formFinalizarEventoCorte');
Route::post('/formActualizacionEliminacionEvaluacionCorte/{id}', [EvaluacionCorteController::class, 'formActualizacionEliminacionEvaluacionCorte'])->name('evaluacionCorte.formActualizacionEliminacionEvaluacionCorte');
Route::post('/crearCategoriaParteCorte', [EvaluacionCorteController::class, 'crearCategoriaParteCorte'])->name('evaluacionCorte.crearCategoriaParteCorte');

//Inicio apartado para seccion Auditoria Proceso de Corte
Route::get('/auditoriaProcesoCorte', [AuditoriaProcesoCorteController::class, 'auditoriaProcesoCorte'])->name('auditoriaProcesoCorte.auditoriaProcesoCorte');
Route::get('/altaProcesoCorte', [AuditoriaProcesoCorteController::class, 'altaProcesoCorte'])->name('auditoriaProcesoCorte.altaProcesoCorte');
Route::post('/formAltaProcesoCorte', [AuditoriaProcesoCorteController::class, 'formAltaProcesoCorte'])->name('auditoriaProcesoCorte.formAltaProcesoCorte');
Route::post('/formRegistroAuditoriaProcesoCorte', [AuditoriaProcesoCorteController::class, 'formRegistroAuditoriaProcesoCorte'])->name('auditoriaProcesoCorte.formRegistroAuditoriaProcesoCorte');

//Inicio apartado para seccion Auditoria: proceso, playera, empaque
Route::get('/auditoriaProceso', [AuditoriaProcesoController::class, 'auditoriaProceso'])->name('aseguramientoCalidad.auditoriaProceso');
Route::get('/altaProceso', [AuditoriaProcesoController::class, 'altaProceso'])->name('aseguramientoCalidad.altaProceso');
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


//Inicio apartado para seccion Auditoria AQL
Route::get('/auditoriaAQL', [AuditoriaAQLController::class, 'auditoriaAQL'])->name('auditoriaAQL.auditoriaAQL');
Route::get('/altaAQL', [AuditoriaAQLController::class, 'altaAQL'])->name('auditoriaAQL.altaAQL');
Route::post('/obtenerItemIdAQL', [AuditoriaAQLController::class, 'obtenerItemIdAQL'])->name('obtenerItemIdAQL');
Route::post('/formAltaProcesoAQL', [AuditoriaAQLController::class, 'formAltaProcesoAQL'])->name('auditoriaAQL.formAltaProcesoAQL');
Route::post('/formRegistroAuditoriaProcesoAQL', [AuditoriaAQLController::class, 'formRegistroAuditoriaProcesoAQL'])->name('auditoriaAQL.formRegistroAuditoriaProcesoAQL');
Route::post('/formUpdateDeleteProcesoAQL', [AuditoriaAQLController::class, 'formUpdateDeleteProceso'])->name('auditoriaAQL.formUpdateDeleteProceso');
Route::post('/formFinalizarProcesoAQL', [AuditoriaAQLController::class, 'formFinalizarProceso'])->name('auditoriaAQL.formFinalizarProceso');
Route::post('/cambiarEstadoInicioParoAQL', [AuditoriaAQLController::class, 'cambiarEstadoInicioParoAQL'])->name('auditoriaAQL.cambiarEstadoInicioParoAQL');
Route::get('/RechazosParoAQL', [AuditoriaAQLController::class, 'RechazosParoAQL'])->name('auditoriaAQL.RechazosParoAQL');


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
Route::get('/NoOrdenes', [DatosAuditoriaEtiquetas::class, 'NoOrdenes']);
Route::get('/NoOP', [DatosAuditoriaEtiquetas::class, 'NoOP']);
Route::get('/NoPO', [DatosAuditoriaEtiquetas::class, 'NoPO']);
Route::get('/NoOV', [DatosAuditoriaEtiquetas::class, 'NoOV']);
Route::get('/buscarEstilos', [DatosAuditoriaEtiquetas::class, 'buscarEstilos']);
Route::get('/buscarDatosAuditoriaPorEstilo', [DatosAuditoriaEtiquetas::class, 'buscarDatosAuditoriaPorEstilo']);
Route::get('/obtenerTiposDefectos', [DatosAuditoriaEtiquetas::class, 'obtenerTiposDefectos']);
Route::post('/guardarInformacion', [DatosAuditoriaEtiquetas::class, 'guardarInformacion']);
Route::put('/actualizarStatus', [DatosAuditoriaEtiquetas::class, 'actualizarStatus']);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Apartado para detalles dashboard
Route::get('/dashboarAProceso', [DashboardController::class, 'dashboarAProceso'])->name('dashboar.dashboarAProceso');
Route::get('/dashboarAProcesoPlayera', [DashboardController::class, 'dashboarAProcesoPlayera'])->name('dashboar.dashboarAProcesoPlayera');

Route::get('/dashboarAProceso', [DashboardController::class, 'dashboarAProceso'])->name('dashboar.dashboarAProceso');
Route::get('/dashboarAProcesoPlayera', [DashboardController::class, 'dashboarAProcesoPlayera'])->name('dashboar.dashboarAProcesoPlayera');

Route::get('/dashboarAProcesoAQL', [DashboardController::class, 'dashboarAProcesoAQL'])->name('dashboar.dashboarAProcesoAQL');
Route::get('/detalleXModuloAQL', [DashboardController::class, 'detalleXModuloAQL'])->name('dashboar.detalleXModuloAQL');

Route::get('/dashboarAProcesoAQL', [DashboardController::class, 'dashboarAProcesoAQL'])->name('dashboar.dashboarAProcesoAQL');
Route::get('/detalleXModuloAQL', [DashboardController::class, 'detalleXModuloAQL'])->name('dashboar.detalleXModuloAQL');
Route::get('/detallePorGerente', [DashboardController::class, 'detallePorGerente'])->name('dashboar.detallePorGerente');
Route::get('/detallePorCliente', [DashboardController::class, 'detallePorCliente'])->name('dashboar.detallePorCliente');

Route::get('/reporteriaInterna',[reporteriaInternaController::class, 'reporteriaInterna'])->name('reporteriaInterna.reporteriaInterna')->middleware('checkrole');
