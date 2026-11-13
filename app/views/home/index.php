<?php
$totais = $data['totais'];
$maxPiloto = max(array_column($data['topPilotos'], 'vitorias') ?: [1]);
$maxEquipa = max(array_column($data['topConstrutores'], 'vitorias') ?: [1]);
$lider = $data['topPilotos'][0] ?? null;
$km = function ($valor) {
    return number_format((float) $valor, 3, ',', '.');
};
?>

<!-- Hero com pesquisa global -->
<section class="hero rounded-3 shadow mb-4 mb-md-5">
    <div class="hero-conteudo p-4 p-md-5">
        <p class="text-uppercase small fw-bold text-danger mb-2 letter-spacing">Campeonato do Mundo · Temporada 2025</p>
        <h1 class="display-5 fw-bold text-white mb-3">Tudo sobre a grelha de Fórmula 1</h1>
        <p class="lead text-white-50 mb-4">
            Pilotos, equipas e os <?php echo (int) $totais['circuitos']; ?> Grandes Prémios do calendário, num só lugar.
        </p>

        <form action="<?php echo url('home/pesquisa'); ?>" method="GET" class="pesquisa-global" role="search">
            <label for="pesquisa" class="visually-hidden">Pesquisar</label>
            <div class="input-group input-group-lg">
                <input type="search" id="pesquisa" name="q" class="form-control" placeholder="Pesquisa um piloto, equipa ou circuito…" required>
                <button type="submit" class="btn btn-danger px-4">Pesquisar</button>
            </div>
        </form>
        <p class="small text-white-50 mt-2 mb-0">
            Experimenta:
            <?php foreach (['Verstappen', 'Ferrari', 'Mónaco'] as $exemplo): ?>
                <a href="<?php echo url('home/pesquisa?q=' . urlencode($exemplo)); ?>" class="link-light ms-1"><?php echo e($exemplo); ?></a>
            <?php endforeach; ?>
        </p>
    </div>
</section>

<!-- Números da época -->
<section class="row g-3 mb-4 mb-md-5" aria-label="Números da época">
    <?php foreach ([
        ['pilotos', '🏎️', $totais['pilotos'], 'Pilotos', 'Ver a grelha'],
        ['construtores', '🛡️', $totais['construtores'], 'Equipas', 'Ver as equipas'],
        ['circuitos', '🏁', $totais['circuitos'], 'Grandes Prémios', 'Ver o calendário'],
        ['circuitos', '📏', number_format($totais['km'], 0, ',', '.'), 'km de corrida', 'Distância total'],
    ] as [$link, $icone, $valor, $rotulo, $acao]): ?>
        <div class="col-6 col-lg-3">
            <a href="<?php echo url($link); ?>" class="card cartao-numero h-100 shadow-sm text-decoration-none">
                <div class="card-body">
                    <div class="fs-3 mb-1" aria-hidden="true"><?php echo $icone; ?></div>
                    <div class="display-6 fw-bold text-dark"><?php echo e($valor); ?></div>
                    <div class="text-muted"><?php echo e($rotulo); ?></div>
                    <div class="small text-danger mt-2"><?php echo e($acao); ?> →</div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</section>

