<?php
namespace app\core;

/**
* Classe base que instancia um model e invoca uma view
*/
class Controller {

  /**
  * Método para a invocação de um model.
  *
  * @param   string  $model   Model que será instanciado para usar numa view
  */
  public function model($model) {
    require_once 'app/models/' . $model . '.php';
    $classe = 'app\\models\\' . $model;
    return new $classe();
  }

  /**
  * Método para a invocação de uma view (página).
  *
  * @param   string  $view   A view que será invocada (ex: 'pilotos/index')
  * @param   array   $data   Dados a exibir na view
  */
  public function view(string $view, $data = []) {
    $url_alias = '/Programa-oWeb-F1';
    
    if (file_exists('app/views/' . $view . '.php')) {
      require_once 'app/views/' . $view . '.php';
    } else {
      $this->pageNotFound();
    }
  }

  /**
  * Método invocado quando o método ou a view não são encontrados.
  */
  public function pageNotFound() {
    if (file_exists('app/views/erro404.php')) {
      require_once 'app/views/erro404.php';
    } else {
      echo "<h1>Erro 404 - Página Não Encontrada</h1>";
    }
  }
}
?>