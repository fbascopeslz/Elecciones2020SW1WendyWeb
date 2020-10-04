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
    Route::post('/usuario/registrar', 'UserController@store');
    Route::post('/usuario/actualizar', 'UserController@update');
    Route::put('/usuario/desactivar', 'UserController@desactivar');
    Route::put('/usuario/activar', 'UserController@activar');
    

    //Reporte de resultados
    Route::get('/listarResultadosNacionales', 'ReportesController@listarResultadosNacionales');
    Route::get('/listarResultadosDepartamentales', 'ReportesController@listarResultadosDepartamentales');

    Route::get('/getDepartamentos', 'ReportesController@getDepartamentos');
        

});
