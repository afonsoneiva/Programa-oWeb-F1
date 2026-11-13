<?php
namespace app\controllers;

use app\core\Controller;
use app\core\Geografia;
use app\models\Construtores;

class ConstrutoresController extends Controller {

    public function index() {
        $utilizador = $this->currentUser();
        $filters = [
            'q' => $this->queryString('q'),
            'pais' => $this->queryString('pais'),
            'motor' => $this->queryString('motor')
        ];
        $construtoresModel = $this->model('Construtores');
        $paises = array_values(array_unique(array_merge(
            Geografia::paises(),
            $construtoresModel->distinctOptions('pais')
        )));
        sort($paises, SORT_NATURAL | SORT_FLAG_CASE);

        $this->view('construtores/index', [
            'titulo' => 'Equipas e Construtores 2025',
            'construtores' => array_map(function ($construtor) use ($utilizador) {
                return $construtor + ['motivo_bloqueio' => Construtores::motivoBloqueio($construtor, $utilizador)];
            }, $construtoresModel->getAll($filters)),
            'filters' => $filters,
            'paises' => $paises,
            'motores' => $construtoresModel->distinctOptions('motor'),
            'favoritos' => $utilizador ? $this->model('Favoritos')->getIds('construtores', (int) $utilizador['id']) : [],
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
        $construtor = $id === null ? null : $construtoresModel->getById($id);
        if (!$construtor) {
            $this->pageNotFound();
            return;
        }

        $construtor = $construtoresModel->comContagens($construtor);
        $motivo = Construtores::motivoBloqueio($construtor, $this->currentUser());
        if ($motivo !== null) {
            $this->forbidden($motivo);
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
        $paises = Geografia::incluirValorAtual(Geografia::paises(), $construtor['pais']);

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
            if (!in_array($construtor['pais'], $paises, true)) {
                $errors[] = 'Seleciona um país válido.';
            }

            if (!$errors) {
                if ($id === null) {
                    $construtor['criado_por'] = (int) $this->currentUser()['id'];
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
            'paises' => $paises,
            'errors' => $errors,
            'action' => url('construtores/' . ($id === null ? 'create' : 'update/' . $id))
        ]);
    }
}
