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
                <label for="nome" class="form-label">Nome da equipa</label>
                <input type="text" id="nome" name="nome" class="form-control" maxlength="100" required value="<?php echo htmlspecialchars($data['construtor']['nome'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="mb-3">
                <label for="pais" class="form-label">País</label>
                <input type="text" id="pais" name="pais" class="form-control" maxlength="50" required value="<?php echo htmlspecialchars($data['construtor']['pais'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="mb-3">
                <label for="motor" class="form-label">Motor</label>
                <input type="text" id="motor" name="motor" class="form-control" maxlength="50" required value="<?php echo htmlspecialchars($data['construtor']['motor'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger">Guardar</button>
                <a href="<?php echo $url_alias; ?>/construtores" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </main>
</body>
</html>
