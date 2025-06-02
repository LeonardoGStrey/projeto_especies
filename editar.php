<?php
include_once __DIR__ . '/includes/conexao.php';

// Verifica se o ID foi passado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: lista_especies.php');
    exit;
}

$id = (int)$_GET['id'];

// Busca os dados da espécie
$sql = "SELECT e.*, o.nome as ordem_nome, f.nome as familia_nome 
        FROM especie e
        LEFT JOIN ordem o ON e.ordemID = o.id
        LEFT JOIN familia f ON e.familiaID = f.id
        WHERE e.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$especie = $result->fetch_assoc();

// Busca todas as ordens e famílias para os selects
$ordens = $conn->query("SELECT * FROM ordem ORDER BY nome");
$familias = $conn->query("SELECT * FROM familia ORDER BY nome");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Espécie - Fauna do RS</title>
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --danger: #e74c3c;
            --success: #27ae60;
            --light: #ecf0f1;
            --dark: #34495e;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: #f9f9f9;
            color: #333;
        }
        
        .header {
            background-color: var(--primary);
            color: white;
            padding: 2rem 0;
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .card h2 {
            color: var(--primary);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--light);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark);
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--secondary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }
        
        .btn-primary {
            background-color: var(--success);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #219653;
        }
        
        .btn-danger {
            background-color: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 2rem;
        }
        
        .footer {
            text-align: center;
            padding: 2rem 0;
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Preservação da Fauna do RS</h1>
            <p>Protegendo a biodiversidade para as futuras gerações</p>
        </div>
    </header>
    
    <main class="container">
        <div class="card">
            <h2>Editar Espécie</h2>
            
            <form action="atualizar_especie.php" method="POST">
                <input type="hidden" name="id" value="<?= htmlspecialchars($especie['id']) ?>">
                
                <div class="form-group">
                    <label for="nome">Nome da Espécie</label>
                    <input type="text" id="nome" name="nome" class="form-control" 
                           value="<?= htmlspecialchars($especie['nome']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="ordemID">Ordem</label>
                    <select id="ordemID" name="ordemID" class="form-control" required>
                        <?php while($ordem = $ordens->fetch_assoc()): ?>
                            <option value="<?= $ordem['id'] ?>" 
                                <?= $ordem['id'] == $especie['ordemID'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ordem['nome']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="familiaID">Família</label>
                    <select id="familiaID" name="familiaID" class="form-control" required>
                        <?php while($familia = $familias->fetch_assoc()): ?>
                            <option value="<?= $familia['id'] ?>" 
                                <?= $familia['id'] == $especie['familiaID'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($familia['nome']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="regiao">Região</label>
                    <input type="text" id="regiao" name="regiao" class="form-control" 
                           value="<?= htmlspecialchars($especie['regiao']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="habitat">Habitat</label>
                    <textarea id="habitat" name="habitat" class="form-control" required><?= htmlspecialchars($especie['habitat']) ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="grau_de_ameaca">Grau de Ameaça</label>
                    <input type="text" id="grau_de_ameaca" name="grau_de_ameaca" class="form-control" 
                           value="<?= htmlspecialchars($especie['grau_de_ameaca']) ?>" required>
                </div>
                
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    <a href="lista_especies.php" class="btn btn-danger">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
    
    <footer class="footer">
        <div class="container">
            <p>© <?= date('Y') ?> Preservação da Fauna do RS. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>