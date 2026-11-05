<?php
namespace app\controllers;

use app\core\Controller;

class CircuitosController extends Controller {

    public function index() {
        $circuitosModel = $this->model('Circuitos');
        $listaCircuitos = $circuitosModel->getAll();

        $data = [
            'titulo' => 'Calendário de Circuitos 2025',
            'circuitos' => $listaCircuitos
        ];

        $this->view('circuitos/index', $data);
    }
}
?>