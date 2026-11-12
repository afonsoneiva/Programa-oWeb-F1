<?php $podeEditar = (bool) auth_user(); ?>

<div class="cabecalho-pagina d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <h1 class="fw-bold text-danger m-0">🏁 Calendário de Circuitos 2025</h1>
    <?php if ($podeEditar): ?>
        <a href="<?php echo url('circuitos/create'); ?>" class="btn btn-danger align-self-start align-self-sm-auto">Adicionar Circuito</a>
    <?php endif; ?>
</div>

<form action="<?php echo url('circuitos'); ?>" method="GET" class="card card-body shadow-sm mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-12 col-lg-4">
            <label for="q" class="form-label">Pesquisa livre</label>
            <input type="search" id="q" name="q" class="form-control" value="<?php echo e($data['filters']['q']); ?>" placeholder="Circuito, GP, país, cidade ou vencedor">
        </div>
        <div class="col-12 col-sm-4 col-lg-2">
            <label for="grande_premio" class="form-label">Grande Prémio (exato)</label>
            <input type="text" id="grande_premio" name="grande_premio" class="form-control" value="<?php echo e($data['filters']['grande_premio']); ?>">
        </div>
        <div class="col-6 col-sm-4 col-lg-2">
            <label for="pais" class="form-label">País (exato)</label>
            <input type="text" id="pais" name="pais" class="form-control" value="<?php echo e($data['filters']['pais']); ?>">
        </div>
        <div class="col-6 col-sm-4 col-lg-2">
            <label for="cidade" class="form-label">Cidade (exata)</label>
            <input type="text" id="cidade" name="cidade" class="form-control" value="<?php echo e($data['filters']['cidade']); ?>">
        </div>
        <div class="col-12 col-lg-2 d-flex gap-2">
            <button type="submit" class="btn btn-outline-danger">Pesquisar</button>
            <a href="<?php echo url('circuitos'); ?>" class="btn btn-outline-secondary">Limpar</a>
        </div>
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped table-hover m-0 align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Grande Prémio</th>
                    <th>Circuito</th>
                    <th>País / Cidade</th>
                    <th>Extensão</th>
                    <th>Voltas</th>
                    <th>Piloto Vencedor</th>
                    <th>Equipa Vencedora</th>
                    <?php if ($podeEditar): ?><th>Ações</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['circuitos'] as $c): ?>
                    <tr>
                        <td><?php echo (int) $c['id']; ?></td>
                        <td class="fw-bold text-danger"><?php echo e($c['grande_premio']); ?></td>
                        <td><?php echo e($c['nome']); ?></td>
                        <td><?php echo e($c['pais'] . ' (' . $c['cidade'] . ')'); ?></td>
                        <td class="text-nowrap"><?php echo number_format((float) $c['extensao_km'], 3, ',', '.'); ?> km</td>
                        <td><?php echo (int) $c['num_voltas']; ?></td>
                        <td><?php echo e($c['piloto_vencedor'] ?? 'N/D'); ?></td>
                        <td><?php echo e($c['construtor_vencedor'] ?? 'N/D'); ?></td>
                        <?php if ($podeEditar): ?>
                            <td class="acoes">
                                <a href="<?php echo url('circuitos/update/' . (int) $c['id']); ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                <?php if (is_admin()): ?>
                                <form action="<?php echo url('circuitos/delete/' . (int) $c['id']); ?>" method="POST" class="d-inline" data-confirm="Eliminar o <?php echo e($c['grande_premio']); ?>?">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($data['circuitos'])): ?>
                    <tr>
                        <td colspan="<?php echo $podeEditar ? 9 : 8; ?>" class="text-center py-4">Nenhum circuito encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
