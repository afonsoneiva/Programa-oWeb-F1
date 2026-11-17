<?php
namespace app\controllers;

use app\core\Controller;

class SobreController extends Controller {

    public function index() {
        $data = [
            'titulo' => 'Sobre o Projeto',
            'autores' => [
                ['nome' => 'Afonso Neiva',  'numero' => '33104', 'curso' => 'Eng. Computação Gráfica e Multimédia'],
                ['nome' => 'André Freitas', 'numero' => '33782', 'curso' => 'Eng. Computação Gráfica e Multimédia'],
                ['nome' => 'Diogo Viana',   'numero' => '34504', 'curso' => 'Eng. Computação Gráfica e Multimédia'],
            ],
            'funcionalidades' => [
                'Gestão completa (CRUD) de pilotos, construtores e circuitos',
                'Filtros por atributo e pesquisa com texto livre em todas as listagens',
                'Relações entre entidades (piloto ↔ equipa, circuito ↔ vencedor)',
                'Criação de conta e autenticação com sessões (utilizadores gerem pilotos e equipas e eliminam os que criaram; administradores gerem também os circuitos e podem eliminar qualquer registo)',
                'Proteção da integridade dos dados: não é possível eliminar registos com vitórias, pilotos associados ou vencedores registados',
                'Favoritos de pilotos, equipas e circuitos por utilizador',
                'Interface responsiva, adaptada a computador, tablet e telemóvel',
            ],
            'tecnologias' => ['PHP (POO)', 'MVC', 'MySQL / MySQLi', 'HTML5', 'CSS3', 'Bootstrap 5', 'JavaScript', 'Apache (.htaccess)'],
        ];

        $this->view('sobre/index', $data);
    }
}
?>
