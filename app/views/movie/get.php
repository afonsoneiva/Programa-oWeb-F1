<pre>
<?php 
// debug
// print_r($data);
?>
</pre>

<?php
if (count($data['movies']) == 0) {
?>
  <h1>O filme não existe na nossa base de dados...</h1>
<?php 
} else {
  $idGenero = $data['movies'][0]['genres_id'];
  $genero = array_filter($data['genres'], function ($genre) use ($idGenero) {
      return $genre['id'] == $idGenero;
  });
  $values = array_values($genero);
  $genero_descr = $values[0]['genre'];
?>

  <div>
  <?php
  echo "Nome: " . $data['movies'][0]['title'];
  ?>
  </div>

  <div>
  <?php
  echo "IMDB: " . $data['movies'][0]['imdb_rating'];
  ?>
  </div>

  <div>
  <?php
  echo "Ano: " . $data['movies'][0]['release_year'];
  ?>
  </div>

  <div>
  <?php
  echo "Género: " . $genero_descr;
  ?>
  </div>
<?php 
}
?>
<a href="<?php echo $url_alias;?>/movie">Voltar</a>