<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($data['titulo']) ? $data['titulo'] : 'Construtores F1'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold text-danger">🛡️ Equipas / Construtores 2025</h1>
            <a href="/Programa-oWeb-F1/" class="btn btn-outline-secondary">Voltar ao Início</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-striped table-hover m-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nome da Equipa</th>
                            <th>Nacionalidade</th>
                            <th>Motor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['construtores'])): ?>
                            <?php foreach ($data['construtores'] as $c): ?>
                                <tr>
                                    <td><?php echo $c['id']; ?></td>
                                    <td class="fw-bold text-dark"><?php echo htmlspecialchars($c['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($c['pais'] ?? 'N/D'); ?></td>
                                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars($c['motor'] ?? 'N/D'); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-4">Nenhum construtor encontrado na base de dados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>