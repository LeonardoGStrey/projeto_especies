<?php
session_start();
include_once __DIR__ . '/includes/conexao.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID inválido.");
}

$id = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $ordemID = $_POST['ordemID'] ?? '';
    $familiaID = $_POST['familiaID'] ?? '';
    $regiao = $_POST['regiao'] ?? '';
    $habitat = $_POST['habitat'] ?? '';
    $grau = $_POST['grau_de_ameaca'] ?? '';

    $stmt = $conn->prepare("UPDATE especie SET nome=?, ordemID=?, familiaID=?, regiao=?, habitat=?, grau_de_ameaca=? WHERE id=?");
    $stmt->bind_param("siisssi", $nome, $ordemID, $familiaID, $regiao, $habitat, $grau, $id);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Espécie atualizada com sucesso!";
        $_SESSION['tipo'] = "sucesso";
    } else {
        $_SESSION['mensagem'] = "Erro ao atualizar a espécie.";
        $_SESSION['tipo'] = "erro";
    }

    header("Location: lista_especies.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM especie WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$especie = $result->fetch_assoc();

if (!$especie) {
    die("Espécie não encontrada.");
}

$ordens = $conn->query("SELECT * FROM ordem ORDER BY nome");
$familias = $conn->query("SELECT * FROM familia ORDER BY nome");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Atualizar Espécie</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #121212;
            color: #f0f0f0;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            background-color: #1e1e1e;
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 255, 128, 0.1);
        }

        h1 {
            text-align: center;
            color: #00e676;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-top: 16px;
            margin-bottom: 6px;
            font-weight: 600;
            color: #cfcfcf;
        }

        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #333;
            border-radius: 6px;
            background-color: #2a2a2a;
            color: #fff;
            font-size: 15px;
        }

        input[type="text"]:focus,
        select:focus,
        textarea:focus {
            border-color: #00e676;
            outline: none;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .btn {
            margin-top: 24px;
            padding: 14px 24px;
            background-color: #00e676;
            border: none;
            color: #121212;
            font-weight: bold;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #00c364;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Espécie</h1>

        <form method="POST">
            <label>Nome</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($especie['nome']) ?>" required>

            <label>Ordem</label>
            <select name="ordemID" required>
                <?php while ($ordem = $ordens->fetch_assoc()): ?>
                    <option value="<?= $ordem['id'] ?>" <?= $ordem['id'] == $especie['ordemID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($ordem['nome']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Família</label>
            <select name="familiaID" required>
                <?php while ($familia = $familias->fetch_assoc()): ?>
                    <option value="<?= $familia['id'] ?>" <?= $familia['id'] == $especie['familiaID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($familia['nome']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Região</label>
            <input type="text" name="regiao" value="<?= htmlspecialchars($especie['regiao']) ?>" required>

            <label>Habitat</label>
            <textarea name="habitat" required><?= htmlspecialchars($especie['habitat']) ?></textarea>

            <label>Grau de Ameaça</label>
            <input type="text" name="grau_de_ameaca" value="<?= htmlspecialchars($especie['grau_de_ameaca']) ?>" required>

            <button type="submit" class="btn">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>
