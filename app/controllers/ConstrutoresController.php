<?php
namespace app\controllers;

use app\core\Controller;

class ConstrutoresController extends Controller {

    public function index() {
        $construtoresModel = $this->model('Construtores');
        $filters = [
            'q' => $this->queryString('q'),
            'pais' => $this->queryString('pais'),
            'motor' => $this->queryString('motor')
        ];
        $data = [
            'titulo' => 'Equipas e Construtores 2025',
            'construtores' => $construtoresModel->getAll($filters),
            'filters' => $filters,
            'success' => $this->successMessage()
        ];

        $this->view('construtores/index', $data);
    }

    public function create() {
        $construtoresModel = $this->model('Construtores');
        $construtor = ['nome' => '', 'pais' => '', 'motor' => ''];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $construtor = [
                'nome' => $this->postedString('nome'),
                'pais' => $this->postedString('pais'),
                'motor' => $this->postedString('motor')
            ];
            $errors = $this->validateConstrutor($construtor);

            if (!$errors) {
                $construtoresModel->create($construtor);
                $this->redirect('construtores?success=created');
            }
        }

        $this->view('construtores/form', [
            'titulo' => 'Adicionar Construtor',
            'construtor' => $construtor,
            'errors' => $errors,
            'action' => '/Programa-oWeb-F1/construtores/create'
        ]);
    }

    public function update($id) {
        $id = $this->validId($id);
        $construtoresModel = $this->model('Construtores');
        $construtor = $id === null ? null : $construtoresModel->getById($id);
        if (!$construtor) {
            $this->pageNotFound();
            return;
        }

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $construtor = [
                'id' => $id,
                'nome' => $this->postedString('nome'),
                'pais' => $this->postedString('pais'),
                'motor' => $this->postedString('motor')
            ];
            $errors = $this->validateConstrutor($construtor);

            if (!$errors) {
                $construtoresModel->update($id, $construtor);
                $this->redirect('construtores?success=updated');
            }
        }

        $this->view('construtores/form', [
            'titulo' => 'Editar Construtor',
            'construtor' => $construtor,
            'errors' => $errors,
            'action' => '/Programa-oWeb-F1/construtores/update/' . $id
        ]);
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Método não permitido.';
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

    private function validateConstrutor(array $construtor): array {
        $errors = [];
        foreach ([
            'nome' => ['Nome', 100],
            'pais' => ['País', 50],
            'motor' => ['Motor', 50]
        ] as $field => [$label, $maxLength]) {
            if ($construtor[$field] === '') {
                $errors[] = $label . ' é obrigatório.';
            } elseif ($this->stringLength($construtor[$field]) > $maxLength) {
                $errors[] = $label . ' excede o limite de ' . $maxLength . ' caracteres.';
            }
        }
        return $errors;
    }

    private function successMessage() {
        $messages = [
            'created' => 'Construtor adicionado com sucesso.',
            'updated' => 'Construtor atualizado com sucesso.',
            'deleted' => 'Construtor eliminado com sucesso.'
        ];
        $success = $_GET['success'] ?? '';
        return is_string($success) ? ($messages[$success] ?? null) : null;
    }
}
?>
