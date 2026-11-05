<?php
namespace app\models;
use app\core\Db;

class Genres {

  public static function getAllGenres() {
    $conn = new Db();
    $response = $conn->execQuery('SELECT id, genre FROM genres');
    return $response;
  }

}
?>
