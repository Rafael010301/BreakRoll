<?php
require_once 'includes/conexao.php';
session_start();

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($email) || empty($senha)) {
        $erro = "Preencha todos os campos!";
    } else {
        try {
            // Busca o usuário no banco pelo e-mail
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email_user = :email");
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch();

            // Verifica se o usuário existe e se a senha bate com o hash
            if ($usuario && password_verify($senha, $usuario['senha_user'])) {
                // Cria as variáveis de sessão
                $_SESSION['usuario_id'] = $usuario['id_user'];
                $_SESSION['usuario_nome'] = $usuario['nome_user'];

                // Redireciona para o catálogo após logar
                header("Location: catalogo.php");
                exit;
            } else {
                $erro = "E-mail ou senha inválidos.";
            }
        } catch (PDOException $e) {
            $erro = "Erro interno no sistema. Tente novamente mais tarde.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - BreakRoll</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
    <main class="container">
        <h2>Entrar na Plataforma</h2>
        <?php if (!empty($erro)): ?>
            <p style="color: red;"><?= $erro ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label>E-mail:</label>
            <input type="email" name="email" required>
            
            <label>Senha:</label>
            <input type="password" name="senha" required>
            
            <button type="submit">Entrar</button>
        </form>
        <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
        <p><a href="recuperacao.php">Esqueceu a senha?</a></p>
    </main>
</body>
</html>