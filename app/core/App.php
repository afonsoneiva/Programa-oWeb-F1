<?php
namespace app\core;

/**
 * Router da aplicação.
 * Converte o URL em controlador/método/parâmetros:
 *   /pilotos/update/5  ->  PilotosController->update(5)
 */
class App {
  private $controller = 'HomeController';
  private $method = 'index';
  private $params = [];

  public function __construct() {
    $url = $this->parseURL();

    // 1. Determinar o controlador
    if (!empty($url[0])) {
      $controller = $this->controllerName($url[0]);
      if ($controller === null) {
        (new Controller())->pageNotFound();
        return;
      }
      $this->controller = $controller;
      unset($url[0]);
    }

    $controllerClass = 'app\\controllers\\' . $this->controller;
    $this->controller = new $controllerClass();

    // 2. Determinar o método (apenas métodos públicos)
    if (!empty($url[1])) {
      if (!is_callable([$this->controller, $url[1]])) {
        $this->controller->pageNotFound();
        return;
      }
      $this->method = $url[1];
      unset($url[1]);
    }

    // 3. Parâmetros restantes
    $this->params = $url ? array_values($url) : [];

    // 4. Executar
    call_user_func_array([$this->controller, $this->method], $this->params);
  }

  /**
   * Nome da classe do controlador para um segmento do URL (aceita
   * singular ou plural: /piloto e /pilotos), ou null se não existir.
   */
  private function controllerName(string $segmento) {
    if (!preg_match('/^[A-Za-z]+$/', $segmento)) {
      return null;
    }
    $segmento = ucfirst(strtolower($segmento));
    foreach ([$segmento, $segmento . 's'] as $nome) {
      if (file_exists('app/controllers/' . $nome . 'Controller.php')) {
        return $nome . 'Controller';
      }
    }
    return null;
  }

  /**
   * Lê o URL a partir de $_GET['url'] (definido pelo .htaccess).
   */
  private function parseURL(): array {
    $url = $_GET['url'] ?? '';
    if (!is_string($url) || $url === '') {
      return [];
    }
    return explode('/', filter_var(trim($url, '/'), FILTER_SANITIZE_URL));
  }
}
