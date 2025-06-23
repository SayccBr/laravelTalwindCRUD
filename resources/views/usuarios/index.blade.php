<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><!-- Define a codificação de caracteres como UTF-8 -->
    <title>Usuários (AJAX)</title><!-- Título da página -->
    
    <!-- Importa o Tailwind CSS via CDN (para estilização rápida) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Meta tag com token CSRF gerado pelo Laravel, usado para segurança nas requisições AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Importa CSS via Vite (build local do Tailwind) - se estiver usando Laravel + Vite -->
    @vite('resources/css/app.css')
</head>
<body class="p-6 bg-gray-100 min-h-screen">
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Usuários</h1>

    <!-- Formulário para cadastro e edição de usuários -->
    <form id="formUsuario" class="space-y-4">
        <!-- Campo para nome -->
        <input name="nome" placeholder="Nome" class="border p-2 w-full rounded">
        
        <!-- Campo para email -->
        <input name="email" placeholder="Email" class="border p-2 w-full rounded">
        
        <!-- Campo para senha -->
        <input name="senha" type="password" placeholder="Senha" class="border p-2 w-full rounded">

        <!-- Select para escolher o tipo de usuário -->
        <select name="tipo" id="tipo" class="border p-2 w-full rounded" onchange="atualizarCampos()">
            <option value="">Selecione o tipo</option>
            <option value="aluno">Aluno</option>
            <option value="professor">Professor</option>
            <option value="administrador">Administrador</option>
        </select>

        <!-- Campo oculto para curso, mostrado somente se tipo = aluno -->
        <div id="campoCurso" class="hidden">
            <input name="curso" placeholder="Curso (Aluno)" class="border p-2 w-full rounded">
        </div>

        <!-- Campo oculto para departamento, mostrado somente se tipo = professor -->
        <div id="campoDepartamento" class="hidden">
            <input name="departamento" placeholder="Departamento (Professor)" class="border p-2 w-full rounded">
        </div>

        <!-- Campo oculto para armazenar o ID do usuário (usado na edição) -->
        <input type="hidden" name="id" id="userId">
        
        <!-- Botão para salvar cadastro ou edição -->
        <button id="btnSalvar" class="bg-blue-600 text-white px-4 py-2 rounded">Cadastrar</button>
    </form>

    <hr class="my-6">

    <!-- Tabela para listar usuários cadastrados -->
    <table class="w-full border text-sm" id="tabelaUsuarios">
        <thead class="bg-gray-200">
        <tr>
            <th class="border px-3 py-2">Nome</th>
            <th class="border px-3 py-2">Email</th>
            <th class="border px-3 py-2">Tipo</th>
            <th class="border px-3 py-2">Ações</th>
        </tr>
        </thead>
        <tbody></tbody> <!-- Corpo da tabela preenchido dinamicamente via JS -->
    </table>
</div>

<script>
    // Pega o token CSRF da meta tag para usar nas requisições AJAX (proteção contra CSRF)
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Função para mostrar/ocultar campos adicionais conforme o tipo selecionado
    function atualizarCampos() {
        const tipo = document.getElementById('tipo').value;
        document.getElementById('campoCurso').classList.add('hidden');
        document.getElementById('campoDepartamento').classList.add('hidden');

        if (tipo === 'aluno') {
            document.getElementById('campoCurso').classList.remove('hidden'); // mostra curso
        } else if (tipo === 'professor') {
            document.getElementById('campoDepartamento').classList.remove('hidden'); // mostra departamento
        }
    }

    // Função assíncrona para buscar usuários via AJAX e atualizar a tabela
    async function carregarUsuarios() {
        const res = await fetch('/usuarios', { headers: { 'Accept': 'application/json' } });
        const usuarios = await res.json(); // converte resposta para JSON
        const tbody = document.querySelector('#tabelaUsuarios tbody');
        tbody.innerHTML = ''; // limpa tabela antes de preencher

        // Para cada usuário, cria uma linha na tabela com dados e botões de ação
        usuarios.forEach(usuario => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="border px-3 py-2">${usuario.nome}</td>
                <td class="border px-3 py-2">${usuario.email}</td>
                <td class="border px-3 py-2">${usuario.tipo}</td>
                <td class="border px-3 py-2 flex gap-2 justify-center">
                    <button onclick='editar(${JSON.stringify(usuario)})' class="text-yellow-500 hover:underline">Editar</button>
                    <button onclick='excluir(${usuario.id})' class="text-red-500 hover:underline">Excluir</button>
                </td>`;
            tbody.appendChild(tr);
        });
    }

    // Função para excluir um usuário, pede confirmação, chama endpoint e recarrega tabela
    async function excluir(id) {
        if (!confirm('Tem certeza que deseja excluir?')) return;
        await fetch(`/usuarios/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': token }
        });
        carregarUsuarios(); // recarrega tabela após exclusão
    }

    // Função para preencher o formulário com os dados do usuário para edição
    function editar(usuario) {
        const form = document.forms['formUsuario'];
        form.nome.value = usuario.nome;
        form.email.value = usuario.email;
        form.tipo.value = usuario.tipo;
        document.getElementById('userId').value = usuario.id; // seta id oculto
        form.senha.value = ''; // limpa senha (não preenche por segurança)
        form.curso.value = usuario.curso ?? ''; // preenche curso se existir
        form.departamento.value = usuario.departamento ?? ''; // preenche departamento se existir
        atualizarCampos(); // ajusta visibilidade dos campos adicionais
        document.getElementById('btnSalvar').textContent = 'Atualizar'; // muda texto do botão
    }

    // Evento submit do formulário para cadastrar ou atualizar usuário via AJAX
    document.getElementById('formUsuario').addEventListener('submit', async e => {
        e.preventDefault(); // previne comportamento padrão (recarregar página)
        const form = e.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries()); // transforma em objeto
        const id = data.id; // pega id (se tiver)
        delete data.id; // remove id do objeto para enviar separado

        // Define método HTTP e URL conforme se é criação ou atualização
        const metodo = id ? 'PUT' : 'POST';
        const url = id ? `/usuarios/${id}` : '/usuarios';

        // Faz requisição AJAX enviando dados em JSON, com cabeçalhos apropriados
        await fetch(url, {
            method: metodo,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify(data)
        });

        form.reset(); // limpa formulário após envio
        document.getElementById('campoCurso').classList.add('hidden'); // oculta campos extras
        document.getElementById('campoDepartamento').classList.add('hidden');
        document.getElementById('btnSalvar').textContent = 'Cadastrar'; // volta texto do botão
        carregarUsuarios(); // recarrega a tabela para mostrar novo dado
    });

    // Ao carregar a página, configura a exibição dos campos e carrega usuários da API
    document.addEventListener('DOMContentLoaded', () => {
        atualizarCampos();
        carregarUsuarios();
    });
</script>
</body>
</html>
