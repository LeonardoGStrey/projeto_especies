<?php
// dados do servidor de banco de dados
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "especie";

// objeto que controla a conexao com o banco
$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica a conexao
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
