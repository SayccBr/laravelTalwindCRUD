<?php
try {
    $dbh = new PDO('mysql:host=127.0.0.1;dbname=laravel_crud', 'root', '');
    echo "Conectado com sucesso!";
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
