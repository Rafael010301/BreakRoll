<?php
// Carrega o autenticador e valida a sessão
require_once 'includes/auth.php';
verificarAutenticacao(); // Se não estiver logado, redireciona pro login

require_once 'includes/conexao.php';
include 'includes/header.php';

// Busca os conteúdos do banco de dados com PDO
$stmt = $pdo->query("SELECT * FROM conteudo ORDER BY id_conteudo DESC");
$conteudos = $stmt->fetchAll();
?>

<h2>Catálogo de Animes e Mangás</h2>
<p>Bem-vindo à área restrita, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>! (<a href="logout.php">Sair</a>)</p>

<div class="grid-conteudo">
    <?php if (count($conteudos) > 0): ?>
        <?php foreach ($conteudos as $item): ?>
            <div class="card">
                <h3><?= htmlspecialchars($item['titulo_conteudo'] ?? 'Sem Título') ?></h3>
                <p><?= htmlspecialchars($item['descricao_conteudo'] ?? '') ?></p>
                <a href="detalhes.php?id=<?= $item['id_conteudo'] ?>">Ver Detalhes</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nenhum conteúdo cadastrado no momento.</p>
    <?php endif; ?>
</div>

<?php
include 'includes/footer.php';
?>