<?php
namespace app\models;

use app\core\Db;

class Construtores {
    private $db;

    public function __construct() {
        $this->db = new Db();
    }

    /**
     * Obtém a lista completa de construtores/equipas
     */
    public function getAll() {
        $sql = "SELECT * FROM construtores ORDER BY id ASC";
        return $this->db->execQuery($sql);
    }
}
?>