<?php $podeEditar = (bool) auth_user(); ?>

<div class="cabecalho-pagina d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <h1 class="fw-bold text-danger m-0">🏎️ Pilotos 2025</h1>
    <?php if ($podeEditar): ?>
        <a href="<?php echo url('pilotos/create'); ?>" class="btn btn-danger align-self-start align-self-sm-auto">Adicionar Piloto</a>
    <?php endif; ?>
</div>

<form action="<?php echo url('pilotos'); ?>" method="GET" class="card card-body shadow-sm mb-4"
      data-live-search data-live-results="#pilotos-results">
    <div class="row g-3 align-items-end">
        <div class="col-12 col-lg-5">
            <label for="q" class="form-label">Pesquisa livre</label>
            <input type="search" id="q" name="q" class="form-control" value="<?php echo e($data['filters']['q']); ?>" placeholder="Nome, número, nacionalidade ou equipa">
        </div>
        <div class="col-6 col-lg-3">
            <label for="nacionalidade" class="form-label">Nacionalidade (exata)</label>
            <select id="nacionalidade" name="nacionalidade" class="form-select">
                <option value="">Todas</option>
                <?php foreach ($data['nacionalidades'] as $nacionalidade): ?>
                    <option value="<?php echo e($nacionalidade); ?>" <?php echo $data['filters']['nacionalidade'] === $nacionalidade ? 'selected' : ''; ?>>
                        <?php echo e($nacionalidade); ?>
                    </option>
                <?php endforeach; ?>
            </select>
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

<div id="pilotos-results" aria-live="polite">
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
                        <td class="fw-bold">
                            <div class="d-inline-flex align-items-center gap-2">
                                <?php echo e($p['nome']); ?>
                                <?php if (auth_user()): ?>
                                    <?php $favorito = in_array((int) $p['id'], $data['favoritos'], true); ?>
                                    <form action="<?php echo url('favoritos/toggle'); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="entidade" value="pilotos">
                                        <input type="hidden" name="item_id" value="<?php echo (int) $p['id']; ?>">
                                        <button type="submit" class="favorito-toggle<?php echo $favorito ? ' ativo' : ''; ?>" aria-label="<?php echo $favorito ? 'Remover dos favoritos' : 'Adicionar aos favoritos'; ?>" title="<?php echo $favorito ? 'Remover dos favoritos' : 'Adicionar aos favoritos'; ?>"><?php echo $favorito ? '★' : '☆'; ?></button>
                                    </form>
                                <?php else: ?>
                                    <a class="favorito-toggle" href="<?php echo url('auth/login?next=pilotos'); ?>" aria-label="Inicia sessão para adicionar aos favoritos" title="Inicia sessão para adicionar aos favoritos">☆</a>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><?php echo e($p['nacionalidade']); ?></td>
                        <td><?php echo e($p['nome_construtor'] ?? 'Sem Equipa'); ?></td>
                        <?php if ($podeEditar): ?>
                            <td class="acoes">
                                <a href="<?php echo url('pilotos/update/' . (int) $p['id']); ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                <?php if ($p['motivo_bloqueio'] === null): ?>
                                <form action="<?php echo url('pilotos/delete/' . (int) $p['id']); ?>" method="POST" class="d-inline" data-confirm="Eliminar o piloto <?php echo e($p['nome']); ?>?">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                                <?php else: ?>
                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="<?php echo e($p['motivo_bloqueio']); ?>">
                                    <button type="button" class="btn btn-sm btn-outline-secondary btn-bloqueado" style="pointer-events: none;" disabled aria-label="Eliminar (bloqueado): <?php echo e($p['motivo_bloqueio']); ?>">🔒 Eliminar</button>
                                </span>
                                <?php endif; ?>
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
</div>
