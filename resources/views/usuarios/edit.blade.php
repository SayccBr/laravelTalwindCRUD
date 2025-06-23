<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><!-- Define codificação UTF-8 -->
    <title>Editar Usuário</title><!-- Título da página -->
    <script src="https://cdn.tailwindcss.com"></script><!-- Tailwind CSS via CDN -->
</head>
<body class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow"><!-- Container centralizado com sombra e padding -->
        <h1 class="text-2xl font-bold mb-4">Editar Usuário</h1><!-- Título -->

        <!-- Formulário para edição -->
        <form id="form-editar" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Campo oculto com o ID do usuário -->
            <input type="hidden" id="usuario_id" value="{{ $usuario->id }}">

            <!-- Campos para editar nome, email e senha -->
            <input name="nome" id="nome" placeholder="Nome" value="{{ $usuario->nome }}" class="border p-2 w-full rounded">
            <input name="email" id="email" placeholder="Email" value="{{ $usuario->email }}" class="border p-2 w-full rounded">
            <input name="senha" id="senha" type="password" placeholder="Nova Senha (opcional)" class="border p-2 w-full rounded">

            <!-- Select para escolher o tipo -->
            <select name="tipo" id="tipo" class="border p-2 w-full rounded" onchange="exibirCampos()">
                <option value="aluno" {{ $usuario->tipo === 'aluno' ? 'selected' : '' }}>Aluno</option>
                <option value="professor" {{ $usuario->tipo === 'professor' ? 'selected' : '' }}>Professor</option>
                <option value="administrador" {{ $usuario->tipo === 'administrador' ? 'selected' : '' }}>Administrador</option>
            </select>

            <!-- Campo para curso, mostrado se tipo = aluno -->
            <div id="campoCurso" class="{{ $usuario->tipo === 'aluno' ? '' : 'hidden' }}">
                <input name="curso" id="curso" placeholder="Curso (Aluno)" value="{{ $usuario->curso }}" class="border p-2 w-full rounded">
            </div>

            <!-- Campo para departamento, mostrado se tipo = professor -->
            <div id="campoDepartamento" class="{{ $usuario->tipo === 'professor' ? '' : 'hidden' }}">
                <input name="departamento" id="departamento" placeholder="Departamento (Professor)" value="{{ $usuario->departamento }}" class="border p-2 w-full rounded">
            </div>

            <!-- Botão para enviar atualização -->
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Atualizar</button>
            <a href="{{ route('usuarios.index') }}" class="ml-4 text-blue-600">Voltar</a><!-- Link para voltar -->

            <p id="mensagem" class="text-sm mt-2"></p><!-- Local para mensagens de sucesso ou erro -->
        </form>
    </div>

    <script>
        // Função que mostra ou esconde campos adicionais conforme o tipo selecionado
        function exibirCampos() {
            const tipo = document.getElementById('tipo').value;
            document.getElementById('campoCurso').classList.add('hidden');
            document.getElementById('campoDepartamento').classList.add('hidden');

            if (tipo === 'aluno') {
                document.getElementById('campoCurso').classList.remove('hidden'); // mostra curso
            } else if (tipo === 'professor') {
                document.getElementById('campoDepartamento').classList.remove('hidden'); // mostra departamento
            }
        }

        // Após carregar a página, configura campos e trata submit via AJAX
        document.addEventListener('DOMContentLoaded', () => {
            exibirCampos();

            // Captura evento submit do formulário de edição
            document.getElementById('form-editar').addEventListener('submit', function(e) {
                e.preventDefault(); // previne recarregar página

                const id = document.getElementById('usuario_id').value;

                // Monta objeto com dados do formulário para envio via AJAX
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

                // Envia requisição para atualizar usuário (usando método POST com _method=PUT)
                fetch(`/usuarios/${id}`, {
                    method: 'POST', // Laravel aceita método POST com _method=PUT para updates
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(response => {
                    // Mostra mensagem de sucesso e redireciona para lista após 1s
                    document.getElementById('mensagem').innerText = 'Usuário atualizado com sucesso!';
                    document.getElementById('mensagem').classList.add('text-green-600');
                    setTimeout(() => window.location.href = "{{ route('usuarios.index') }}", 1000);
                })
                .catch(error => {
                    // Mostra mensagem de erro se falhar
                    document.getElementById('mensagem').innerText = 'Erro ao atualizar usuário.';
                    document.getElementById('mensagem').classList.add('text-red-600');
                });
            });
        });
    </script>
</body>
</html>
