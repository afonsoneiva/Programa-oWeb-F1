<?php
namespace app\controllers;

use app\core\Controller;

class HomeController extends Controller {

    /**
     * Página inicial: números da época, classificações por vitórias e curiosidades.
     */
    public function index() {
        $pilotos = $this->model('Pilotos');
        $construtores = $this->model('Construtores');
        $circuitos = $this->model('Circuitos');
        $registado = $this->queryString('success') === 'registered' && auth_user();

        $this->view('home/index', [
            'titulo' => 'Portal de Fórmula 1 - Época 2025',
            'success' => $registado ? 'Conta criada com sucesso. Bem-vindo, ' . auth_user()['nome'] . '!' : null,
            'totais' => [
                'pilotos' => $pilotos->count(),
                'construtores' => $construtores->count(),
                'circuitos' => $circuitos->count(),
                'km' => $circuitos->kmTotais()
            ],
            'topPilotos' => $circuitos->vitoriasPorPiloto(5),
            'topConstrutores' => $circuitos->vitoriasPorConstrutor(5),
            'curiosidades' => $circuitos->curiosidades()
        ]);
    }

    /**
     * Pesquisa global em pilotos, equipas e circuitos (/home/pesquisa?q=...).
     */
    public function pesquisa() {
        $q = $this->queryString('q');
        if ($q === '') {
            $this->redirect('');
        }

        $filtro = ['q' => $q];
        $resultados = [
            'pilotos' => $this->model('Pilotos')->getAll($filtro),
            'construtores' => $this->model('Construtores')->getAll($filtro),
            'circuitos' => $this->model('Circuitos')->getAll($filtro)
        ];

        $this->view('home/pesquisa', [
            'titulo' => 'Pesquisa: ' . $q,
            'q' => $q,
            'resultados' => $resultados,
            'total' => array_sum(array_map('count', $resultados))
        ]);
    }
}
