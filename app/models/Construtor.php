<?php
namespace app\models;

use app\core\Db;

class Construtor {
    private $db;

    public function __construct() {
        $this->db = new Db();
    }

    

  public function getAll() {
      $sql = "SELECT FROM construtores ORDER BY id ASC";
      return $this->db->execQuery($sql);}
}
?>