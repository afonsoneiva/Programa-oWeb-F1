<?php
namespace app\models;

use app\core\Db;

class Circuito {
    private $db;

    public function __construct() {
        $this->db = new Db();
    }

    /**
     * Retorna todos os circuitos com informações do piloto e construtor vencedores.
     *
     * @return array
     */ 

  public function getAll() {
      $sql = "SELECT c., 
                     p.nome AS piloto_vencedor, 
                     co.nome AS construtor_vencedor
              FROM circuitos c
              LEFT JOIN pilotos p ON c.piloto_vencedor_id = p.id
              LEFT JOIN construtores co ON c.construtor_vencedor_id = co.id
              ORDER BY c.id ASC";
      return $this->db->execQuery($sql);}
}
?>