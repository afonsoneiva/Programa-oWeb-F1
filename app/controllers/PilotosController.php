<?php
namespace app\controllers;

use app\core\Controller;

class PilotosController extends Controller {

    public function index() {
        $pilotosModel = $this->model('Pilotos');
        $listaPilotos = $pilotosModel->getAll();

        $data = [
            'titulo' => 'Grelha de Pilotos 2025',
            'pilotos' => $listaPilotos
        ];

        $this->view('pilotos/index', $data);
    }
}
?>