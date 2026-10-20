<?php
// config.php

// Sua chave secreta deve vir de variável de ambiente (ex.: RESEND_API_KEY)
define('RESEND_API_KEY', getenv('RESEND_API_KEY') ?: '');

// O e-mail do dono do site (Como o modo de teste só entrega para a própria conta do Resend, use o seu e-mail)
define('DONO_EMAIL', 'rafadrummer1@gmail.com');
?>