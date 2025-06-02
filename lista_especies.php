<?php
include_once __DIR__ . '/includes/conexao.php';


// Consulta com JOIN para trazer os nomes das ordens e famílias
$sql = "SELECT 
            e.id, 
            e.nome, 
            o.nome as ordem_nome, 
            f.nome as familia_nome, 
            e.regiao, 
            e.habitat, 
            e.grau_de_ameaca 
        FROM especie e
        LEFT JOIN ordem o ON e.ordemID = o.id
        LEFT JOIN familia f ON e.familiaID = f.id";

$result = $conn->query($sql);

if ($result === false) {
    die("Erro na consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Espécies - Fauna do RS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        h1 {
            color: #2c3e50;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 3px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e9e9e9;
        }
        .acoes a {
            margin-right: 10px;
            color: #3498db;
            text-decoration: none;
        }
        .acoes a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Lista de Espécies do Rio Grande do Sul</h1>
    
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Ordem</th>
                    <th>Família</th>
                    <th>Região</th>
                    <th>Habitat</th>
                    <th>Grau de Ameaça</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['nome']) ?></td>
                        <td><?= htmlspecialchars($row['ordem_nome'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($row['familia_nome'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($row['regiao']) ?></td>
                        <td><?= htmlspecialchars($row['habitat']) ?></td>
                        <td><?= htmlspecialchars($row['grau_de_ameaca']) ?></td>
                        <td class="acoes">
                            <a href="editar.php?id=<?= $row['id'] ?>">Editar</a>
                            <a href="deletar.php?id=<?= $row['id'] ?>">Deletar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center;">Nenhuma espécie encontrada no banco de dados.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>
</html>