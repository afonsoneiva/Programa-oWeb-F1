<?php
namespace app\controllers;

use app\core\Controller;

class PilotosController extends Controller {

    public function index() {
        $construtorId = $this->queryString('construtor_id');
        $utilizador = $this->currentUser();
        $filters = [
            'q' => $this->queryString('q'),
            'nacionalidade' => $this->queryString('nacionalidade'),
            'construtor_id' => $construtorId === '' ? '' : ($this->validId($construtorId) ?? '')
        ];

        $this->view('pilotos/index', [
            'titulo' => 'Pilotos 2025',
            'pilotos' => $this->model('Pilotos')->getAll($filters),
            'filters' => $filters,
            'construtores' => $this->model('Construtores')->getAll(),
            'favoritos' => $utilizador ? $this->model('Favoritos')->getIds('pilotos', (int) $utilizador['id']) : [],
            'success' => $this->successMessage('Piloto')
        ]);
    }

    public function create() {
        $this->requireLogin();
        $this->showForm(null);
    }

    public function update($id = null) {
        $this->requireLogin();
        $id = $this->validId($id);
        $piloto = $id === null ? null : $this->model('Pilotos')->getById($id);
        if (!$piloto) {
            $this->pageNotFound();
            return;
        }

        $this->showForm($id, $piloto);
    }

    public function delete($id = null) {
        if (!$this->requireAdmin()) {
            return;
        }
        if (!$this->isPost()) {
            $this->methodNotAllowed();
            return;
        }

        $id = $this->validId($id);
        $pilotosModel = $this->model('Pilotos');
        if ($id === null || !$pilotosModel->getById($id)) {
            $this->pageNotFound();
            return;
        }

        $pilotosModel->delete($id);
        $this->redirect('pilotos?success=deleted');
    }

    /**
     * Formulário de criação/edição (GET apresenta, POST valida e grava).
     */
    private function showForm($id, $piloto = null) {
        $pilotosModel = $this->model('Pilotos');
        $construtoresModel = $this->model('Construtores');
        $errors = [];
        $piloto = $piloto ?? [
            'nome' => '',
            'numero' => '',
            'nacionalidade' => '',
            'construtor_id' => null
        ];

        if ($this->isPost()) {
            $numero = filter_var($_POST['numero'] ?? null, FILTER_VALIDATE_INT);
            $construtorIdRaw = $this->postedString('construtor_id');
            $construtorId = $construtorIdRaw === '' ? null : $this->validId($construtorIdRaw);
            $piloto = [
                'id' => $id,
                'nome' => $this->postedString('nome'),
                'numero' => $numero === false ? '' : $numero,
                'nacionalidade' => $this->postedString('nacionalidade'),
                'construtor_id' => $construtorId
            ];

            $errors = $this->validateRequiredStrings($piloto, [
                'nome' => ['Nome', 100],
                'nacionalidade' => ['Nacionalidade', 50]
            ]);
            if ($numero === false || $numero < 0) {
                $errors[] = 'Indica um número de piloto válido.';
            }
            if ($construtorIdRaw !== '' && ($construtorId === null || !$construtoresModel->getById($construtorId))) {
                $errors[] = 'Seleciona um construtor válido.';
            }

            if (!$errors) {
                if ($id === null) {
                    $pilotosModel->create($piloto);
                    $this->redirect('pilotos?success=created');
                }
                $pilotosModel->update($id, $piloto);
                $this->redirect('pilotos?success=updated');
            }
        }

        $this->view('pilotos/form', [
            'titulo' => $id === null ? 'Adicionar Piloto' : 'Editar Piloto',
            'piloto' => $piloto,
            'construtores' => $construtoresModel->getAll(),
            'errors' => $errors,
            'action' => url('pilotos/' . ($id === null ? 'create' : 'update/' . $id))
        ]);
    }
}
