<?php

namespace app\models;

use app\core\Db;
class Movies {
  /**
  * Método para obtenção do dataset de todos os filmes
  *
  * @return   array
  */
  public static function getAllMovies() {
    $conn = new Db();
    $response = $conn->execQuery('SELECT id, title, imdb_rating, release_year FROM movies');
    return $response;
  }

  /**
  * Método para a obtenção de um filme pelo id correspondente
  * @param    int     $id   Id. do filme
  *
  * @return   array
  */
  public static function findMovieById(int $id) {
    $conn = new Db();
    $response = $conn->execQuery('SELECT id, title, imdb_rating, release_year, genres_id FROM movies WHERE id = ?', array('i', array($id)));
    return $response;
  }

  public static function addMovie($data) {
    $conn = new Db();
    $response = $conn->execQuery('INSERT INTO movies (title, imdb_rating, release_year, genres_id) VALUES (?, ?, ?, ?)', array(
      'ssii',
      array($data['title'], $data['imdb_rating'], $data['release_year'], $data['genres_id'])
    ));
    return $response;
  }

  public static function updateMovie($id, $data) {
    $conn = new Db();
    $response = $conn->execQuery('UPDATE movies SET title = ?, imdb_rating = ?, release_year = ?, genres_id = ? WHERE id = ?', array(
      'ssiii',
      array($data['title'], $data['imdb_rating'], $data['release_year'], $data['genres_id'], $id)
    ));
    return $response;
  }

  public static function deleteMovie($id) {
    $conn = new Db();
    $response = $conn->execQuery('DELETE FROM movies WHERE id = ?', array(
      'i', array($id)
    ));
    return $response;
  }

  

}