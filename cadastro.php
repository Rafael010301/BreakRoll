<?php
// Inclui as configurações (onde está a chave da API) e a conexão com o banco
require_once 'config.php';
require_once 'includes/conexao.php';

// Verifica se os dados vieram via POST do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senhaPura = $_POST['senha'];

    // Validação básica se os campos não estão vazios
    if (empty($nome) || empty($email) || empty($senhaPura)) {
        echo "Preencha todos os campos!";
        exit;
    }

    // 1. Criptografa a senha (Exigência do projeto: "A senha não fica em texto puro")[cite: 1]
    $senhaHash = password_hash($senhaPura, PASSWORD_DEFAULT);

    try {
        // 2. Insere o usuário no banco de dados usando Prepared Statement com PDO
        $sql = "INSERT INTO usuarios (nome_user, email_user, senha_user) VALUES (:nome, :email, :senha)";
        $stmt = $pdo->prepare($sql);
        
        // No PDO, passamos os valores associados diretamente no execute()
        $stmt->execute([
            ':nome'  => $nome,
            ':email' => $email,
            ':senha' => $senhaHash
        ]);

        // 3. Dispara o e-mail para o dono do site usando a API do Resend (Exigência do projeto)[cite: 1]
        $dadosEmail = [
            'from'    => 'onboarding@resend.dev', // Domínio de teste padrão do Resend
            'to'      => [DONO_EMAIL],            // Constante definida no config.php
            'subject' => 'Novo cadastro no site de Streaming!',
            'html'    => "<h2>Novo usuário cadastrado!</h2>
                          <p><strong>Nome:</strong> {$nome}</p>
                          <p><strong>E-mail:</strong> {$email}</p>
                          <p>Data/Hora do cadastro: " . date('d/m/Y H:i:s') . "</p>"
        ];

        $ch = curl_init('https://api.resend.com/emails');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dadosEmail));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . RESEND_API_KEY,
            'Content-Type: application/json'
        ]);

        $resposta = curl_exec($ch);
        curl_close($ch);

        // Redireciona para o login informando sucesso
        header("Location: login.php?status=sucesso");
        exit;

    } catch (PDOException $e) {
        // Trata erro de duplicidade (código 23000 no SQLSTATE geralmente indica violação de chave única / e-mail já existente)
        if ($e->getCode() == '23000') {
            echo "Erro ao cadastrar. O e-mail informado já está em uso.";
        } else {
            // Outros erros caem no log geral do sistema ou mostram mensagem genérica
            echo "Ocorreu um erro inesperado ao realizar o cadastro. Tente novamente mais tarde.";
        }
    }
}
?>