<?php
namespace app\controllers;

use app\core\Controller;

class HomeController extends Controller {

    public function index() {
        $registado = $this->queryString('success') === 'registered' && auth_user();

        $this->view('home/index', [
            'titulo' => 'Portal de Fórmula 1 - Época 2025',
            'success' => $registado ? 'Conta criada com sucesso. Bem-vindo, ' . auth_user()['nome'] . '!' : null
        ]);
    }
}
