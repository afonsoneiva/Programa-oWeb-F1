<?php
namespace app\controllers;

use app\core\Controller;

class CircuitosController extends Controller {

    public function index() {
        $utilizador = $this->currentUser();
        $filters = [
            'q' => $this->queryString('q'),
            'grande_premio' => $this->queryString('grande_premio'),
            'pais' => $this->queryString('pais'),
            'cidade' => $this->queryString('cidade')
        ];

        $this->view('circuitos/index', [
            'titulo' => 'Calendário de Circuitos 2025',
            'circuitos' => $this->model('Circuitos')->getAll($filters),
            'filters' => $filters,
            'favoritos' => $utilizador ? $this->model('Favoritos')->getIds('circuitos', (int) $utilizador['id']) : [],
            'success' => $this->successMessage('Circuito')
        ]);
    }

    public function create() {
        $this->requireLogin();
        $this->showForm(null);
    }

    public function update($id = null) {
        if (!$this->requireAdmin()) {
            return;
        }
        $id = $this->validId($id);
        $circuito = $id === null ? null : $this->model('Circuitos')->getById($id);
        if (!$circuito) {
            $this->pageNotFound();
            return;
        }

        $this->showForm($id, $circuito);
    }

    /**
     * Formulário de criação/edição (GET apresenta, POST valida e grava).
     */
    private function showForm($id, $circuito = null) {
        $circuitosModel = $this->model('Circuitos');
        $pilotosModel = $this->model('Pilotos');
        $construtoresModel = $this->model('Construtores');
        $errors = [];
        $circuito = $circuito ?? [
            'nome' => '',
            'grande_premio' => '',
            'pais' => '',
            'cidade' => '',
            'extensao_km' => '',
            'num_voltas' => '',
            'piloto_vencedor_id' => null,
            'construtor_vencedor_id' => null
        ];

        if ($this->isPost()) {
            $numeroVoltas = filter_var($_POST['num_voltas'] ?? null, FILTER_VALIDATE_INT);
            $pilotoIdRaw = $this->postedString('piloto_vencedor_id');
            $construtorIdRaw = $this->postedString('construtor_vencedor_id');
            $pilotoId = $pilotoIdRaw === '' ? null : $this->validId($pilotoIdRaw);
            $construtorId = $construtorIdRaw === '' ? null : $this->validId($construtorIdRaw);
            $circuito = [
                'id' => $id,
                'nome' => $this->postedString('nome'),
                'grande_premio' => $this->postedString('grande_premio'),
                'pais' => $this->postedString('pais'),
                'cidade' => $this->postedString('cidade'),
                'extensao_km' => $this->postedString('extensao_km'),
                'num_voltas' => $numeroVoltas === false ? $this->postedString('num_voltas') : $numeroVoltas,
                'piloto_vencedor_id' => $pilotoId,
                'construtor_vencedor_id' => $construtorId
            ];

            $errors = $this->validateRequiredStrings($circuito, [
                'nome' => ['Nome do circuito', 100],
                'grande_premio' => ['Grande Prémio', 100],
                'pais' => ['País', 50],
                'cidade' => ['Cidade', 50]
            ]);
            if (!preg_match('/^\d+(?:\.\d{1,3})?$/D', $circuito['extensao_km']) || (float) $circuito['extensao_km'] <= 0) {
                $errors[] = 'Indica uma extensão válida superior a zero.';
            } elseif ((float) $circuito['extensao_km'] >= 10) {
                $errors[] = 'A extensão não pode ser superior a 9,999 km.';
            }
            if ($numeroVoltas === false || $numeroVoltas < 1) {
                $errors[] = 'Indica um número de voltas válido.';
            }
            if ($pilotoIdRaw !== '' && ($pilotoId === null || !$pilotosModel->getById($pilotoId))) {
                $errors[] = 'Seleciona um piloto vencedor válido.';
            }
            if ($construtorIdRaw !== '' && ($construtorId === null || !$construtoresModel->getById($construtorId))) {
                $errors[] = 'Seleciona um construtor vencedor válido.';
            }

            if (!$errors) {
                $circuito['extensao_km'] = (float) $circuito['extensao_km'];
                if ($id === null) {
                    $circuitosModel->create($circuito);
                    $this->redirect('circuitos?success=created');
                }
                $circuitosModel->update($id, $circuito);
                $this->redirect('circuitos?success=updated');
            }
        }

        $this->view('circuitos/form', [
            'titulo' => $id === null ? 'Adicionar Circuito' : 'Editar Circuito',
            'circuito' => $circuito,
            'pilotos' => $pilotosModel->getAll(),
            'construtores' => $construtoresModel->getAll(),
            'errors' => $errors,
            'action' => url('circuitos/' . ($id === null ? 'create' : 'update/' . $id))
        ]);
    }
}
