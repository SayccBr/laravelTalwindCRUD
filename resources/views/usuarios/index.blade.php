<!DOCTYPE html>
<html>
<head>
    <title>CRUD Usuários</title>
</head>
<body>

<h1>Usuários</h1>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

<form action="{{ route('usuarios.store') }}" method="POST">
    @csrf
    <input type="text" name="nome" placeholder="Nome" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="tipo" placeholder="Tipo" required>
    <input type="text" name="curso" placeholder="Curso">
    <input type="text" name="departamento" placeholder="Departamento">
    <button type="submit">Adicionar</button>
</form>

<h2>Lista de Usuários</h2>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Tipo</th>
        <th>Curso</th>
        <th>Departamento</th>
    </tr>
    @foreach($usuarios as $usuario)
    <tr>
        <td>{{ $usuario->nome }}</td>
        <td>{{ $usuario->email }}</td>
        <td>{{ $usuario->tipo }}</td>
        <td>{{ $usuario->curso }}</td>
        <td>{{ $usuario->departamento }}</td>
    </tr>
    @endforeach
</table>

</body>
</html>
