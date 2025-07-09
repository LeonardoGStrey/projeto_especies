<?php
session_start();
include_once __DIR__ . '/includes/conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'] ?? '';
    $ordemID = $_POST['ordemID'] ?? null;
    $familiaID = $_POST['familiaID'] ?? null;
    $regiao = $_POST['regiao'] ?? '';
    $habitat = $_POST['habitat'] ?? '';
    $grau = $_POST['grau_de_ameaca'] ?? '';

    $stmt = $conn->prepare("INSERT INTO especie (nome, ordemID, familiaID, regiao, habitat, grau_de_ameaca) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("siisss", $nome, $ordemID, $familiaID, $regiao, $habitat, $grau);
        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Espécie criada com sucesso!";
            $_SESSION['tipo'] = "sucesso";
            header("Location: lista_especies.php");
            exit;
        } else {
            $_SESSION['mensagem'] = "Erro ao criar espécie: " . $stmt->error;
            $_SESSION['tipo'] = "erro";
        }
        $stmt->close();
    } else {
        $_SESSION['mensagem'] = "Erro na preparação da query.";
        $_SESSION['tipo'] = "erro";
    }
}

$ordens = $conn->query("SELECT id, nome FROM ordem");
$familias = $conn->query("SELECT id, nome FROM familia");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nova Espécie</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #121212;
            color: #f0f0f0;
            padding: 40px 20px;
            margin: 0;
        }

        h1 {
            text-align: center;
            color: #00e676;
            margin-bottom: 30px;
        }

        form {
            max-width: 700px;
            margin: auto;
            background-color: #1e1e1e;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 255, 128, 0.1);
        }

        label {
            display: block;
            margin-top: 16px;
            font-weight: 600;
            color: #cfcfcf;
        }

        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
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

        .botoes {
            display: flex;
            justify-content: space-between;
            margin-top: 24px;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            font-weight: bold;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }

        .btn-voltar {
            background-color: #7f8c8d;
            color: white;
        }

        .btn-enviar {
            background-color: #00e676;
            color: #121212;
        }

        .btn-voltar:hover {
            background-color: #95a5a6;
        }

        .btn-enviar:hover {
            background-color: #00c364;
        }
    </style>
</head>
<body>

<h1>Incluir Nova Espécie</h1>

<form method="POST">
    <label for="nome">Nome da Espécie:</label>
    <input type="text" name="nome" id="nome" required>

    <label for="ordemID">Ordem:</label>
    <select name="ordemID" id="ordemID" required>
        <option value="">Selecione...</option>
        <?php while ($o = $ordens->fetch_assoc()): ?>
            <option value="<?= $o['id'] ?>"><?= htmlspecialchars($o['nome']) ?></option>
        <?php endwhile; ?>
    </select>

    <label for="familiaID">Família:</label>
    <select name="familiaID" id="familiaID" required>
        <option value="">Selecione...</option>
        <?php while ($f = $familias->fetch_assoc()): ?>
            <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome']) ?></option>
        <?php endwhile; ?>
    </select>

    <label for="regiao">Região:</label>
    <input type="text" name="regiao" id="regiao" required>

    <label for="habitat">Habitat:</label>
    <textarea name="habitat" id="habitat" required></textarea>

    <label for="grau_de_ameaca">Grau de Ameaça:</label>
    <input type="text" name="grau_de_ameaca" id="grau_de_ameaca" required>

    <div class="botoes">
        <a class="btn btn-voltar" href="lista_especies.php">← Voltar</a>
        <button class="btn btn-enviar" type="submit">Salvar</button>
    </div>
</form>

<?php $conn->close(); ?>
</body>
</html>
            