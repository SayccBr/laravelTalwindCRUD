<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aluno;
use App\Models\Professor;
use App\Models\Administrador;
use Illuminate\Routing\Controller;

use App\Models\Usuario;

use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
    $usuarios = DB::table('usuarios')->get();
    if ($request->expectsJson()) {
        return response()->json($usuarios);
    }
    return view('usuarios.index', compact('usuarios'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required',
            'email' => 'required|email|unique:usuarios,email',
            'senha' => 'required',
            'tipo' => 'required|in:aluno,professor,administrador',
            'curso' => 'nullable',
            'departamento' => 'nullable',
        ]);

        $data['senha'] = bcrypt($data['senha']);

        $usuario = match ($data['tipo']) {
            'aluno' => Aluno::create($data),
            'professor' => Professor::create($data),
            'administrador' => Administrador::create($data),
        };

        return response()->json(['mensagem' => 'Usuário cadastrado com sucesso!', 'usuario' => $usuario]);
    }

    public function destroy($id)
    {
        DB::table('usuarios')->where('id', $id)->delete();
        return response()->json(['mensagem' => 'Usuário excluído com sucesso.']);
    }

    public function edit($id)
    {
        $usuario = DB::table('usuarios')->where('id', $id)->first();
        return response()->json($usuario);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nome' => 'required',
            'email' => 'required|email|unique:usuarios,email,' . $id,
            'senha' => 'nullable',
            'tipo' => 'required|in:aluno,professor,administrador',
            'curso' => 'nullable',
            'departamento' => 'nullable',
        ]);

        if (!empty($data['senha'])) {
            $data['senha'] = bcrypt($data['senha']);
        } else {
            unset($data['senha']);
        }

        DB::table('usuarios')->where('id', $id)->update($data);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Usuário atualizado com sucesso']);
}
    }

    public function lista()
    {
        $usuarios = DB::table('usuarios')->get();
        return response()->json($usuarios);
    }
}
