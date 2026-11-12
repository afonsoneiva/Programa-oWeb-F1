<!doctype html>
<html lang="pt">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title><?php echo isset($data['titulo']) ? $data['titulo'] : 'Portal F1'; ?></title>
  </head>
  <body class="bg-light">
    
    <div class="container py-5 text-center">
      <div class="row">
        <div class="col-md-10 mx-auto">
          <h1 class="display-4 fw-bold text-danger mb-3">🏎️ Portal Fórmula 1</h1>
          <p class="lead text-muted">Aplicação web de gestão de dados da temporada de F1 (MVC + MySQLi)</p>
          <hr class="my-4">
          
          <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="<?php echo $url_alias;?>/pilotos" class="btn btn-danger btn-lg px-4">Pilotos</a>
            <a href="<?php echo $url_alias;?>/construtores" class="btn btn-dark btn-lg px-4">Construtores</a>
            <a href="<?php echo $url_alias;?>/circuitos" class="btn btn-secondary btn-lg px-4">Circuitos</a>
          </div>

          <p class="mt-4 mb-0">
            <a href="<?php echo $url_alias;?>/sobre" class="link-secondary">Sobre o projeto e autores</a>
          </p>
        </div>
      </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
</html>