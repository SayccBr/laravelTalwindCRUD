<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class Usuario extends Model
{
    // Define que esta model usa a tabela 'usuarios' do banco de dados
    protected $table = 'usuarios';

    // Campos que podem ser preenchidos em massa (mass assignment)
    protected $fillable = ['nome', 'email', 'senha', 'tipo', 'curso', 'departamento'];

    // Método abstrato que obriga as classes filhas a implementarem
    // o método getPermissao que retorna a permissão do usuário
    abstract public function getPermissao(): string;
}


#Eloquent (ORM do Laravel).
#Single Table Inheritance (STI)