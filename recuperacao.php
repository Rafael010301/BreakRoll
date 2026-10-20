<?php
require_once 'includes/conexao.php';
require_once 'config.php';
require_once 'includes/auth.php';
verificarAutenticacao();

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Verifica se o e-mail existe no banco
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email_user = :email");
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        // Aqui você poderia gerar um token de redefinição ou enviar um e-mail via Resend
        $mensagem = "Se o e-mail estiver cadastrado, as instruções de recuperação foram enviadas.";
    } else {
        $mensagem = "Se o e-mail estiver cadastrado, as instruções de recuperação foram enviadas."; // Mensagem genérica por segurança
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recuperação de Senha</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <main class="container">
        <h2>Recuperar Senha</h2>
        <p><?= $mensagem ?></p>
        <form action="recuperacao.php" method="POST">
            <label>Informe seu e-mail cadastrado:</label>
            <input type="email" name="email" required>
            <button type="submit">Enviar</button>
        </form>
        <p><a href="login.php">Voltar para o Login</a></p>
    </main>
</body>
</html>