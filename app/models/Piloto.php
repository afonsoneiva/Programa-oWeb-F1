<?php
namespace app\models;

use app\core\Db;

class Piloto {
    private $db;

    public function __construct() {
        $this->db = new Db();
    }

    public function getAll() {
        $sql = "SELECT p.*, c.nome AS nome_construtor 
                FROM pilotos p 
                LEFT JOIN construtores c ON p.construtor_id = c.id 
                ORDER BY p.id ASC";
        return $this->db->execQuery($sql);
    }
}
?>