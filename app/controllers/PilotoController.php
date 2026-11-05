<?php
namespace app\controllers;

use app\core\Controller;

class PilotoController extends Controller {

    public function index() {
        $pilotoModel = $this->model('Piloto');
        $listaPilotos = $pilotoModel->getAll();

        $data = [
            'titulo' => 'Grelha de Pilotos 2025',
            'pilotos' => $listaPilotos
        ];

        $this->view('pilotos/index', $data);
    }
}
?>