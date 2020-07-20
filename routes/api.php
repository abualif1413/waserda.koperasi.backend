<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'Api\MemberController@login');

Route::get('/user/getByToken/{token}', 'Api\MemberController@getUserByToken')->middleware('waserdaAuth');

Route::get('/coa/getcoa', 'Api\COAController@getCOA')->middleware('waserdaAuth');
Route::get('/coa/getkelompokcoa', 'Api\COAController@getKelompokCOA')->middleware('waserdaAuth');
Route::post('/coa/addcoa', 'Api\COAController@addCOA')->middleware('waserdaAuth');
Route::get('/coa/find/{idCOA}', 'Api\COAController@findCOA')->middleware('waserdaAuth');
Route::get('/coa/deletecoa/{idCOA}', 'Api\COAController@deleteCOA')->middleware('waserdaAuth');

Route::post('/penerimaankas/addrincian', 'Api\PenerimaanKasController@addRincian')->middleware('waserdaAuth');
Route::get('/penerimaankas/rincianlist/{id_penerimaan_kas}', 'Api\PenerimaanKasController@rincianList')->middleware('waserdaAuth');
Route::get('/penerimaankas/findrincian/{id}', 'Api\PenerimaanKasController@findRincian')->middleware('waserdaAuth');
Route::get('/penerimaankas/deleterincian/{id}', 'Api\PenerimaanKasController@deleteRincian')->middleware('waserdaAuth');
Route::post('/penerimaankas/addheader', 'Api\PenerimaanKasController@addHeader')->middleware('waserdaAuth');
Route::get('/penerimaankas/headerlist', 'Api\PenerimaanKasController@headerList')->middleware('waserdaAuth');
Route::get('/penerimaankas/findheader/{id}', 'Api\PenerimaanKasController@findHeader')->middleware('waserdaAuth');
Route::get('/penerimaankas/deleteheader/{id}', 'Api\PenerimaanKasController@deleteHeader')->middleware('waserdaAuth');

Route::post('/pengeluarankas/addrincian', 'Api\PengeluaranKasController@addRincian')->middleware('waserdaAuth');
Route::get('/pengeluarankas/rincianlist/{id_pengeluaran_kas}', 'Api\PengeluaranKasController@rincianList')->middleware('waserdaAuth');
Route::get('/pengeluarankas/findrincian/{id}', 'Api\PengeluaranKasController@findRincian')->middleware('waserdaAuth');
Route::get('/pengeluarankas/deleterincian/{id}', 'Api\PengeluaranKasController@deleteRincian')->middleware('waserdaAuth');
Route::post('/pengeluarankas/addheader', 'Api\PengeluaranKasController@addHeader')->middleware('waserdaAuth');
Route::get('/pengeluarankas/headerlist', 'Api\PengeluaranKasController@headerList')->middleware('waserdaAuth');
Route::get('/pengeluarankas/findheader/{id}', 'Api\PengeluaranKasController@findHeader')->middleware('waserdaAuth');
Route::get('/pengeluarankas/deleteheader/{id}', 'Api\PengeluaranKasController@deleteHeader')->middleware('waserdaAuth');
