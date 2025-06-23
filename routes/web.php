<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

// Rota raiz apenas para teste
Route::get('/', function () {
    return 'Funcionando!';
});

// Rota para listar usuários (GET /usuarios)
Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');

// Rota para criar usuário (POST /usuarios)
Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');

// Rota para buscar usuário específico para edição (GET /usuarios/{id}/edit)
Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');

// Rota para atualizar usuário (PUT /usuarios/{id})
Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');

// Rota para deletar usuário (DELETE /usuarios/{id})
Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
