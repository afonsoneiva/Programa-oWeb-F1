<?php
//======================================================================
// Configuração da aplicação
//----------------------------------------------------------------------
// Para usar valores diferentes na tua máquina (ex.: MySQL na porta 3307),
// cria o ficheiro app/config.local.php (ignorado pelo git) com, p. ex.:
//
//   <?php
//   return ['DB_PORT' => 3307];
//======================================================================

$config = [
    // Caminho da aplicação no servidor (pasta dentro do htdocs)
    'BASE_URL' => '/Programa-oWeb-F1',

    // Base de dados (MySQLi)
    'DB_HOST' => 'localhost',
    'DB_USER' => 'root',
    'DB_PASS' => '',
    'DB_NAME' => 'pwf1',
    'DB_PORT' => 3306,
];

if (file_exists(__DIR__ . '/config.local.php')) {
    $config = array_merge($config, require __DIR__ . '/config.local.php');
}

foreach ($config as $nome => $valor) {
    define($nome, $valor);
}
