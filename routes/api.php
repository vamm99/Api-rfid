<?php

use App\Http\Controllers\RFIDTagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



Route::prefix('users')->group(function () {
    Route::post('/tag', [RFIDTagController::class, 'store']);

    // CRUD Básico
    Route::get('/fecthUser', [UserController::class, 'index']); // Listar todos los usuarios
    Route::post('/register', [UserController::class, 'store']); // Crear un nuevo usuario
    Route::get('/show/{id}', [UserController::class, 'show']); // Mostrar un usuario específico
    Route::put('/update/{cc}', [UserController::class, 'update']); // Actualizar un usuario específico
    Route::delete('/delete/{id}', [UserController::class, 'destroy']); // Eliminar un usuario específico

    // Métodos de Filtrado
    Route::get('/filterByNombre', [UserController::class, 'filterByNombre']); // Filtrar usuarios por nombre
    Route::get('/filterByCC', [UserController::class, 'filterByCC']); // Filtrar usuario por cc
    Route::get('/filterByUID', [UserController::class, 'filterByUID']); // Filtrar usuario por uid

    // Actualizar saldo acumulado
    Route::put('/{id}/saldo', [UserController::class, 'updateSaldoAcumulado']); // Actualizar saldo acumulado
    Route::get('/users/search', [UserController::class, 'searchByUIDOrCC']);
});
