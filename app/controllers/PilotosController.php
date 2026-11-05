<?php
namespace app\controllers;

use app\core\Controller;

class PilotosController extends Controller {

    public function index() {
        $pilotosModel = $this->model('Pilotos');
        $construtoresModel = $this->model('Construtores');
        $construtorId = $this->queryString('construtor_id');
        $validatedConstrutorId = $construtorId === '' ? null : $this->validId($construtorId);
        $filters = [
            'q' => $this->queryString('q'),
            'nacionalidade' => $this->queryString('nacionalidade'),
            'construtor_id' => $validatedConstrutorId ?? ''
        ];
        $data = [
            'titulo' => 'Grelha de Pilotos 2025',
            'pilotos' => $pilotosModel->getAll($filters),
            'filters' => $filters,
            'construtores' => $construtoresModel->getAll(),
            'success' => $this->successMessage()
        ];

        $this->view('pilotos/index', $data);
    }

    public function create() {
        $this->showForm(null);
    }

    public function update($id) {
        $id = $this->validId($id);
        $pilotosModel = $this->model('Pilotos');
        $piloto = $id === null ? null : $pilotosModel->getById($id);
        if (!$piloto) {
            $this->pageNotFound();
            return;
        }

        $this->showForm($id, $piloto);
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Método não permitido.';
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numero = filter_var($_POST['numero'] ?? null, FILTER_VALIDATE_INT);
            $construtorIdRaw = $_POST['construtor_id'] ?? '';
            $construtorId = $construtorIdRaw === '' ? null : $this->validId($construtorIdRaw);
            $piloto = [
                'id' => $id,
                'nome' => $this->postedString('nome'),
                'numero' => $numero === false ? '' : $numero,
                'nacionalidade' => $this->postedString('nacionalidade'),
                'construtor_id' => $construtorId
            ];

            if ($piloto['nome'] === '') {
                $errors[] = 'Nome é obrigatório.';
            } elseif ($this->stringLength($piloto['nome']) > 100) {
                $errors[] = 'O nome não pode exceder 100 caracteres.';
            }
            if ($numero === false || $numero < 0) {
                $errors[] = 'Indica um número de piloto válido.';
            }
            if ($piloto['nacionalidade'] === '') {
                $errors[] = 'Nacionalidade é obrigatória.';
            } elseif ($this->stringLength($piloto['nacionalidade']) > 50) {
                $errors[] = 'A nacionalidade não pode exceder 50 caracteres.';
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
            'action' => '/Programa-oWeb-F1/pilotos/' . ($id === null ? 'create' : 'update/' . $id)
        ]);
    }

    private function successMessage() {
        $messages = [
            'created' => 'Piloto adicionado com sucesso.',
            'updated' => 'Piloto atualizado com sucesso.',
            'deleted' => 'Piloto eliminado com sucesso.'
        ];
        $success = $_GET['success'] ?? '';
        return is_string($success) ? ($messages[$success] ?? null) : null;
    }
}
?>