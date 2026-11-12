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
                'Autenticação com sessões para proteger a criação, edição e remoção de dados',
                'Interface responsiva, adaptada a computador, tablet e telemóvel',
            ],
            'tecnologias' => ['PHP (POO)', 'MVC', 'MySQL / MySQLi', 'HTML5', 'CSS3', 'Bootstrap 5', 'JavaScript', 'Apache (.htaccess)'],
        ];

        $this->view('sobre/index', $data);
    }
}
?>
