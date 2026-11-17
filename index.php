<?php
//======================================================================
// Ponto de entrada único da aplicação (front controller).
// O .htaccess encaminha todos os pedidos para aqui: index.php?url=...
//======================================================================

// Os erros são registados no log do servidor, mas não mostrados ao visitante
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Lançar exceções nos erros do MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Sessão (autenticação)
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,   // cookie inacessível a JavaScript
        'samesite' => 'Lax'   // não é enviado em pedidos POST vindos de outros sites
    ]);
    ini_set('session.use_strict_mode', 1);
    session_start();
}

require_once 'app/config.php';
require_once 'app/core/helpers.php';

// Autoload de classes: app\models\Pilotos -> app/models/Pilotos.php
spl_autoload_register(function ($classe) {
    $ficheiro = str_replace('\\', '/', $classe) . '.php';
    if (file_exists($ficheiro)) {
        require_once $ficheiro;
    }
});

// Executar o pedido. Qualquer erro inesperado (ex.: base de dados
// indisponível) é registado no log e mostrado como uma página de erro 500.
ob_start();
try {
    new app\core\App();
    ob_end_flush();
} catch (Throwable $erro) {
    ob_end_clean();
    error_log((string) $erro);
    http_response_code(500);
    (new app\core\Controller())->view('erro', [
        'titulo' => 'Erro no servidor',
        'mensagem' => 'Ocorreu um erro inesperado. Tenta novamente dentro de alguns instantes.'
    ]);
}
