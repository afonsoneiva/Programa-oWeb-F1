<?php $podeEditar = (bool) auth_user(); ?>

<div class="cabecalho-pagina d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <h1 class="fw-bold text-danger m-0">🏎️ Pilotos 2025</h1>
    <?php if ($podeEditar): ?>
        <a href="<?php echo url('pilotos/create'); ?>" class="btn btn-danger align-self-start align-self-sm-auto">Adicionar Piloto</a>
    <?php endif; ?>
</div>

<form action="<?php echo url('pilotos'); ?>" method="GET" class="card card-body shadow-sm mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-12 col-lg-5">
            <label for="q" class="form-label">Pesquisa livre</label>
            <input type="search" id="q" name="q" class="form-control" value="<?php echo e($data['filters']['q']); ?>" placeholder="Nome, número, nacionalidade ou equipa">
        </div>
        <div class="col-6 col-lg-3">
            <label for="nacionalidade" class="form-label">Nacionalidade (exata)</label>
            <input type="text" id="nacionalidade" name="nacionalidade" class="form-control" value="<?php echo e($data['filters']['nacionalidade']); ?>">
        </div>
        <div class="col-6 col-lg-2">
            <label for="construtor_id" class="form-label">Equipa</label>
            <select id="construtor_id" name="construtor_id" class="form-select">
                <option value="">Todas</option>
                <?php foreach ($data['construtores'] as $construtor): ?>
                    <option value="<?php echo (int) $construtor['id']; ?>" <?php echo (string) $data['filters']['construtor_id'] === (string) $construtor['id'] ? 'selected' : ''; ?>>
                        <?php echo e($construtor['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12 col-lg-2 d-flex gap-2">
            <button type="submit" class="btn btn-outline-danger">Pesquisar</button>
            <a href="<?php echo url('pilotos'); ?>" class="btn btn-outline-secondary">Limpar</a>
        </div>
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped table-hover m-0 align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Número</th>
                    <th>Nome</th>
                    <th>Nacionalidade</th>
                    <th>Equipa</th>
                    <?php if ($podeEditar): ?><th>Ações</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['pilotos'] as $p): ?>
                    <tr>
                        <td><?php echo (int) $p['id']; ?></td>
                        <td><span class="badge badge-equipa" style="background-color: <?php echo e($p['cor_equipa']); ?>;">#<?php echo (int) $p['numero']; ?></span></td>
                        <td class="fw-bold"><?php echo e($p['nome']); ?></td>
                        <td><?php echo e($p['nacionalidade']); ?></td>
                        <td><?php echo e($p['nome_construtor'] ?? 'Sem Equipa'); ?></td>
                        <?php if ($podeEditar): ?>
                            <td class="acoes">
                                <a href="<?php echo url('pilotos/update/' . (int) $p['id']); ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                <form action="<?php echo url('pilotos/delete/' . (int) $p['id']); ?>" method="POST" class="d-inline" data-confirm="Eliminar o piloto <?php echo e($p['nome']); ?>?">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($data['pilotos'])): ?>
                    <tr>
                        <td colspan="<?php echo $podeEditar ? 6 : 5; ?>" class="text-center py-4">Nenhum piloto encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
