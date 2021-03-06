<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;

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

Route::get('/', [PropertyController::class, 'index']);
 
Route::get('/search', [PropertyController::class, 'getProperties']);

Route::get('/delete', [PropertyController::class, 'delete']);

Route::get('/add-edit', [PropertyController::class, 'addEdit']);

Route::post('/save/{id}', [PropertyController::class, 'save']);

