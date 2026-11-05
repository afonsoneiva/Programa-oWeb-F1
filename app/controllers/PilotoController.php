<?php
use app\core\Controller;
class Movie extends Controller {
  /**
  * Invocação da view index.php
  */
  public function index() {
    $Movies = $this->model('Movies'); // é retornado o model Movies()
    $data = $Movies::getAllMovies();
    /*
    $Movies = new Movies();
    $data = $Movies->getAllMovies();
    ------------------------------------------------------
    $Movies = "Movies";
    $data = $Movies::getAllMovies();
    */
    $this->view('movie/index', ['movies' => $data]);
  }

  /**
  * Invocação da view get.php
  *
  * @param  int   $id   Id. movie
  */
  public function get($id = null) {
    if (is_numeric($id)) {
      $Movies = $this->model('Movies');
      $Genres = $this->model('Genres'); 
      $data = $Movies::findMovieById($id);
      $genres = $Genres::getAllGenres();
      $this->view('movie/get', ['movies' => $data, 'genres' => $genres]);
    } else {
       $this->pageNotFound();
    }
  }

  public function create() {
    $Movies = $this->model('Movies');
    $Genres = $this->model('Genres'); 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $newMovieData = [
        'title' => $_POST['title'],
        'imdb_rating' => $_POST['imdb_rating'],
        'release_year' => $_POST['release_year'],
        'genres_id' => $_POST['genres_id']
      ];
      $info = $Movies::addMovie($newMovieData);

      $data = $Movies::getAllMovies();
      $this->view('movie/index', ['movies' => $data, 'info' => $info, 'type' => 'INSERT']);
    } else {
      $genres = $Genres::getAllGenres();
      $this->view('movie/create', ['genres' => $genres]);
    }
  }

  public function update($id = null) {
    $Movies = $this->model('Movies');
    $Genres = $this->model('Genres');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $updatedMovieData = [
        'title' => $_POST['title'],
        'imdb_rating' => $_POST['imdb_rating'],
        'release_year' => $_POST['release_year'],
        'genres_id' => $_POST['genres_id']
      ];
      $info = $Movies::updateMovie($id, $updatedMovieData);
      
      $data = $Movies::getAllMovies();
      $this->view('movie/index', ['movies' => $data, 'info' => $info, 'type' => 'UPDATE']);
    } else {
      $data = $Movies::findMovieById($id);
      $genres = $Genres::getAllGenres(); 
      $this->view('movie/update', ['movie' => $data, 'genres' => $genres]);
    }
  }

  public function delete($id = null) {
    if (is_numeric($id)) {
      $Movies = $this->model('Movies');
      $info = $Movies::deleteMovie($id);

      $data = $Movies::getAllMovies();
      $this->view('movie/index', ['movies' => $data, 'info' => $info, 'type' => 'DELETE']);
    } else {
      $this->pageNotFound();
    }
  }
}

// :: Scope Resolution Operator
// Utilizado para acesso às propriedades e métodos das classes
?>