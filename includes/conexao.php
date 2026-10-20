<?php 
$hn = "localhost";
$pwd = "";
$usr = "root";
$db = "break_db";
$dsn = "mysql:host=$hn;dbname=$db;charset=utf8mb4";
try{
    $pdo = new PDO($dsn,$usr,$pwd,[
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}catch(PDOException $e){
    $mensagemLog = "[" . date("Y-m-d H:i:s") . "] Erro PDO: " . $e->getMessage() . PHP_EOL;

        // 2. Salva o erro em um arquivo de log dedicado na pasta do projeto (ex: /logs/db.log)
        // Certifique-se de que a pasta 'logs' existe ou crie-a
        $pastaLogs = __DIR__ . '/../logs';
        if (!is_dir($pastaLogs)) {
            mkdir($pastaLogs, 0777, true);
        }
        error_log($mensagemLog, 3, $pastaLogs . '/db.log');

        // 3. Exibe uma mensagem limpa e amigável para o usuário (User Experience)
        http_response_code(500);
        exit("Desculpe, estamos enfrentando instabilidade técnica no momento. Tente novamente mais tarde.");
}
?>