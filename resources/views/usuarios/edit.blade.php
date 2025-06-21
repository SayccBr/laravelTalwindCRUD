<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function exibirCampos() {
            const tipo = document.getElementById('tipo').value;
            document.getElementById('curso').style.display = tipo === 'aluno' ? 'block' : 'none';
            document.getElementById('departamento').style.display = tipo === 'professor' ? 'block' : 'none';
        }
    </script>
</head>
<body class="p-6">
    <h1 class="text-2xl font-bold mb-4">Editar Usuário</h1>

    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST" class="space-y-2">
        @csrf
        @method('PUT')

        <input name="nome" value="{{ $usuario->nome }}" class="border p-2 w-full">
        <input name="email" value="{{ $usuario->email }}" class="border p-2 w-full">
        <input name="senha" placeholder="Nova Senha (deixe em branco para manter)" type="password" class="border p-2 w-full">

        <select name="tipo" id="tipo" onchange="exibirCampos()" class="border p-2 w-full">
            <option value="aluno" {{ $usuario->tipo === 'aluno' ? 'selected' : '' }}>Aluno</option>
            <option value="professor" {{ $usuario->tipo === 'professor' ? 'selected' : '' }}>Professor</option>
            <option value="administrador" {{ $usuario->tipo === 'administrador' ? 'selected' : '' }}>Administrador</option>
        </select>

        <input id="curso" name="curso" placeholder="Curso (Aluno)" class="border p-2 w-full" value="{{ $usuario->curso }}" style="display: {{ $usuario->tipo === 'aluno' ? 'block' : 'none' }}">
        <input id="departamento" name="departamento" placeholder="Departamento (Professor)" class="border p-2 w-full" value="{{ $usuario->departamento }}" style="display: {{ $usuario->tipo === 'professor' ? 'block' : 'none' }}">

        <button class="bg-green-600 text-white px-4 py-2">Atualizar</button>
        <a href="{{ route('usuarios.index') }}" class="ml-4 text-blue-600">Voltar</a>
    </form>

    <script>exibirCampos();</script>
</body>
</html>
