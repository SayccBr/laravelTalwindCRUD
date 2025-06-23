<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Usuario;

class Aluno extends Usuario
{
    // Implementa o método abstrato para retornar a permissão do aluno
    public function getPermissao(): string
    {
        return 'acesso limitado ao conteúdo';
    }
}
