<?php
session_start();
include_once __DIR__ . '/includes/conexao.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM especie WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Espécie deletada com sucesso!";
        $_SESSION['tipo'] = "sucesso";
    } else {
        $_SESSION['mensagem'] = "Erro ao deletar espécie.";
        $_SESSION['tipo'] = "erro";
    }

    $stmt->close();
    $conn->close();
} else {
    $_SESSION['mensagem'] = "ID inválido.";
    $_SESSION['tipo'] = "erro";
}

header("Location: lista_especies.php");
exit;
