<?php
/* ======================================================================
Esta classe é responsável por:
> Obtenção do URL
> Divide-o pelo /
> Cria um array (pela divisão do URL pelo /)
  - Controller a instanciar
  - Método a invocar (do controller instanciado)
  - Parâmetros do método
====================================================================== */
namespace app\core;

class App {
  private $controller;
  private $method;
  private $params;
  private $URLArray;
  private $startIndexFromUrl;
  private $pageNotFound;

  public function __construct() {
    $this->controller = 'Home';
    $this->method = 'index';
    $this->params = [];
    $this->pageNotFound = false;
    
    $this->parseURL();
    $this->calculateStartIndex();
    $this->setController();
    $this->setMethodFromUrl();
    $this->setParamsFromUrl();
    
    // Invocação dinâmica do controlador e método
    call_user_func_array([$this->controller, $this->method], $this->params);
  }

  /**
  * Método responsável pela manipulação do URL
  */
  private function parseURL() {
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $trimmed = trim($requestUri, '/');
    $this->URLArray = !empty($trimmed) ? explode('/', $trimmed) : [];
  }

  /**
  * Calcula automaticamente onde começa a rota do projeto
  * ignorando as pastas do servidor local (ex: XAMPP htdocs)
  */
  private function calculateStartIndex() {
    $this->startIndexFromUrl = 0;
    
    // Se estiver a correr num subdiretório (ex: /PW_trabalhof1/Programa-oweb-F1/)
    foreach ($this->URLArray as $index => $segment) {
      if (file_exists('app/controllers/' . ucfirst($segment) . '.php')) {
        $this->startIndexFromUrl = $index;
        return;
      }
    }

    // Caso não encontre um controller no URL, calcula pela profundidade de ficheiros
    $scriptName = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));
    array_pop($scriptName); // Remove 'index.php'
    $this->startIndexFromUrl = count($scriptName);
  }

  /**
  * Método que instancia o controlador
  */
  private function setController() {
    $index = $this->startIndexFromUrl;
    
    if (isset($this->URLArray[$index]) && !empty($this->URLArray[$index])) {
      $controllerCandidate = ucfirst($this->URLArray[$index]);
      if (file_exists('app/controllers/' . $controllerCandidate . '.php')) {
        $this->controller = $controllerCandidate;
      } else {
        $this->pageNotFound = true;
      }
    }

    $controllerClass = 'app\\controllers\\' . $this->controller;
    
    if (file_exists('app/controllers/' . $this->controller . '.php')) {
      require_once('app/controllers/' . $this->controller . '.php');
      if (class_exists($controllerClass) || class_exists($this->controller)) {
        $classToInstantiate = class_exists($controllerClass) ? $controllerClass : $this->controller;
        $this->controller = new $classToInstantiate();
        return;
      }
    }
    
    // Se não encontrar o ficheiro ou classe do controlador
    $this->controller = new Controller();
    $this->method = 'pageNotFound';
  }

  /**
  * Método que define o método a executar dentro do controlador
  */
  private function setMethodFromUrl() {
    $index = $this->startIndexFromUrl + 1;
    
    if (isset($this->URLArray[$index]) && !empty($this->URLArray[$index])) {
      $methodCandidate = $this->URLArray[$index];
      if (method_exists($this->controller, $methodCandidate) && !$this->pageNotFound) {
        $this->method = $methodCandidate;
      } else {
        $this->method = 'pageNotFound';
      }
    }
  }

  /**
  * Método que captura os parâmetros adicionais do URL
  */
  private function setParamsFromUrl() {
    $index = $this->startIndexFromUrl + 2;
    if (count($this->URLArray) >= $index) {
      $this->params = array_slice($this->URLArray, $index);
    }
  }
}
?>