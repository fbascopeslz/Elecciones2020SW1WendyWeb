<?php

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

//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');



Route::group(['middleware' => ['guest']], function(){
    Route::get('/', 'Auth\LoginController@showLoginForm');

    //login con el metodo POST es para el formulario de logeo de usuario
    Route::post('login', 'Auth\LoginController@login');
    
    //login con el metodo GET es para cuando caduca el tiempo de session de usuario y redirige a esta ruta
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
}); //Rutas para invitado



Route::group(['middleware' => ['auth']], function(){ //Rutas para usuario autenticado
    
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main'); //Alias de la ruta
    

    Route::get('/actaVotos', 'ActaVotosController@index');

    Route::get('/usuario', 'UsuarioController@index');
    Route::post('/usuario/registrar', 'UsuarioController@store');
    Route::post('/usuario/actualizar', 'UsuarioController@update');
    Route::put('/usuario/desactivar', 'UsuarioController@desactivar');
    Route::put('/usuario/activar', 'UsuarioController@activar');

    Route::get('/rol/selectRol', 'UsuarioController@selectRol');

    //Reporte de resultados
    Route::get('/listarResultadosNacionales', 'ReportesController@listarResultadosNacionales');
    Route::get('/listarResultadosDepartamentales', 'ReportesController@listarResultadosDepartamentales');
    Route::get('/listarResultadosRecintos', 'ReportesController@listarResultadosRecintos');

    Route::get('/getDepartamentos', 'ReportesController@getDepartamentos');
    Route::get('/getRecintos', 'ReportesController@getRecintos');

    //Graficas
    Route::get('/listarResultadosNacionalesGraficas', 'GraficasController@listarResultadosNacionalesGraficas');    
        

});
