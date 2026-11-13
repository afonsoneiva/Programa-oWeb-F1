<h1 class="fw-bold mb-4"><?php echo e($data['titulo']); ?></h1>

<form action="<?php echo e($data['action']); ?>" method="POST" class="card card-body shadow-sm">
    <?php echo csrf_field(); ?>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="nome" class="form-label">Nome do circuito</label>
            <input type="text" id="nome" name="nome" class="form-control" maxlength="100" required value="<?php echo e($data['circuito']['nome']); ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label for="grande_premio" class="form-label">Grande Prémio</label>
            <input type="text" id="grande_premio" name="grande_premio" class="form-control" maxlength="100" required value="<?php echo e($data['circuito']['grande_premio']); ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="pais" class="form-label">País</label>
            <select id="pais" name="pais" class="form-select" required>
                <option value="">Seleciona um país</option>
                <?php foreach ($data['paises'] as $pais): ?>
                    <option value="<?php echo e($pais); ?>" <?php echo $data['circuito']['pais'] === $pais ? 'selected' : ''; ?>>
                        <?php echo e($pais); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="cidade" class="form-label">Cidade</label>
            <input type="text" id="cidade" name="cidade" class="form-control" maxlength="50" required value="<?php echo e($data['circuito']['cidade']); ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="extensao_km" class="form-label">Extensão (km)</label>
            <input type="number" id="extensao_km" name="extensao_km" class="form-control" min="0.001" max="9.999" step="0.001" required value="<?php echo e($data['circuito']['extensao_km']); ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label for="num_voltas" class="form-label">Número de voltas</label>
            <input type="number" id="num_voltas" name="num_voltas" class="form-control" min="1" required value="<?php echo e($data['circuito']['num_voltas']); ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="piloto_vencedor_id" class="form-label">Piloto vencedor</label>
            <select id="piloto_vencedor_id" name="piloto_vencedor_id" class="form-select">
                <option value="">Sem vencedor registado</option>
                <?php foreach ($data['pilotos'] as $piloto): ?>
                    <option value="<?php echo (int) $piloto['id']; ?>" <?php echo (string) $data['circuito']['piloto_vencedor_id'] === (string) $piloto['id'] ? 'selected' : ''; ?>>
                        <?php echo e($piloto['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="construtor_vencedor_id" class="form-label">Construtor vencedor</label>
            <select id="construtor_vencedor_id" name="construtor_vencedor_id" class="form-select">
                <option value="">Sem vencedor registado</option>
                <?php foreach ($data['construtores'] as $construtor): ?>
                    <option value="<?php echo (int) $construtor['id']; ?>" <?php echo (string) $data['circuito']['construtor_vencedor_id'] === (string) $construtor['id'] ? 'selected' : ''; ?>>
                        <?php echo e($construtor['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-danger">Guardar</button>
        <a href="<?php echo url('circuitos'); ?>" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>
