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
  /** Ligação partilhada por todas as instâncias (uma só ligação por pedido). */
  private static $sharedConn = null;

  private $conn;

  public function __construct() {
    if (self::$sharedConn === null) {
      // Credenciais definidas em app/config.php
      self::$sharedConn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
      self::$sharedConn->set_charset("utf8mb4");
    }
    $this->conn = self::$sharedConn;
  }

  /**
  * Método para a definição dos parâmetros para o prepared statement
  * @param   \mysqli_stmt $stmt    query "preparada"
  * @param   array        $parameters  array com tipos e respetivos valores (caso existam)
  */
  private function setParameters($stmt, array $parameters) {
    if (count($parameters) >= 2) {
      $types = $parameters[0];
      $values = $parameters[1];
      $stmt->bind_param($types, ...$values);
    }
  }

  /**
  * Extrai o nome da tabela a partir de uma query SQL (INSERT/DELETE)
  */
  private function extractTableName(string $sql): string {
    $sqlTrim = trim($sql);
    if (preg_match('/INTO\s+([^\s\(]+)/i', $sqlTrim, $matches)) {
      return trim($matches[1], '`');
    }
    if (preg_match('/FROM\s+([^\s\(]+)/i', $sqlTrim, $matches)) {
      return trim($matches[1], '`');
    }
    return '';
  }

  private function executeStatement($stmt) {
    if (!$stmt->execute()) {
      throw new \RuntimeException("Erro na execução da query SQL: " . $stmt->error);
    }
  }

  /**
  * Método genérico para a execução do SQL
  * @param   string   $sql         instrução SQL
  * @param   array    $parameters  array de parâmetros ['tipos', [valores]]
  * @param   string   $table       (opcional) nome da tabela para suporte a INSERT/DELETE
  *
  * @return  mixed    $response    dataset ou registo manipulado
  */
  public function execQuery(string $sql, array $parameters = [], string $table = '') {
      $stmt = $this->conn->prepare($sql);
      
      if (!$stmt) {
        throw new \RuntimeException("Erro na preparação da query SQL: " . $this->conn->error);
      }

      $this->setParameters($stmt, $parameters);

      $sqlUpper = strtoupper(trim($sql));

      if (stripos($sqlUpper, 'SELECT') === 0) { 
          $this->executeStatement($stmt);
          $response = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
      } elseif (stripos($sqlUpper, 'INSERT') === 0) { 
          $this->executeStatement($stmt);
          $lastId = $this->conn->insert_id;
          $targetTable = !empty($table) ? $table : $this->extractTableName($sql);
          
          if (!empty($targetTable) && $lastId > 0) {
            $result = $this->execQuery("SELECT * FROM {$targetTable} WHERE id = ?", ['i', [$lastId]]); 
            $response = !empty($result) ? $result[0] : null;
          } else {
            $response = ['id' => $lastId];
          }
      } elseif (stripos($sqlUpper, 'UPDATE') === 0) { 
          $this->executeStatement($stmt);
          $response = isset($parameters[1]) ? $parameters[1] : true;
      } elseif (stripos($sqlUpper, 'DELETE') === 0) {
          $targetTable = !empty($table) ? $table : $this->extractTableName($sql);
          $id = isset($parameters[1][0]) ? $parameters[1][0] : null;
          
          $deletedData = null;
          if (!empty($targetTable) && $id !== null) {
            $deletedData = $this->execQuery("SELECT * FROM {$targetTable} WHERE id = ?", ['i', [$id]]);
          }
          
          $this->executeStatement($stmt);
          $response = (!empty($deletedData)) ? $deletedData[0] : false;
      } else {
          $this->executeStatement($stmt);
          $response = true;
      }

      $stmt->close();
      return $response;
  }
}
?>