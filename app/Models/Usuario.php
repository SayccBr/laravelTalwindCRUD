<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class Usuario extends Model
{
    protected $table = 'usuarios';  // <--- define tabela única aqui
    protected $fillable = ['nome', 'email', 'senha', 'tipo', 'curso', 'departamento'];

    abstract public function getPermissao(): string;
}


#Eloquent (ORM do Laravel).