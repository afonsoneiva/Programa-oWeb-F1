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
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <h1 class="fw-bold text-danger text-center mb-4">🏁 Iniciar Sessão</h1>

                <?php if (!empty($data['errors'])): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($data['errors'] as $error): ?>
                                <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?php echo $url_alias; ?>/auth/login" method="POST" class="card card-body shadow-sm">
                    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($data['csrf'], ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="next" value="<?php echo htmlspecialchars($data['next'], ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required autofocus autocomplete="username" value="<?php echo htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger flex-grow-1">Entrar</button>
                        <a href="<?php echo $url_alias; ?>/" class="btn btn-outline-secondary">Voltar</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
