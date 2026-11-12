<?php
// Secção ativa na navbar (1.º segmento do URL)
$seccaoAtiva = strtolower(explode('/', (string) ($_GET['url'] ?? ''))[0]);
$menu = [
    'pilotos' => 'Pilotos',
    'construtores' => 'Construtores',
    'circuitos' => 'Circuitos',
    'sobre' => 'Sobre',
];
?>
<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($data['titulo'] ?? 'Portal F1'); ?> | Portal F1</title>
    <link rel="stylesheet" href="<?php echo url('assets/bootstrap-5.1.3-dist/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo url('assets/css/main.css'); ?>">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?php echo url(); ?>">🏎️ Portal <span class="text-danger">F1</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal" aria-controls="menuPrincipal" aria-expanded="false" aria-label="Abrir menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav me-auto">
                <?php foreach ($menu as $caminho => $nome): ?>
                    <?php $ativo = $seccaoAtiva === $caminho; ?>
                    <li class="nav-item">
                        <a class="nav-link<?php echo $ativo ? ' active' : ''; ?>" href="<?php echo url($caminho); ?>"<?php echo $ativo ? ' aria-current="page"' : ''; ?>><?php echo e($nome); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <?php if (auth_user()): ?>
                <form action="<?php echo url('auth/logout'); ?>" method="POST" class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                    <?php echo csrf_field(); ?>
                    <span class="navbar-text small">
                        👤 <?php echo e(auth_user()['nome']); ?>
                        <?php if (is_admin()): ?><span class="badge bg-danger ms-1">admin</span><?php endif; ?>
                    </span>
                    <button type="submit" class="btn btn-sm btn-outline-light">Terminar Sessão</button>
                </form>
            <?php else: ?>
                <div class="d-flex gap-2 mt-2 mt-md-0">
                    <a href="<?php echo url('auth/register'); ?>" class="btn btn-sm btn-outline-light">Criar Conta</a>
                    <a href="<?php echo url('auth/login'); ?>" class="btn btn-sm btn-danger">Iniciar Sessão</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="faixa-xadrez" aria-hidden="true"></div>

<main class="container py-4 py-md-5 flex-grow-1">

    <?php if (!empty($data['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e($data['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($data['errors'])): ?>
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                <?php foreach ($data['errors'] as $erro): ?>
                    <li><?php echo e($erro); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
