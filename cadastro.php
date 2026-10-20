<?php
date_default_timezone_set('America/Sao_Paulo');
// Inclui as configurações (onde está a chave da API) e a conexão com o banco
require_once 'config.php';
require_once 'includes/conexao.php';

$mensagemErro = '';

// Processa o formulário quando enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senhaPura = $_POST['senha'] ?? '';

    // Validação de campos vazios
    if (empty($nome) || empty($email) || empty($senhaPura)) {
        $mensagemErro = "Por favor, preencha todos os campos obrigatórios.";
    } else {
        // Criptografa a senha para segurança
        $senhaHash = password_hash($senhaPura, PASSWORD_DEFAULT);

        try {
            // Insere o novo usuário usando PDO
            $sql = "INSERT INTO usuarios (nome_user, email_user, senha_user) VALUES (:nome, :email, :senha)";
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                ':nome'  => $nome,
                ':email' => $email,
                ':senha' => $senhaHash
            ]);

            // 1. Pega o ID do usuário que acabou de ser gerado no banco
            $usuarioId = $pdo->lastInsertId();

            // 2. Inicia a sessão e já autentica o usuário automaticamente
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['usuario_id'] = $usuarioId;
            $_SESSION['usuario_nome'] = $nome;

            // Disparo de notificação de novo cadastro via API do Resend
            $dadosEmail = [
                'from'    => 'onboarding@resend.dev',
                'to'      => [DONO_EMAIL],
                'subject' => 'Novo cadastro no BreakRoll!',
                'html'    => "<h2>Novo usuário cadastrado!</h2>
                              <p><strong>Nome:</strong> " . htmlspecialchars($nome) . "</p>
                              <p><strong>E-mail:</strong> " . htmlspecialchars($email) . "</p>
                              <p>Data/Hora: " . date('d/m/Y H:i:s') . "</p>"
            ];

            $ch = curl_init('https://api.resend.com/emails');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dadosEmail));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . RESEND_API_KEY,
                'Content-Type: application/json'
            ]);

            curl_exec($ch);
            curl_close($ch);

            // 3. Redireciona direto para o catálogo (já logado)
            header("Location: catalogo.php");
            exit;

        } catch (PDOException $e) {
            // Trata violação de e-mail único (código 23000 no SQLSTATE)
            if ($e->getCode() == '23000') {
                $mensagemErro = "Este e-mail já está cadastrado. Tente fazer login ou use outro e-mail.";
            } else {
                $mensagemErro = "Ocorreu um erro ao realizar o cadastro. Tente novamente mais tarde.";
            }
        }
    }
}

// Inclui o cabeçalho padrão da aplicação
include 'includes/header.php';
?>

<div class="auth-container" style="max-width: 420px; margin: 40px auto; padding: 25px; background: #fff; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
    <h2 style="text-align: center; margin-bottom: 20px;">Crie sua Conta</h2>
    
    <!-- Exibição de Alertas -->
    <?php if (!empty($mensagemErro)): ?>
        <div class="alert alert-danger" style="background-color: #fee2e2; color: #dc2626; padding: 10px 15px; border-radius: 6px; margin-bottom: 15px; font-size: 0.9rem;">
            <?= htmlspecialchars($mensagemErro) ?>
        </div>
    <?php endif; ?>

    <!-- Formulário de Cadastro -->
    <form action="cadastro.php" method="POST" class="auth-form" style="display: flex; flex-direction: column; gap: 15px;">
        <div class="form-group" style="display: flex; flex-direction: column; gap: 5px;">
            <label for="nome" style="font-weight: 600; font-size: 0.9rem;">Nome completo:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" placeholder="Digite seu nome" required style="padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;">
        </div>

        <div class="form-group" style="display: flex; flex-direction: column; gap: 5px;">
            <label for="email" style="font-weight: 600; font-size: 0.9rem;">E-mail:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="seu@email.com" required style="padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;">
        </div>

        <div class="form-group" style="display: flex; flex-direction: column; gap: 5px;">
            <label for="senha" style="font-weight: 600; font-size: 0.9rem;">Senha:</label>
            <input type="password" id="senha" name="senha" placeholder="Crie uma senha forte" required style="padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;">
        </div>

        <button type="submit" class="btn-primary" style="padding: 12px; background-color: #6366f1; color: #fff; border: none; border-radius: 6px; font-weight: bold; font-size: 1rem; cursor: pointer; margin-top: 10px;">
            Cadastrar e Entrar
        </button>
    </form>

    <div class="auth-footer" style="text-align: center; margin-top: 20px; font-size: 0.9rem; color: #666;">
        Já possui uma conta? <a href="login.php" style="color: #6366f1; text-decoration: none; font-weight: 600;">Faça Login</a>
    </div>
</div>

<?php
// Inclui o rodapé padrão da aplicação
include 'includes/footer.php';
?>