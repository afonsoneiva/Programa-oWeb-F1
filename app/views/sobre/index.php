<?php
function e($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function iniciais(string $nome): string {
    $partes = preg_split('/\s+/', trim($nome));
    $primeira = mb_substr($partes[0] ?? '', 0, 1, 'UTF-8');
    $ultima = count($partes) > 1 ? mb_substr(end($partes), 0, 1, 'UTF-8') : '';
    return mb_strtoupper($primeira . $ultima, 'UTF-8');
}
?>
<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($data['titulo']); ?> | Portal F1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .avatar {
            width: 72px;
            height: 72px;
            font-size: 1.5rem;
        }
        .faixa-f1 {
            height: 6px;
            background: repeating-linear-gradient(90deg, #212529 0 16px, #fff 16px 32px);
        }
    </style>
</head>
<body class="bg-light">

    <main class="container py-5">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
            <h1 class="fw-bold text-danger m-0">🏁 Sobre o Projeto</h1>
            <a href="<?php echo $url_alias; ?>/" class="btn btn-outline-secondary align-self-start align-self-sm-auto">Voltar ao Início</a>
        </div>

        <section class="card shadow-sm mb-4 overflow-hidden">
            <div class="faixa-f1"></div>
            <div class="card-body p-4">
                <h2 class="h4 fw-bold">Portal Fórmula 1 — Época 2025</h2>
                <p class="mb-3">
                    Aplicação web informativa sobre a temporada 2025 do Campeonato do Mundo de Fórmula 1.
                    Permite consultar e gerir os pilotos da grelha, as equipas/construtores e os circuitos do
                    calendário, incluindo os respetivos vencedores.
                </p>
                <p class="text-muted mb-0">
                    Trabalho Prático #1 da Unidade Curricular de <strong>Programação Web</strong> —
                    Engenharia de Computação Gráfica e Multimédia, 3.º ano, ESTG / Instituto Politécnico de Viana do Castelo,
                    ano letivo 2026/2027.
                </p>
            </div>
        </section>

        <section class="mb-4">
            <h2 class="h4 fw-bold mb-3">Autores</h2>
            <div class="row g-3">
                <?php foreach ($data['autores'] as $autor): ?>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="card shadow-sm h-100 text-center">
                            <div class="card-body p-4">
                                <div class="avatar rounded-circle bg-danger text-white fw-bold d-flex align-items-center justify-content-center mx-auto mb-3">
                                    <?php echo e(iniciais($autor['nome'])); ?>
                                </div>
                                <h3 class="h5 fw-bold mb-1"><?php echo e($autor['nome']); ?></h3>
                                <p class="mb-1">N.º <?php echo e($autor['numero']); ?></p>
                                <p class="small text-muted mb-0"><?php echo e($autor['curso']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <div class="row g-4">
            <section class="col-12 col-lg-7">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4">
                        <h2 class="h4 fw-bold mb-3">Funcionalidades</h2>
                        <ul class="mb-0">
                            <?php foreach ($data['funcionalidades'] as $funcionalidade): ?>
                                <li class="mb-1"><?php echo e($funcionalidade); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </section>
            <section class="col-12 col-lg-5">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4">
                        <h2 class="h4 fw-bold mb-3">Tecnologias</h2>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($data['tecnologias'] as $tecnologia): ?>
                                <span class="badge bg-dark fs-6 fw-normal"><?php echo e($tecnologia); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

</body>
</html>
