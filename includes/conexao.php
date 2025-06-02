<?php
// Conexão com o banco de dados (usando o mesmo estilo do seu código original)
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "especie";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Falha na conexão: ". $conn->connect_error);
}