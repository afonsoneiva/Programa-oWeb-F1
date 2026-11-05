<?php
// Ativar exibição de erros durante o desenvolvimento
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar a sessão (necessário para autenticação/mensagens)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoload simples de classes com namespaces
spl_autoload_register(function ($classe) {
    $ficheiro = str_replace('\\', '/', $classe) . '.php';
    if (file_exists($ficheiro)) {
        require_once $ficheiro;
    }
});

// Instanciar a aplicação MVC principal
use app\core\App;

$app = new App();
?>