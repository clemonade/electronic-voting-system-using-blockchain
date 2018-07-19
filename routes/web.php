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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/registervoter', 'RegisteredVoterController@index')
    ->name('registervoter.index');
Route::post('/registervoter/store', 'RegisteredVoterController@store')
    ->name('registervoter.store');

//Route::resource('/registervoter', 'RegisteredVoterController', ['except' => [
//    'edit',
//    'update',
//    'destroy'
//]]);
