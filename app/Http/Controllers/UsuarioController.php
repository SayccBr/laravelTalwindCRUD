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
    public function index()
    {
        $usuarios = DB::table('usuarios')->get(); // uso direto para polimorfismo depois
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

        match ($data['tipo']) {
            'aluno' => Aluno::create($data),
            'professor' => Professor::create($data),
            'administrador' => Administrador::create($data),
        };

        return redirect()->route('usuarios.index');
    }

    public function destroy($id)
    {
        DB::table('usuarios')->where('id', $id)->delete();
        return redirect()->route('usuarios.index');
    }

    public function edit($id)
{
    $usuario = DB::table('usuarios')->where('id', $id)->first();
    return view('usuarios.edit', compact('usuario'));
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
        unset($data['senha']); // não atualizar se for vazio
    }

    DB::table('usuarios')->where('id', $id)->update($data);

    return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso.');
}

}
