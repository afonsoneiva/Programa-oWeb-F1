<?php
namespace app\controllers;

use app\core\Controller;

class ConstrutoresController extends Controller {

    public function index() {
        $construtoresModel = $this->model('Construtores');
        $listaConstrutores = $construtoresModel->getAll();

        $data = [
            'titulo' => 'Equipas e Construtores 2025',
            'construtores' => $listaConstrutores
        ];

        $this->view('construtores/index', $data);
    }
}
?>
