<?php
namespace app\controllers;

use app\core\Controller;

class CircuitosController extends Controller {

    public function index() {
        $circuitosModel = $this->model('Circuitos');
        $filters = [
            'q' => $this->queryString('q'),
            'grande_premio' => $this->queryString('grande_premio'),
            'pais' => $this->queryString('pais'),
            'cidade' => $this->queryString('cidade')
        ];
        $data = [
            'titulo' => 'Calendário de Circuitos 2025',
            'circuitos' => $circuitosModel->getAll($filters),
            'filters' => $filters,
            'success' => $this->successMessage()
        ];

        $this->view('circuitos/index', $data);
    }

    public function create() {
        $this->showForm(null);
    }

    public function update($id) {
        $id = $this->validId($id);
        $circuitosModel = $this->model('Circuitos');
        $circuito = $id === null ? null : $circuitosModel->getById($id);
        if (!$circuito) {
            $this->pageNotFound();
            return;
        }

        $this->showForm($id, $circuito);
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Método não permitido.';
            return;
        }

        $id = $this->validId($id);
        $circuitosModel = $this->model('Circuitos');
        if ($id === null || !$circuitosModel->getById($id)) {
            $this->pageNotFound();
            return;
        }

        $circuitosModel->delete($id);
        $this->redirect('circuitos?success=deleted');
    }

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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numeroVoltas = filter_var($_POST['num_voltas'] ?? null, FILTER_VALIDATE_INT);
            $pilotoIdRaw = $_POST['piloto_vencedor_id'] ?? '';
            $construtorIdRaw = $_POST['construtor_vencedor_id'] ?? '';
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

            foreach ([
                'nome' => ['Nome do circuito', 100],
                'grande_premio' => ['Grande Prémio', 100],
                'pais' => ['País', 50],
                'cidade' => ['Cidade', 50]
            ] as $field => [$label, $maxLength]) {
                if ($circuito[$field] === '') {
                    $errors[] = $label . ' é obrigatório.';
                } elseif ($this->stringLength($circuito[$field]) > $maxLength) {
                    $errors[] = $label . ' excede o limite de ' . $maxLength . ' caracteres.';
                }
            }
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
                $circuito['num_voltas'] = $numeroVoltas;
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
            'action' => '/Programa-oWeb-F1/circuitos/' . ($id === null ? 'create' : 'update/' . $id)
        ]);
    }

    private function successMessage() {
        $messages = [
            'created' => 'Circuito adicionado com sucesso.',
            'updated' => 'Circuito atualizado com sucesso.',
            'deleted' => 'Circuito eliminado com sucesso.'
        ];
        $success = $_GET['success'] ?? '';
        return is_string($success) ? ($messages[$success] ?? null) : null;
    }
}
?>