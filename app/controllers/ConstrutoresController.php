<?php
namespace app\controllers;

use app\core\Controller;

class ConstrutoresController extends Controller {

    public function index() {
        $filters = [
            'q' => $this->queryString('q'),
            'pais' => $this->queryString('pais'),
            'motor' => $this->queryString('motor')
        ];

        $this->view('construtores/index', [
            'titulo' => 'Equipas e Construtores 2025',
            'construtores' => $this->model('Construtores')->getAll($filters),
            'filters' => $filters,
            'success' => $this->successMessage('Construtor')
        ]);
    }

    public function create() {
        $this->requireLogin();
        $this->showForm(null);
    }

    public function update($id = null) {
        $this->requireLogin();
        $id = $this->validId($id);
        $construtor = $id === null ? null : $this->model('Construtores')->getById($id);
        if (!$construtor) {
            $this->pageNotFound();
            return;
        }

        $this->showForm($id, $construtor);
    }

    public function delete($id = null) {
        $this->requireLogin();
        if (!$this->isPost()) {
            $this->methodNotAllowed();
            return;
        }

        $id = $this->validId($id);
        $construtoresModel = $this->model('Construtores');
        if ($id === null || !$construtoresModel->getById($id)) {
            $this->pageNotFound();
            return;
        }

        $construtoresModel->delete($id);
        $this->redirect('construtores?success=deleted');
    }

    /**
     * Formulário de criação/edição (GET apresenta, POST valida e grava).
     */
    private function showForm($id, $construtor = null) {
        $construtoresModel = $this->model('Construtores');
        $errors = [];
        $construtor = $construtor ?? ['nome' => '', 'pais' => '', 'motor' => ''];

        if ($this->isPost()) {
            $construtor = [
                'id' => $id,
                'nome' => $this->postedString('nome'),
                'pais' => $this->postedString('pais'),
                'motor' => $this->postedString('motor')
            ];
            $errors = $this->validateRequiredStrings($construtor, [
                'nome' => ['Nome', 100],
                'pais' => ['País', 50],
                'motor' => ['Motor', 50]
            ]);

            if (!$errors) {
                if ($id === null) {
                    $construtoresModel->create($construtor);
                    $this->redirect('construtores?success=created');
                }
                $construtoresModel->update($id, $construtor);
                $this->redirect('construtores?success=updated');
            }
        }

        $this->view('construtores/form', [
            'titulo' => $id === null ? 'Adicionar Construtor' : 'Editar Construtor',
            'construtor' => $construtor,
            'errors' => $errors,
            'action' => url('construtores/' . ($id === null ? 'create' : 'update/' . $id))
        ]);
    }
}
