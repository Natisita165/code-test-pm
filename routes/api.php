<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RecordController;


Route::get('/records', [RecordController::class, 'index']); // Para obtener todos los registros
Route::post('/records', [RecordController::class, 'store']); // Para crear un registro
Route::put('/records/{uuid}', [RecordController::class, 'update']); // Para actualizar un registro
Route::delete('/records/{uuid}', [RecordController::class, 'destroy']); // Para eliminar un registro

Route::get('/prueba', fn () => ['mensaje' => 'Sí funciona la API']);