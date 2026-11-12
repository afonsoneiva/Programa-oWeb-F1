<h1 class="fw-bold mb-4"><?php echo e($data['titulo']); ?></h1>

<form action="<?php echo e($data['action']); ?>" method="POST" class="card card-body shadow-sm">
    <?php echo csrf_field(); ?>
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" id="nome" name="nome" class="form-control" maxlength="100" required value="<?php echo e($data['piloto']['nome']); ?>">
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="numero" class="form-label">Número</label>
            <input type="number" id="numero" name="numero" class="form-control" min="0" required value="<?php echo e($data['piloto']['numero']); ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label for="nacionalidade" class="form-label">Nacionalidade</label>
            <input type="text" id="nacionalidade" name="nacionalidade" class="form-control" maxlength="50" required value="<?php echo e($data['piloto']['nacionalidade']); ?>">
        </div>
    </div>
    <div class="mb-3">
        <label for="construtor_id" class="form-label">Equipa</label>
        <select id="construtor_id" name="construtor_id" class="form-select">
            <option value="">Sem equipa</option>
            <?php foreach ($data['construtores'] as $construtor): ?>
                <option value="<?php echo (int) $construtor['id']; ?>" <?php echo (string) $data['piloto']['construtor_id'] === (string) $construtor['id'] ? 'selected' : ''; ?>>
                    <?php echo e($construtor['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-danger">Guardar</button>
        <a href="<?php echo url('pilotos'); ?>" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>
