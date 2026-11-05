<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($data['titulo'], ENT_QUOTES, 'UTF-8'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5">
        <h1 class="fw-bold mb-4"><?php echo htmlspecialchars($data['titulo'], ENT_QUOTES, 'UTF-8'); ?></h1>

        <?php if (!empty($data['errors'])): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($data['errors'] as $error): ?>
                        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($data['action'], ENT_QUOTES, 'UTF-8'); ?>" method="POST" class="card card-body shadow-sm">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do circuito</label>
                <input type="text" id="nome" name="nome" class="form-control" maxlength="100" required value="<?php echo htmlspecialchars($data['circuito']['nome'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="mb-3">
                <label for="grande_premio" class="form-label">Grande Prémio</label>
                <input type="text" id="grande_premio" name="grande_premio" class="form-control" maxlength="100" required value="<?php echo htmlspecialchars($data['circuito']['grande_premio'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="pais" class="form-label">País</label>
                    <input type="text" id="pais" name="pais" class="form-control" maxlength="50" required value="<?php echo htmlspecialchars($data['circuito']['pais'], ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cidade" class="form-label">Cidade</label>
                    <input type="text" id="cidade" name="cidade" class="form-control" maxlength="50" required value="<?php echo htmlspecialchars($data['circuito']['cidade'], ENT_QUOTES, 'UTF-8'); ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="extensao_km" class="form-label">Extensão (km)</label>
                    <input type="number" id="extensao_km" name="extensao_km" class="form-control" min="0.001" max="9.999" step="0.001" required value="<?php echo htmlspecialchars((string) $data['circuito']['extensao_km'], ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="num_voltas" class="form-label">Número de voltas</label>
                    <input type="number" id="num_voltas" name="num_voltas" class="form-control" min="1" required value="<?php echo htmlspecialchars((string) $data['circuito']['num_voltas'], ENT_QUOTES, 'UTF-8'); ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="piloto_vencedor_id" class="form-label">Piloto vencedor</label>
                    <select id="piloto_vencedor_id" name="piloto_vencedor_id" class="form-select">
                        <option value="">Sem vencedor registado</option>
                        <?php foreach ($data['pilotos'] as $piloto): ?>
                            <option value="<?php echo (int) $piloto['id']; ?>" <?php echo (string) $data['circuito']['piloto_vencedor_id'] === (string) $piloto['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($piloto['nome'], ENT_QUOTES, 'UTF-8'); ?>
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
                                <?php echo htmlspecialchars($construtor['nome'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger">Guardar</button>
                <a href="<?php echo $url_alias; ?>/circuitos" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </main>
</body>
</html>
