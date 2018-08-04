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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', 'ConstituencyController@voter')
    ->name('constituencies');
//Route::get('/constituency/{id}', 'ConstituencyController@show')
//    ->name('constituency.show');
Route::get('/constituency/{federal}/{state?}', 'ConstituencyController@show')
    ->name('constituency.show');

Route::get('/voter', 'VoterController@index')
    ->name('voter.index');

Route::get('/admin', 'ConstituencyController@login')
    ->name('admin.login');
Route::get('/admin/dashboard', 'ConstituencyController@index')
    ->name('admin.index');

Route::get('/admin/registervoter', 'VoterController@create')
    ->name('registervoter.create');
Route::post('/admin/registervoter/store', 'VoterController@store')
    ->name('registervoter.store');

Route::get('/admin/party', 'PartyController@index')
    ->name('party.index');
Route::get('/admin/party/register', 'PartyController@create')
    ->name('party.create');
Route::post('/admin/party/store', 'PartyController@store')
    ->name('party.store');

Route::get('/admin/registercandidate', 'CandidateController@create')
    ->name('registercandidate.create');

Route::get('/admin/verify/{federal}/{state?}', 'VoterController@verify')
    ->name('admin.verify');
Route::post('/admin/prevote', 'VoterController@prevote')
    ->name('admin.prevote');
Route::post('/admin/vote', 'VoterController@vote')
    ->name('admin.vote');
Route::post('/admin/postvote', 'VoterController@postvote')
    ->name('admin.postvote');
