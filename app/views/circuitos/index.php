<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($data['titulo']) ? $data['titulo'] : 'Circuitos F1'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold text-danger">🏁 Calendário de Circuitos 2025</h1>
            <div class="d-flex gap-2">
                <a href="<?php echo $url_alias; ?>/circuitos/create" class="btn btn-danger">Adicionar Circuito</a>
                <a href="<?php echo $url_alias; ?>/" class="btn btn-outline-secondary">Voltar ao Início</a>
            </div>
        </div>

        <?php if (!empty($data['success'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($data['success'], ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-striped table-hover m-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Grande Prémio</th>
                            <th>Circuito</th>
                            <th>País / Cidade</th>
                            <th>Extensão (km)</th>
                            <th>Voltas</th>
                            <th>Último Vencedor (Piloto)</th>
                            <th>Equipa Vencedora</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['circuitos'])): ?>
                            <?php foreach ($data['circuitos'] as $c): ?>
                                <tr>
                                    <td><?php echo (int) $c['id']; ?></td>
                                    <td class="fw-bold text-danger"><?php echo htmlspecialchars($c['grande_premio'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($c['nome'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($c['pais'] . ' (' . $c['cidade'] . ')', ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo number_format($c['extensao_km'], 3, ',', '.'); ?> km</td>
                                    <td><?php echo (int) $c['num_voltas']; ?></td>
                                    <td><?php echo htmlspecialchars($c['piloto_vencedor'] ?? 'N/D', ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($c['construtor_vencedor'] ?? 'N/D', ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-nowrap">
                                        <a href="<?php echo $url_alias; ?>/circuitos/update/<?php echo (int) $c['id']; ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                        <form action="<?php echo $url_alias; ?>/circuitos/delete/<?php echo (int) $c['id']; ?>" method="POST" class="d-inline" onsubmit="return confirm('Eliminar este circuito?');">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center py-4">Nenhum circuito encontrado na base de dados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>