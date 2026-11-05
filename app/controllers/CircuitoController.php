<?php
namespace app\controllers;

use app\core\Controller;

class CircuitoController extends Controller {

    public function index() {
        $circuitoModel = $this->model('Circuito');
        $listaCircuitos = $circuitoModel->getAll();

        $data = [
            'titulo' => 'Calendário de Circuitos 2025',
            'circuitos' => $listaCircuitos
        ];

        $this->view('circuitos/index', $data);
    }
}
?>