<?php
namespace app\core;

/**
 * Classe base que instancia um model e invoca uma view
 */
class Controller {

  /**
   * Método para a invocação de um model.
   */
  public function model($model) {
    require_once 'app/models/' . $model . '.php';
    $classe = 'app\\models\\' . $model;
    return new $classe();
  }

  /**
   * Método para a invocação de uma view.
   */
  public function view(string $view, $data = []) {
    $url_alias = '/Programa-oWeb-F1';
    $ficheiroView = 'app/views/' . $view . '.php';

    if (file_exists($ficheiroView)) {
      require_once $ficheiroView;
    } else {
      $this->pageNotFound();
    }
  }

  /**
   * Método invocado quando uma vista ou rota não é encontrada.
   */
  public function pageNotFound() {
    $url_alias = '/Programa-oWeb-F1';
    if (file_exists('app/views/erro404.php')) {
      require_once 'app/views/erro404.php';
    } else {
      echo "<h1>404: Página não encontrada...</h1>";
    }
  }
}
?>