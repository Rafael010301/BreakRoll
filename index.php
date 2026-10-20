<?php
// Inicia a sessão apenas para saber se o usuário já está logado e adaptar o topo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/header.php';
?>

<!-- Seção Principal / Hero Section -->
<section class="hero-landing" style="text-align: center; padding: 60px 20px;">
    <h1>Bem-vindo ao BreakRoll</h1>
    <p style="font-size: 1.2rem; color: #666; margin-top: 10px;">
        A sua plataforma definitiva para acompanhar os melhores animes e mangás em um só lugar.
    </p>

    <div class="cta-buttons" style="margin-top: 30px;">
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <!-- Se já estiver logado, manda direto para o catálogo -->
            <a href="catalogo.php" class="btn" style="padding: 12px 24px; background-color: #6366f1; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold;">Ir para o Catálogo</a>
        <?php else: ?>
            <!-- Se não estiver logado, oferece as opções de entrar ou cadastrar -->
            <a href="login.php" class="btn" style="padding: 12px 24px; background-color: #6366f1; color: #fff; text-decoration: none; border-radius: 6px; margin-right: 10px; font-weight: bold;">Entrar</a>
            <a href="cadastro.php" class="btn-outline" style="padding: 12px 24px; border: 2px solid #6366f1; color: #6366f1; text-decoration: none; border-radius: 6px; font-weight: bold;">Cadastre-se</a>
        <?php endif; ?>
    </div>
</section>

<!-- Seção Sobre Nós / Recursos -->
<section class="sobre-nos" style="padding: 40px 20px; background-color: #f9fafb; margin-top: 40px; border-radius: 8px;">
    <h2 style="text-align: center; margin-bottom: 20px;">Sobre o Projeto</h2>
    <div style="display: flex; justify-content: space-around; flex-wrap: wrap; gap: 20px;">
        <div style="flex: 1; min-width: 250px; background: #fff; padding: 20px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <h3>🚀 Alta Performance</h3>
            <p>Desenvolvido com PHP moderno utilizando PDO e arquitetura segura contra vulnerabilidades.</p>
        </div>
        <div style="flex: 1; min-width: 250px; background: #fff; padding: 20px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <h3>📚 Catálogo Exclusivo</h3>
            <p>Encontre sinopses, detalhes e navegue pelo universo dos seus mangás e animes favoritos.</p>
        </div>
        <div style="flex: 1; min-width: 250px; background: #fff; padding: 20px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <h3>🔒 Segurança em Foco</h3>
            <p>Senhas criptografadas com `password_hash` e notificações automatizadas via API.</p>
        </div>
    </div>
</section>

<?php
include 'includes/footer.php';
?>