<?php
// Garante que a sessão está ativa para verificar o usuário no menu
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BreakRoll - Streaming de Animes e Mangás</title>
    <!-- Caminho para o CSS na raiz do projeto -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="logo">
            <h1><a href="index.php" style="text-decoration: none; color: inherit;">BreakRoll</a></h1>
        </div>
        <nav class="site-nav">
            <ul>
                <li><a href="index.php">Início</a></li>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <li><a href="catalogo.php">Catálogo</a></li>
                    <li><a href="logout.php" style="color: #ff4d4d;">Sair (<?= htmlspecialchars($_SESSION['usuario_nome']) ?>)</a></li>
                <?php else: ?>
                    <li><a href="login.php">Entrar</a></li>
                    <li><a href="cadastro.php">Cadastrar</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <hr>
    <main class="main-content">