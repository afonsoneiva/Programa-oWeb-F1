<?php
namespace app\controllers;

use app\core\Controller;

class Home extends Controller {
  // invocação da view index.php de /home
  public function index() {
    $data = [
            'titulo' => 'Portal de Fórmula 1 - Época 2025'
        ];
        
        $this->view('home/index', $data);
  }

}
?>