<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Usuários</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/app.css')
</head>
<body class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Usuários</h1>

        <!-- Formulário de Cadastro -->
        <form action="{{ route('usuarios.store') }}" method="POST" class="space-y-4">
            @csrf

            <input name="nome" placeholder="Nome" class="border p-2 w-full rounded">
            <input name="email" placeholder="Email" class="border p-2 w-full rounded">
            <input name="senha" type="password" placeholder="Senha" class="border p-2 w-full rounded">

            <select name="tipo" id="tipo" class="border p-2 w-full rounded" onchange="atualizarCampos()">
                <option value="">Selecione o tipo</option>
                <option value="aluno">Aluno</option>
                <option value="professor">Professor</option>
                <option value="administrador">Administrador</option>
            </select>

            <!-- Campo Curso (Aluno) -->
            <div id="campoCurso" class="hidden">
                <input name="curso" placeholder="Curso (Aluno)" class="border p-2 w-full rounded">
            </div>

            <!-- Campo Departamento (Professor) -->
            <div id="campoDepartamento" class="hidden">
                <input name="departamento" placeholder="Departamento (Professor)" class="border p-2 w-full rounded">
            </div>

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Cadastrar</button>
        </form>

        <!-- Separador -->
        <hr class="my-6">

        <!-- Tabela de Usuários -->
        <table class="w-full border text-sm">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border px-3 py-2">Nome</th>
                    <th class="border px-3 py-2">Email</th>
                    <th class="border px-3 py-2">Tipo</th>
                    <th class="border px-3 py-2">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $usuario)
                <tr class="text-center">
                    <td class="border px-3 py-2">{{ $usuario->nome }}</td>
                    <td class="border px-3 py-2">{{ $usuario->email }}</td>
                    <td class="border px-3 py-2 capitalize">{{ $usuario->tipo }}</td>
                    <td class="border px-3 py-2 flex justify-center gap-2">
                        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="text-yellow-600 hover:underline">Editar</a>
                        <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" onsubmit="return confirm('Deseja realmente excluir?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-500 hover:underline">Excluir</button>
                        </form>
                    </td>
                </tr>
                @endforeach

                @if ($usuarios->isEmpty())
                <tr>
                    <td colspan="4" class="text-center text-gray-500 py-4">Nenhum usuário cadastrado.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Script de Exibição Dinâmica -->
    <script>
        function atualizarCampos() {
            const tipo = document.getElementById('tipo').value;
            const campoCurso = document.getElementById('campoCurso');
            const campoDepartamento = document.getElementById('campoDepartamento');

            campoCurso.classList.add('hidden');
            campoDepartamento.classList.add('hidden');

            if (tipo === 'aluno') {
                campoCurso.classList.remove('hidden');
            } else if (tipo === 'professor') {
                campoDepartamento.classList.remove('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', atualizarCampos);
    </script>
</body>
</html>