<!-- Classificações por vitórias -->
<div class="row g-4 mb-4 mb-md-5">
    <section class="col-12 col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body p-4">
                <h2 class="h5 fw-bold mb-1">🏆 Mais vitórias — Pilotos</h2>
                <?php if ($lider): ?>
                    <p class="small text-muted mb-4"><?php echo e($lider['nome']); ?> lidera com <?php echo (int) $lider['vitorias']; ?> vitórias.</p>
                <?php endif; ?>

                <?php foreach ($data['topPilotos'] as $posicao => $piloto): ?>
                    <div class="barra-classificacao mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span><span class="fw-bold me-2"><?php echo $posicao + 1; ?>.</span><?php echo e($piloto['nome']); ?>
                                <span class="text-muted d-none d-sm-inline">· <?php echo e($piloto['equipa'] ?? 'Sem equipa'); ?></span></span>
                            <span class="fw-bold"><?php echo (int) $piloto['vitorias']; ?></span>
                        </div>
                        <div class="progress" role="progressbar" aria-label="Vitórias de <?php echo e($piloto['nome']); ?>" aria-valuenow="<?php echo (int) $piloto['vitorias']; ?>" aria-valuemin="0" aria-valuemax="<?php echo (int) $maxPiloto; ?>">
                            <div class="progress-bar" style="width: <?php echo round($piloto['vitorias'] / $maxPiloto * 100); ?>%; background-color: <?php echo e($piloto['cor']); ?>;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (!$data['topPilotos']): ?>
                    <p class="text-muted mb-0">Ainda não há vencedores registados.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="col-12 col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body p-4">
                <h2 class="h5 fw-bold mb-1">🛠️ Mais vitórias — Equipas</h2>
                <p class="small text-muted mb-4">Grandes Prémios ganhos por cada construtor.</p>

                <?php foreach ($data['topConstrutores'] as $posicao => $equipa): ?>
                    <div class="barra-classificacao mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span><span class="fw-bold me-2"><?php echo $posicao + 1; ?>.</span><?php echo e($equipa['nome']); ?></span>
                            <span class="fw-bold"><?php echo (int) $equipa['vitorias']; ?></span>
                        </div>
                        <div class="progress" role="progressbar" aria-label="Vitórias de <?php echo e($equipa['nome']); ?>" aria-valuenow="<?php echo (int) $equipa['vitorias']; ?>" aria-valuemin="0" aria-valuemax="<?php echo (int) $maxEquipa; ?>">
                            <div class="progress-bar" style="width: <?php echo round($equipa['vitorias'] / $maxEquipa * 100); ?>%; background-color: <?php echo e($equipa['cor']); ?>;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (!$data['topConstrutores']): ?>
                    <p class="text-muted mb-0">Ainda não há vencedores registados.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<!-- Curiosidades -->
<?php if ($data['curiosidades']): ?>
    <section class="mb-4 mb-md-5">
        <h2 class="h5 fw-bold mb-3">📍 Curiosidades do calendário</h2>
        <div class="row g-3">
            <?php foreach ([
                'mais_longo' => ['Circuito mais longo', function ($c) use ($km) { return $km($c['extensao_km']) . ' km'; }],
                'mais_curto' => ['Circuito mais curto', function ($c) use ($km) { return $km($c['extensao_km']) . ' km'; }],
                'mais_voltas' => ['Mais voltas', function ($c) { return (int) $c['num_voltas'] . ' voltas'; }],
            ] as $chave => [$rotulo, $valor]): ?>
                <?php if (empty($data['curiosidades'][$chave])) continue; ?>
                <?php $c = $data['curiosidades'][$chave]; ?>
                <div class="col-12 col-md-4">
                    <div class="card shadow-sm h-100 border-0 border-start border-4 border-danger">
                        <div class="card-body">
                            <div class="small text-muted text-uppercase"><?php echo e($rotulo); ?></div>
                            <div class="fs-4 fw-bold"><?php echo e($valor($c)); ?></div>
                            <div><?php echo e($c['grande_premio']); ?></div>
                            <div class="small text-muted"><?php echo e($c['nome'] . ' · ' . $c['cidade']); ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<!-- Atalhos conforme o utilizador -->
<section class="card shadow-sm bg-dark text-white">
    <div class="card-body p-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <?php if (auth_user()): ?>
            <div>
                <h2 class="h5 fw-bold mb-1">Olá, <?php echo e(auth_user()['nome']); ?> 👋</h2>
                <p class="text-white-50 mb-0">Ajuda a manter a informação da temporada atualizada.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?php echo url('pilotos/create'); ?>" class="btn btn-danger">+ Piloto</a>
                <a href="<?php echo url('construtores/create'); ?>" class="btn btn-outline-light">+ Equipa</a>
                <?php if (is_admin()): ?>
                    <a href="<?php echo url('circuitos/create'); ?>" class="btn btn-outline-light">+ Circuito</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div>
                <h2 class="h5 fw-bold mb-1">Queres contribuir?</h2>
                <p class="text-white-50 mb-0">Cria uma conta para adicionar e editar pilotos e equipas.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?php echo url('auth/register'); ?>" class="btn btn-danger">Criar Conta</a>
                <a href="<?php echo url('auth/login'); ?>" class="btn btn-outline-light">Iniciar Sessão</a>
            </div>
        <?php endif; ?>
    </div>
</section>
