<?php
  require './app/load.php';
  use app\core\App; // importação/aliasing com o operador use
                    // a instrução acima é igual a: use app\core\App as App;
                    // a barra \ define o caminho das classes (diferente do caminho dos foicheiros "/")
  use app\core\Controller;
  $app = new App();

//================================================================================
// NOTAS: 
//================================================================================
/*
Autoload function:
_ A técnica acima evita que sejam feitos "requires" para cada uma das classes.
_ O PHP pode fazer o carregamento das classes quando estas são necessárias.
*/
//================================================================================
?>