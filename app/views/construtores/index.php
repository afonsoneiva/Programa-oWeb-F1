<?php $podeEditar = (bool) auth_user(); ?>

<div class="cabecalho-pagina d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <h1 class="fw-bold text-danger m-0">🛡️ Equipas / Construtores 2025</h1>
    <?php if ($podeEditar): ?>
        <a href="<?php echo url('construtores/create'); ?>" class="btn btn-danger align-self-start align-self-sm-auto">Adicionar Construtor</a>
    <?php endif; ?>
</div>

<form action="<?php echo url('construtores'); ?>" method="GET" class="card card-body shadow-sm mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-12 col-lg-6">
            <label for="q" class="form-label">Pesquisa livre</label>
            <input type="search" id="q" name="q" class="form-control" value="<?php echo e($data['filters']['q']); ?>" placeholder="Nome, país ou motor">
        </div>
        <div class="col-6 col-lg-2">
            <label for="pais" class="form-label">País (exato)</label>
            <input type="text" id="pais" name="pais" class="form-control" value="<?php echo e($data['filters']['pais']); ?>">
        </div>
        <div class="col-6 col-lg-2">
            <label for="motor" class="form-label">Motor (exato)</label>
            <input type="text" id="motor" name="motor" class="form-control" value="<?php echo e($data['filters']['motor']); ?>">
        </div>
        <div class="col-12 col-lg-2 d-flex gap-2">
            <button type="submit" class="btn btn-outline-danger">Pesquisar</button>
            <a href="<?php echo url('construtores'); ?>" class="btn btn-outline-secondary">Limpar</a>
        </div>
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped table-hover m-0 align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nome da Equipa</th>
                    <th>Nacionalidade</th>
                    <th>Motor</th>
                    <?php if ($podeEditar): ?><th>Ações</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['construtores'] as $c): ?>
                    <tr>
                        <td><?php echo (int) $c['id']; ?></td>
                        <td class="fw-bold">
                            <div class="d-inline-flex align-items-center gap-2">
                                <?php echo e($c['nome']); ?>
                                <?php if (auth_user()): ?>
                                    <?php $favorito = in_array((int) $c['id'], $data['favoritos'], true); ?>
                                    <form action="<?php echo url('favoritos/toggle'); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="entidade" value="construtores">
                                        <input type="hidden" name="item_id" value="<?php echo (int) $c['id']; ?>">
                                        <button type="submit" class="favorito-toggle<?php echo $favorito ? ' ativo' : ''; ?>" aria-label="<?php echo $favorito ? 'Remover dos favoritos' : 'Adicionar aos favoritos'; ?>" title="<?php echo $favorito ? 'Remover dos favoritos' : 'Adicionar aos favoritos'; ?>"><?php echo $favorito ? '★' : '☆'; ?></button>
                                    </form>
                                <?php else: ?>
                                    <a class="favorito-toggle" href="<?php echo url('auth/login?next=construtores'); ?>" aria-label="Inicia sessão para adicionar aos favoritos" title="Inicia sessão para adicionar aos favoritos">☆</a>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><?php echo e($c['pais']); ?></td>
                        <td><span class="badge badge-equipa" style="background-color: <?php echo e($c['cor']); ?>;"><?php echo e($c['motor']); ?></span></td>
                        <?php if ($podeEditar): ?>
                            <td class="acoes">
                                <a href="<?php echo url('construtores/update/' . (int) $c['id']); ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                <?php if ($c['motivo_bloqueio'] === null): ?>
                                <form action="<?php echo url('construtores/delete/' . (int) $c['id']); ?>" method="POST" class="d-inline" data-confirm="Eliminar o construtor <?php echo e($c['nome']); ?>?">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                                <?php else: ?>
                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="<?php echo e($c['motivo_bloqueio']); ?>">
                                    <button type="button" class="btn btn-sm btn-outline-secondary btn-bloqueado" style="pointer-events: none;" disabled aria-label="Eliminar (bloqueado): <?php echo e($c['motivo_bloqueio']); ?>">🔒 Eliminar</button>
                                </span>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($data['construtores'])): ?>
                    <tr>
                        <td colspan="<?php echo $podeEditar ? 5 : 4; ?>" class="text-center py-4">Nenhum construtor encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
