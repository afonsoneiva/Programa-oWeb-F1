<?php
namespace app\controllers;

use app\core\Controller;

class FavoritosController extends Controller {

    public function toggle() {
        $this->requireLogin();
        if (!$this->isPost()) {
            $this->methodNotAllowed();
            return;
        }

        $entidade = $this->postedString('entidade');
        $id = $this->validId($_POST['item_id'] ?? null);
        $models = [
            'pilotos' => 'Pilotos',
            'construtores' => 'Construtores',
            'circuitos' => 'Circuitos'
        ];

        if (!isset($models[$entidade])) {
            $this->pageNotFound();
            return;
        }

        if ($id === null || !$this->model($models[$entidade])->getById($id)) {
            $this->pageNotFound();
            return;
        }

        $utilizadorId = (int) $this->currentUser()['id'];
        $this->model('Favoritos')->toggle($entidade, $utilizadorId, $id);
        $this->redirect($entidade);
    }
}
