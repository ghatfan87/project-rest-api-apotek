<?php

use App\Http\Controllers\ApotekController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/apotek', [ApotekController::class, 'index']);
Route::post('/apoteks/tambah-data', [ApotekController::class, 'store']);
Route::get('/generate-token',[ApotekController::class, 'createToken']);
Route::get('/apoteks/{id}', [ApotekController::class, 'show']);
Route::patch('/apoteks/{id}/update', [ApotekController::class, 'update']);
Route::delete('/apoteks/{id}/delete', [ApotekController::class,'destroy']);
Route::get('/apoteks/restore/{id}',[ApotekController::class,'restore'])->name('restore');
Route::get('/apoteks/delete/permanent/{id}',[ApotekController::class,'deletePermanent'])->name('permanent');
Route::get('/apoteks/show/trash',[ApotekController::class,'onlyTrash'])->name('onlyTrash');
