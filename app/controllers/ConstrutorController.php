<?php
namespace app\controllers;

use app\core\Controller;

class ConstrutorController extends Controller {

    public function index() {
        $construtorModel = $this->model('Construtor');
        $listaConstrutores = $construtorModel->getAll();

        $data = [
            'titulo' => 'Equipas e Construtores 2025',
            'construtores' => $listaConstrutores
        ];

        $this->view('construtores/index', $data);
    }
}
?>
