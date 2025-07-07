<?php
session_start();
include_once __DIR__ . '/includes/conexao.php';

$busca = $_GET['busca'] ?? '';

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
        LEFT JOIN familia f ON e.familiaID = f.id
        WHERE e.nome LIKE ? 
           OR e.regiao LIKE ? 
           OR e.habitat LIKE ? 
           OR e.grau_de_ameaca LIKE ?
        ORDER BY e.id ASC";

$param = "%{$busca}%";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $param, $param, $param, $param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Preservação da Fauna e Flora</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="style.css" />
    <script type="module" src="script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
            background: var(--card-bg);
            color: var(--text);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            overflow: hidden;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            background: var(--gradient);
            color: #fff;
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .acoes a {
            margin-right: 10px;
            color: var(--secondary);
            text-decoration: none;
            font-weight: bold;
        }

        .acoes a:hover {
            color: var(--primary);
        }

        .btn-incluir {
            display: inline-block;
            margin-top: 20px;
            padding: 0.75rem 1.5rem;
            background: var(--gradient);
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 30px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .btn-incluir:hover {
            opacity: 0.9;
        }

        .mensagem {
            max-width: 800px;
            margin: 20px auto;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            transition: opacity 0.5s ease-out;
        }

        .mensagem.sucesso {
            background-color: #2ecc71;
            color: #fff;
        }

        .mensagem.erro {
            background-color: #e74c3c;
            color: #fff;
        }

        .container-tabela {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .topo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .topo h2 {
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2.2rem;
        }

        .form-busca {
            margin-top: 1rem;
            display: flex;
            gap: 1rem;
        }

        .form-busca input[type="text"] {
            flex: 1;
            padding: 0.6rem 1rem;
            border: 1px solid #ccc;
            border-radius: 20px;
            background: #1f1f1f;
            color: white;
        }

        .form-busca button {
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 20px;
            background: var(--gradient);
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.3s;
        }

        .form-busca button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="background-animation"></div>

    <!-- Header -->
    <header>
        <nav class="main-nav">
            <div class="logo">
                <i class="fas fa-leaf"></i>
                <span>Preservação</span>
            </div>
            <div class="nav-links">
                <a href="index.html" class="nav-link">Início</a>
                    <a target="_blank" href="https://mail.google.com/mail/?view=cm&fs=1&to=projetopreservacaoses@gmail.com" class="nav-link">Contato</a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <main>
        <!-- Lista de Espécies -->
        <section class="container-tabela">
            <div class="topo">
                <h2>Lista de Espécies</h2>
                <a class="btn-incluir" href="criar_especie.php">Incluir Espécie</a>
            </div>

            <form method="get" class="form-busca">
                <input type="text" name="busca" placeholder="Buscar por nome, região, habitat..." value="<?= htmlspecialchars($busca) ?>">
                <button type="submit">Buscar</button>
            </form>

            <?php if (isset($_SESSION['mensagem']) && is_string($_SESSION['mensagem'])): ?>
                <div id="mensagem" class="mensagem <?= $_SESSION['tipo'] ?>">
                    <?= htmlspecialchars($_SESSION['mensagem']) ?>
                </div>
                <?php unset($_SESSION['mensagem'], $_SESSION['tipo']); ?>
            <?php endif; ?>

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
                                    <a href="atualizar_especie.php?id=<?= $row['id'] ?>">Editar</a>
                                    <a href="deletar.php?id=<?= $row['id'] ?>" onclick="return confirm('Tem certeza que deseja deletar esta espécie?')">Deletar</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; color: var(--text-muted);">Nenhuma espécie encontrada no banco de dados.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-main">
                <p class="footer-motto">Preservando hoje para existir amanhã.</p>
                <div class="social-links">
                    <a target="_blank" href="https://www.facebook.com/profile.php?id=61577299915233" class="social-link"><i class="fab fa-facebook"></i></a>
                    <a target="_blank" href="https://www.instagram.com/projeto_preservacao/" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a target="_blank" href="https://x.com/preservaoambie" class="social-link"><i class="fab fa-twitter"></i></a>
                </div>
            </div>

            <div class="team-section">
                <h3>Desenvolvido por:</h3>
                <div class="github-profiles">
                    <a target="_blank" href="https://github.com/lucastuia" class="github-profile"><i class="fab fa-github"></i><span>Lucas Oliveira</span></a>
                    <a target="_blank" href="https://github.com/LeonardoGStrey" class="github-profile"><i class="fab fa-github"></i><span>Leonardo Grübel</span></a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        window.onload = function () {
            const mensagem = document.getElementById('mensagem');
            if (mensagem) {
                setTimeout(() => {
                    mensagem.style.opacity = '0';
                    setTimeout(() => mensagem.remove(), 500);
                }, 4000);
            }
        };
    </script>

    <?php $conn->close(); ?>
</body>
</html>
