<?php
// includes/auth.php

// Inicia a sessão em todas as páginas que incluírem este arquivo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Função para verificar se o usuário está logado
 */
function verificarAutenticacao() {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: login.php?erro=nao_autorizado");
        exit;
    }
}
?>