<?php
namespace app\core;

class App {
  private $controller;
  private $method;
  private $params;

  public function __construct() {
    $this->controller = 'Home';
    $this->method = 'index';
    $this->params = [];

    $url = $this->parseURL();

    // 1. Verificar se existe o Controlador
    if (isset($url[0]) && !empty($url[0])) {
      $controllerName = ucfirst($url[0]);
      if (file_exists('app/controllers/' . $controllerName . '.php')) {
        $this->controller = $controllerName;
        unset($url[0]);
      }
    }

    $controllerClass = 'app\\controllers\\' . $this->controller;
    require_once 'app/controllers/' . $this->controller . '.php';
    
    if (class_exists($controllerClass)) {
      $this->controller = new $controllerClass();
    } else {
      $this->controller = new $this->controller();
    }

    // 2. Verificar se existe o Método
    if (isset($url[1]) && !empty($url[1])) {
      if (method_exists($this->controller, $url[1])) {
        $this->method = $url[1];
        unset($url[1]);
      }
    }

    // 3. Capturar Parâmetros
    $this->params = $url ? array_values($url) : [];

    // 4. Executar Controlador e Método
    call_user_func_array([$this->controller, $this->method], $this->params);
  }

  /**
  * Lê o URL a partir do $_GET['url'] (redirecionado pelo .htaccess)
  * ou dividindo a barra de endereço
  */
  private function parseURL() {
    if (isset($_GET['url'])) {
      return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
    }

    // Fallback manual para servidores sem a variável $_GET['url']
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $script = dirname($_SERVER['SCRIPT_NAME']);
    
    if (strpos($uri, $script) === 0) {
      $uri = substr($uri, strlen($script));
    }
    
    $trimmed = trim($uri, '/');
    return !empty($trimmed) ? explode('/', filter_var($trimmed, FILTER_SANITIZE_URL)) : [];
  }
}
?>