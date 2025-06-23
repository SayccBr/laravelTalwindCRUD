<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Editar Usuário</h1>

        <form id="form-editar" class="space-y-4">
            @csrf
            @method('PUT')

            <input type="hidden" id="usuario_id" value="{{ $usuario->id }}">

            <input name="nome" id="nome" placeholder="Nome" value="{{ $usuario->nome }}" class="border p-2 w-full rounded">
            <input name="email" id="email" placeholder="Email" value="{{ $usuario->email }}" class="border p-2 w-full rounded">
            <input name="senha" id="senha" type="password" placeholder="Nova Senha (opcional)" class="border p-2 w-full rounded">

            <select name="tipo" id="tipo" class="border p-2 w-full rounded" onchange="exibirCampos()">
                <option value="aluno" {{ $usuario->tipo === 'aluno' ? 'selected' : '' }}>Aluno</option>
                <option value="professor" {{ $usuario->tipo === 'professor' ? 'selected' : '' }}>Professor</option>
                <option value="administrador" {{ $usuario->tipo === 'administrador' ? 'selected' : '' }}>Administrador</option>
            </select>

            <div id="campoCurso" class="{{ $usuario->tipo === 'aluno' ? '' : 'hidden' }}">
                <input name="curso" id="curso" placeholder="Curso (Aluno)" value="{{ $usuario->curso }}" class="border p-2 w-full rounded">
            </div>

            <div id="campoDepartamento" class="{{ $usuario->tipo === 'professor' ? '' : 'hidden' }}">
                <input name="departamento" id="departamento" placeholder="Departamento (Professor)" value="{{ $usuario->departamento }}" class="border p-2 w-full rounded">
            </div>

            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Atualizar</button>
            <a href="{{ route('usuarios.index') }}" class="ml-4 text-blue-600">Voltar</a>

            <p id="mensagem" class="text-sm mt-2"></p>
        </form>
    </div>

    <script>
        function exibirCampos() {
            const tipo = document.getElementById('tipo').value;
            document.getElementById('campoCurso').classList.add('hidden');
            document.getElementById('campoDepartamento').classList.add('hidden');

            if (tipo === 'aluno') {
                document.getElementById('campoCurso').classList.remove('hidden');
            } else if (tipo === 'professor') {
                document.getElementById('campoDepartamento').classList.remove('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            exibirCampos();

            document.getElementById('form-editar').addEventListener('submit', function(e) {
                e.preventDefault();

                const id = document.getElementById('usuario_id').value;
                const data = {
                    _token: document.querySelector('input[name="_token"]').value,
                    _method: 'PUT',
                    nome: document.getElementById('nome').value,
                    email: document.getElementById('email').value,
                    senha: document.getElementById('senha').value,
                    tipo: document.getElementById('tipo').value,
                    curso: document.getElementById('curso')?.value ?? '',
                    departamento: document.getElementById('departamento')?.value ?? '',
                };

                fetch(`/usuarios/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(response => {
                    document.getElementById('mensagem').innerText = 'Usuário atualizado com sucesso!';
                    document.getElementById('mensagem').classList.add('text-green-600');
                    setTimeout(() => window.location.href = "{{ route('usuarios.index') }}", 1000);
                })
                .catch(error => {
                    document.getElementById('mensagem').innerText = 'Erro ao atualizar usuário.';
                    document.getElementById('mensagem').classList.add('text-red-600');
                });
            });
        });
    </script>
</body>
</html>
