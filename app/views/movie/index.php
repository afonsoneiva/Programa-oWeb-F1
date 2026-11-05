<h2>Lista de Filmes</h2>
<a href="<?php echo $url_alias;?>/movie/create">NOVO</a>

<?php
// debug
//print_r($data);

if (isset($data['info']) && isset($data['type'])) {
  $type = $data['type'];
  switch ($type) {
    case 'INSERT':
      echo '<h3>Filme - ' . $data['info']['title'] . ' - inserido com sucesso.</h3>';
      break;
    case 'UPDATE':
      echo '<h3>A informação do filme - ' . $data['info'][0] . ' - foi atualizada.</h3>';
      break;
    case 'DELETE':
      echo '<h3>O filme - ' . $data['info']['title'] . ' - foi eliminado.</h3>';
      break;
  }
}
?>

<ul>
  <?php foreach ($data['movies'] as $movie) { ?>
    <li>
      <strong><?php echo $movie['title']; ?></strong>
      <a href="<?php echo $url_alias;?>/movie/get/<?php echo $movie['id'];?>">Ver +</a> | 
      <a href="<?php echo $url_alias;?>/movie/update/<?php echo $movie['id'];?>">Editar</a> | 
      <a href="<?php echo $url_alias;?>/movie/delete/<?php echo $movie['id'];?>">Eliminar</a>
    </li>
  <?php } ?>
</ul>