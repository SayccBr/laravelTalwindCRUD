<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Usuario;

class Professor extends Usuario
{
    // Permissão específica para professor
    public function getPermissao(): string
    {
        return 'acesso ao conteúdo e correção';
    }
}
