<?php
//======================================================================
// namespace:
// Numa definição mais ampla os namespaces são uma forma de encapsulamento de itens.
// Os namespaces em PHP são uma forma de encapsulamento de itens.
// Resolve o problema de colisões:
// _ dentro de um mesmo projeto (de nomes de classes, funções e constantes)
// _ após a importação de bibliotecas/código de "terceiros" 
//   (de nomes de classes, funções e constantes)
//======================================================================
namespace app\core;
use mysqli;
class Db {
  private $DBServer;
  private $DBUser;
  private $DBPass;
  private $DBName;

  private $conn;

  public function __construct() {
    $this->DBServer = 'localhost';
    $this->DBUser   = 'root';
    $this->DBPass   = '';
    $this->DBName   = 'moviesdb';
    $this->conn = new mysqli($this->DBServer, $this->DBUser, $this->DBPass, $this->DBName);
    $this->conn->set_charset("utf8");
  }

  /**
  * Método para a definição dos parâmetros para o prepared statement
  * @param  MySQLiStatement   $stmt         query "preparada".
  * @param  array             $parameters   array com tipos e respetivos valores (caso existam)
  */
  private function setParameters($stmt, array $parameters) {
    if (count($parameters)) {
      $types = $parameters[0];
      $values = $parameters[1];
      $stmt->bind_param($types, ...$values); // *1
    }
  }

  /**
  * Método para a execução do SQL
  * @param  string   $sql         instrução SQL
  * @param  array    $parameters  array de parâmetros
  *
  * @return array    $response    dataset
  */
  
  // precisa de ser mais genérica porque, nesta versão, apenas responde corretamente para operações sobre a tabela "movies"
  public function execQuery(string $sql, array $parameters = []) {
      $stmt = $this->conn->prepare($sql);
      $this->setParameters($stmt, $parameters);
      if (!(stripos(trim($sql), 'DELETE') === 0)) {
        $stmt->execute();
      }

      if (stripos(trim($sql), 'SELECT') === 0) { 
          $response = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
      } elseif (stripos(trim($sql), 'INSERT') === 0) { 
          $lastId = $this->conn->insert_id; // ID do registo inserido
          $result = $this->execQuery('SELECT * FROM movies WHERE id = ?', ['i', [$lastId]]); 
          $response = $result[0];
      } elseif (stripos(trim($sql), 'UPDATE') === 0) { 
          $response = $parameters[1]; // devolve os dados enviados para o execQuery (não há necessidade de ir buscar à BD)
      } elseif (stripos(trim($sql), 'DELETE') === 0) {
          $id = $parameters[1][0]; // id do registo para DELETE
          $deletedData = $this->execQuery('SELECT * FROM movies WHERE id = ?', ['i', [$id]]);
          if (!empty($deletedData)) {
            $response = $deletedData[0];
          } else {
            $response = null;
          }
          $stmt->execute();
      }

      return $response;
  }



  // *1
  // ... Operador splat
  // Uma das funções deste operador é transformar um array em parâmetros separados a passar para
  // determinado método/função (Argument Unpacking)

}