<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Usuários (AJAX)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
</head>
<body class="p-6 bg-gray-100 min-h-screen">
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Usuários</h1>

    <!-- Formulário -->
    <form id="formUsuario" class="space-y-4">
        <input name="nome" placeholder="Nome" class="border p-2 w-full rounded">
        <input name="email" placeholder="Email" class="border p-2 w-full rounded">
        <input name="senha" type="password" placeholder="Senha" class="border p-2 w-full rounded">

        <select name="tipo" id="tipo" class="border p-2 w-full rounded" onchange="atualizarCampos()">
            <option value="">Selecione o tipo</option>
            <option value="aluno">Aluno</option>
            <option value="professor">Professor</option>
            <option value="administrador">Administrador</option>
        </select>

        <div id="campoCurso" class="hidden">
            <input name="curso" placeholder="Curso (Aluno)" class="border p-2 w-full rounded">
        </div>

        <div id="campoDepartamento" class="hidden">
            <input name="departamento" placeholder="Departamento (Professor)" class="border p-2 w-full rounded">
        </div>

        <input type="hidden" name="id" id="userId">
        <button id="btnSalvar" class="bg-blue-600 text-white px-4 py-2 rounded">Cadastrar</button>
    </form>

    <hr class="my-6">

    <table class="w-full border text-sm" id="tabelaUsuarios">
        <thead class="bg-gray-200">
        <tr>
            <th class="border px-3 py-2">Nome</th>
            <th class="border px-3 py-2">Email</th>
            <th class="border px-3 py-2">Tipo</th>
            <th class="border px-3 py-2">Ações</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function atualizarCampos() {
        const tipo = document.getElementById('tipo').value;
        document.getElementById('campoCurso').classList.add('hidden');
        document.getElementById('campoDepartamento').classList.add('hidden');

        if (tipo === 'aluno') {
            document.getElementById('campoCurso').classList.remove('hidden');
        } else if (tipo === 'professor') {
            document.getElementById('campoDepartamento').classList.remove('hidden');
        }
    }

    async function carregarUsuarios() {
    const res = await fetch('/usuarios', {
        headers: {
            'Accept': 'application/json'
        }
    });

    const usuarios = await res.json(); 
    const tbody = document.querySelector('#tabelaUsuarios tbody');
    tbody.innerHTML = '';

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


    async function excluir(id) {
        if (!confirm('Tem certeza que deseja excluir?')) return;
        await fetch(`/usuarios/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': token }
        });
        carregarUsuarios();
    }

    function editar(usuario) {
        const form = document.forms['formUsuario'];
        form.nome.value = usuario.nome;
        form.email.value = usuario.email;
        form.tipo.value = usuario.tipo;
        document.getElementById('userId').value = usuario.id;
        form.senha.value = '';
        form.curso.value = usuario.curso ?? '';
        form.departamento.value = usuario.departamento ?? '';
        atualizarCampos();
        document.getElementById('btnSalvar').textContent = 'Atualizar';
    }

    document.getElementById('formUsuario').addEventListener('submit', async e => {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        const id = data.id;
        delete data.id;

        const metodo = id ? 'PUT' : 'POST';
        const url = id ? `/usuarios/${id}` : '/usuarios';

        await fetch(url, {
            method: metodo,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify(data)
        });

        form.reset();
        document.getElementById('campoCurso').classList.add('hidden');
        document.getElementById('campoDepartamento').classList.add('hidden');
        document.getElementById('btnSalvar').textContent = 'Cadastrar';
        carregarUsuarios();
    });

    document.addEventListener('DOMContentLoaded', () => {
        atualizarCampos();
        carregarUsuarios();
    });
</script>
</body>
</html>
