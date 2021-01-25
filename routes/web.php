<?php
use Illuminate\Support\Facades\Route;
//use Illuminate\Support\Facades\Auth;
use App\Models\Categoria;
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
Route::get('/','IncioControler@index');
Route::get('tablero','IncioControler@tablero');
Route::get('salir'        , 'AutenticarControler@salir');
Route::get('autenticar'        , 'AutenticarControler@autenticar');
Route::get('registrar'        , 'AutenticarControler@registrar');
Route::post('agregar'        , 'AutenticarControler@agregar');
Route::post('validar'        , 'AutenticarControler@validar');
Route::get('listar_por_categoria/{categoria_id}','ExploracionControler@listar_por');
Route::post('busqueda','ExploracionControler@busqueda');
Route::get('Categorias','CategoriasControler@index');
Route::post('Categorias','CategoriasControler@store');
Route::get('Categorias/create','CategoriasControler@create');
Route::get('Categorias/{categoria}','CategoriasControler@show');
Route::put('Categorias/{categoria}','CategoriasControler@update');
Route::delete('Categorias/{categoria}','CategoriasControler@destroy');
Route::get('Categorias/{categoria}/edit','CategoriasControler@edit');
Route::resource('Usuarios','UsuariosControler');
Route::resource('Productos','ProductosControler');
Route::resource('Revisiones', 'RevisarControler', [
    'only' => ['index', 'show', 'update']
]);
Route::resource('Preguntas', 'PreguntasControler', [
    'except' => [ 'create' ]
]);
Route::get('Preguntar/{producto}','PreguntasControler@create');

//  rutas para llamar por ajax
Route::put('_Usuarios/{id}','AjaxControler@updateUsuario');
Route::post('_Categorias','AjaxControler@storeCategoria');
Route::delete('_Categorias/{id}','AjaxControler@destroyCategoria');
Route::post('producto{producto}/image', 'ProductosControler@image')->name('producto.image');
Route::get('producto/image-download/{image}', 'ProductosControler@imageDownload')->name('producto.imageDownload');
Route::delete('producto/image-delete/{image}', 'ProductosControler@imagedelete')->name('producto.imagedelete');
Route::get('comprar/{producto}','ProductosControler@comprar')->name('comprar');
Route::post('dashboard/producto/rechazado','dashboard\ProductController@rechazado')->name('comentrechazado');
Route::post('dashboard/venta/cambiarestado/{estad}','ProductosControler@cambiarestado');
Route::post('check','ProductosControler@check')->name('check');

Route::get('pagos/usuario/{id}','ProductosControler@pagos')->name('pagousuario');


//cambios