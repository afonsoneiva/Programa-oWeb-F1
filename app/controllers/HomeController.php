<?php
namespace app\controllers;

use app\core\Controller;

class HomeController extends Controller {

    public function index() {
        $this->view('home/index', [
            'titulo' => 'Portal de Fórmula 1 - Época 2025'
        ]);
    }
}
