<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Usuario;

class Administrador extends Usuario
{
    // Permissão para o administrador (total acesso)
    public function getPermissao(): string
    {
        return 'acesso completo e gerenciamento';
    }
}
