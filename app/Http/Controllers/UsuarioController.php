<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Para receber requisições HTTP
use App\Models\Aluno;        // Model Aluno (herda de Usuario)
use App\Models\Professor;    // Model Professor
use App\Models\Administrador; // Model Administrador
use Illuminate\Routing\Controller; // Controller base do Laravel

use App\Models\Usuario;      // Model base Usuario (não está sendo usado diretamente aqui)

use Illuminate\Support\Facades\DB; // Facade para usar query builder do Laravel (acesso direto ao BD)

class UsuarioController extends Controller
{
    // Método para listar usuários
    public function index(Request $request)
    {
        // Busca todos usuários da tabela 'usuarios'
        $usuarios = DB::table('usuarios')->get();

        // Se a requisição esperar JSON (ex: AJAX), retorna JSON
        if ($request->expectsJson()) {
            return response()->json($usuarios);
        }

        // Caso contrário, retorna a view normal (renderizada no servidor)
        return view('usuarios.index', compact('usuarios'));
    }


    // Método para cadastrar um usuário
    public function store(Request $request)
    {
        // Validação dos dados recebidos no request
        $data = $request->validate([
            'nome' => 'required',
            'email' => 'required|email|unique:usuarios,email', // email único
            'senha' => 'required',
            'tipo' => 'required|in:aluno,professor,administrador', // só esses tipos válidos
            'curso' => 'nullable', // curso pode ser vazio (para alunos)
            'departamento' => 'nullable', // departamento pode ser vazio (para professores)
        ]);

        // Encripta a senha antes de salvar
        $data['senha'] = bcrypt($data['senha']);

        // Cria o usuário conforme o tipo usando o match expression PHP 8
        $usuario = match ($data['tipo']) {
            'aluno' => Aluno::create($data),
            'professor' => Professor::create($data),
            'administrador' => Administrador::create($data),
        };

        // Retorna resposta JSON confirmando sucesso e com o usuário criado
        return response()->json(['mensagem' => 'Usuário cadastrado com sucesso!', 'usuario' => $usuario]);
    }


    // Método para deletar usuário pelo ID
    public function destroy($id)
    {
        // Deleta o usuário da tabela 'usuarios' pelo id
        DB::table('usuarios')->where('id', $id)->delete();

        // Retorna resposta JSON confirmando exclusão
        return response()->json(['mensagem' => 'Usuário excluído com sucesso.']);
    }


    // Método para buscar dados de um usuário específico para edição
    public function edit($id)
    {
        // Busca usuário pelo id
        $usuario = DB::table('usuarios')->where('id', $id)->first();

        // Retorna os dados em JSON (para preencher o formulário via AJAX)
        return response()->json($usuario);
    }


    // Método para atualizar um usuário existente
    public function update(Request $request, $id)
    {
        // Valida os dados recebidos, email é único exceto para o próprio usuário (passa o id para ignorar)
        $data = $request->validate([
            'nome' => 'required',
            'email' => 'required|email|unique:usuarios,email,' . $id,
            'senha' => 'nullable', // senha é opcional no update
            'tipo' => 'required|in:aluno,professor,administrador',
            'curso' => 'nullable',
            'departamento' => 'nullable',
        ]);

        // Se senha preenchida, encripta, senão não atualiza a senha
        if (!empty($data['senha'])) {
            $data['senha'] = bcrypt($data['senha']);
        } else {
            unset($data['senha']);
        }

        // Atualiza os dados na tabela pelo id
        DB::table('usuarios')->where('id', $id)->update($data);

        // Se a requisição espera JSON (AJAX), retorna confirmação JSON
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Usuário atualizado com sucesso']);
        }
    }

    // Método extra para listar usuários (não está sendo usado diretamente, mas pode ser útil)
    public function lista()
    {
        $usuarios = DB::table('usuarios')->get();
        return response()->json($usuarios);
    }
}
