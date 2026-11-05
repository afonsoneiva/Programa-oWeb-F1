<h2>Criar Novo Filme</h2>

<form action="<?php echo $url_alias;?>/movie/create" method="POST">
  <label for="title">Título:</label>
  <input type="text" id="title" name="title" required><br>

  <label for="imdb_rating">IMDB Rating:</label>
  <input type="number" step="0.1" id="imdb_rating" name="imdb_rating" required><br>

  <label for="release_year">Ano de Lançamento:</label>
  <input type="number" id="release_year" name="release_year" required><br>

  <label for="genres_id">Género:</label>
  <select id="genres_id" name="genres_id" required>
    <?php foreach ($data['genres']  as $genre) { ?>
      <option value="<?php echo $genre['id']; ?>"><?php echo $genre['genre']; ?></option>
    <?php } ?>
  </select><br>

  <button type="submit">Criar Filme</button>
</form>
<a href="<?php echo $url_alias;?>/movie">Voltar</a>


