<?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'banco_tintas'; 

// Cria a conexão
$conexao = mysqli_connect($host, $user, $password, $database);

// Verifica a conexão
if (!$conexao) {
    die("Erro na conexão: " . mysqli_connect_error());
}



?>
