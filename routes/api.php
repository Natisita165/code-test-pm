<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RecordController;


Route::get('/records', [RecordController::class, 'index']); 
Route::post('/records', [RecordController::class, 'store']);
Route::put('/records/{uuid}', [RecordController::class, 'update']);
Route::delete('/records/{uuid}', [RecordController::class, 'destroy']);

Route::get('/prueba', fn () => ['mensaje' => 'Sí funciona la API']);