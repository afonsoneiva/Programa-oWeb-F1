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

    // 1. Determinar o Controlador
    if (isset($url[0]) && !empty($url[0])) {
      $segmento = ucfirst($url[0]);
      
      // Tenta encontrar "PilotoController.php", "PilotosController.php" ou "Home.php"
      if (file_exists('app/controllers/' . $segmento . 'Controller.php')) {
        $this->controller = $segmento . 'Controller';
        unset($url[0]);
      } elseif (file_exists('app/controllers/' . $segmento . '.php')) {
        $this->controller = $segmento;
        unset($url[0]);
      } elseif (substr($segmento, -1) === 's') {
        // Trata o plural (ex: "pilotos" -> procura "PilotoController.php")
        $singular = substr($segmento, 0, -1);
        if (file_exists('app/controllers/' . $singular . 'Controller.php')) {
          $this->controller = $singular . 'Controller';
          unset($url[0]);
        }
      }
    }

    $controllerClass = 'app\\controllers\\' . $this->controller;
    require_once 'app/controllers/' . $this->controller . '.php';

    if (class_exists($controllerClass)) {
      $this->controller = new $controllerClass();
    } else {
      $this->controller = new $this->controller();
    }

    // 2. Determinar o Método
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
  * Lê o URL a partir de $_GET['url'] ou analisa a barra de endereço
  */
  private function parseURL() {
    if (isset($_GET['url']) && !empty($_GET['url'])) {
      return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
    }

    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $segments = array_values(array_filter(explode('/', $requestUri)));

    foreach ($segments as $index => $segment) {
      $segCap = ucfirst($segment);
      $segSingular = (substr($segCap, -1) === 's') ? substr($segCap, 0, -1) : $segCap;

      if (
        file_exists('app/controllers/' . $segCap . 'Controller.php') ||
        file_exists('app/controllers/' . $segCap . '.php') ||
        file_exists('app/controllers/' . $segSingular . 'Controller.php')
      ) {
        return array_slice($segments, $index);
      }
    }

    return [];
  }
}
?>