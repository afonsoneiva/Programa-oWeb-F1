<?php
$r = $data['resultados'];
$q = $data['q'];
?>

<form action="<?php echo url('home/pesquisa'); ?>" method="GET" class="mb-4" role="search">
    <label for="pesquisa" class="form-label fw-bold">Pesquisa global</label>
    <div class="input-group input-group-lg">
        <input type="search" id="pesquisa" name="q" class="form-control" value="<?php echo e($q); ?>" required>
        <button type="submit" class="btn btn-danger px-4">Pesquisar</button>
    </div>
</form>

<h1 class="h4 fw-bold mb-4">
    <?php echo (int) $data['total']; ?> resultado<?php echo $data['total'] === 1 ? '' : 's'; ?> para "<?php echo e($q); ?>"
</h1>

<?php if ($data['total'] === 0): ?>
    <div class="card card-body shadow-sm text-center py-5">
        <p class="fs-1 mb-2" aria-hidden="true">🔍</p>
        <p class="mb-3">Não encontrámos pilotos, equipas ou circuitos com esse termo.</p>
        <a href="<?php echo url(); ?>" class="btn btn-outline-danger align-self-center">Voltar ao Início</a>
    </div>
<?php endif; ?>

<div class="row g-4">
    <?php if ($r['pilotos']): ?>
        <section class="col-12 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h2 class="h6 fw-bold m-0">🏎️ Pilotos</h2>
                    <span class="badge bg-danger rounded-pill"><?php echo count($r['pilotos']); ?></span>
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($r['pilotos'] as $p): ?>
                        <li class="list-group-item d-flex align-items-center gap-2">
                            <span class="badge badge-equipa" style="background-color: <?php echo e($p['cor_equipa']); ?>;">#<?php echo (int) $p['numero']; ?></span>
                            <div>
                                <div class="fw-bold"><?php echo e($p['nome']); ?></div>
                                <div class="small text-muted"><?php echo e($p['nome_construtor'] ?? 'Sem equipa'); ?> · <?php echo e($p['nacionalidade']); ?></div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="card-footer bg-white">
                    <a href="<?php echo url('pilotos?q=' . urlencode($q)); ?>" class="small link-danger">Abrir na lista de pilotos →</a>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($r['construtores']): ?>
        <section class="col-12 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h2 class="h6 fw-bold m-0">🛡️ Equipas</h2>
                    <span class="badge bg-danger rounded-pill"><?php echo count($r['construtores']); ?></span>
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($r['construtores'] as $c): ?>
                        <li class="list-group-item d-flex align-items-center gap-2">
                            <span class="cor-equipa" style="background-color: <?php echo e($c['cor']); ?>;" aria-hidden="true"></span>
                            <div>
                                <div class="fw-bold"><?php echo e($c['nome']); ?></div>
                                <div class="small text-muted"><?php echo e($c['pais']); ?> · Motor <?php echo e($c['motor']); ?></div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="card-footer bg-white">
                    <a href="<?php echo url('construtores?q=' . urlencode($q)); ?>" class="small link-danger">Abrir na lista de equipas →</a>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($r['circuitos']): ?>
        <section class="col-12 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h2 class="h6 fw-bold m-0">🏁 Circuitos</h2>
                    <span class="badge bg-danger rounded-pill"><?php echo count($r['circuitos']); ?></span>
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($r['circuitos'] as $c): ?>
                        <li class="list-group-item">
                            <div class="fw-bold"><?php echo e($c['grande_premio']); ?></div>
                            <div class="small text-muted"><?php echo e($c['nome'] . ' · ' . $c['cidade']); ?></div>
                            <?php if (!empty($c['piloto_vencedor'])): ?>
                                <div class="small">🏆 <?php echo e($c['piloto_vencedor']); ?></div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="card-footer bg-white">
                    <a href="<?php echo url('circuitos?q=' . urlencode($q)); ?>" class="small link-danger">Abrir no calendário →</a>
                </div>
            </div>
        </section>
    <?php endif; ?>
</div>
