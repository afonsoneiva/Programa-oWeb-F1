<h1 class="fw-bold mb-4"><?php echo e($data['titulo']); ?></h1>

<form action="<?php echo e($data['action']); ?>" method="POST" class="card card-body shadow-sm">
    <?php echo csrf_field(); ?>
    <div class="mb-3">
        <label for="nome" class="form-label">Nome da equipa</label>
        <input type="text" id="nome" name="nome" class="form-control" maxlength="100" required value="<?php echo e($data['construtor']['nome']); ?>">
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="pais" class="form-label">País</label>
            <select id="pais" name="pais" class="form-select" required>
                <option value="">Seleciona um país</option>
                <?php foreach ($data['paises'] as $pais): ?>
                    <option value="<?php echo e($pais); ?>" <?php echo $data['construtor']['pais'] === $pais ? 'selected' : ''; ?>>
                        <?php echo e($pais); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="motor" class="form-label">Motor</label>
            <input type="text" id="motor" name="motor" class="form-control" maxlength="50" required value="<?php echo e($data['construtor']['motor']); ?>">
        </div>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-danger">Guardar</button>
        <a href="<?php echo url('construtores'); ?>" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>
