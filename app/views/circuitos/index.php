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
            <a href="/Programa-oWeb-F1/" class="btn btn-outline-secondary">Voltar ao Início</a>
        </div>

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
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['circuitos'])): ?>
                            <?php foreach ($data['circuitos'] as $c): ?>
                                <tr>
                                    <td><?php echo $c['id']; ?></td>
                                    <td class="fw-bold text-danger"><?php echo htmlspecialchars($c['grande_premio']); ?></td>
                                    <td><?php echo htmlspecialchars($c['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($c['pais'] . ' (' . $c['cidade'] . ')'); ?></td>
                                    <td><?php echo number_format($c['extensao_km'], 3, ',', '.'); ?> km</td>
                                    <td><?php echo $c['num_voltas']; ?></td>
                                    <td><?php echo htmlspecialchars($c['piloto_vencedor'] ?? 'N/D'); ?></td>
                                    <td><?php echo htmlspecialchars($c['construtor_vencedor'] ?? 'N/D'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">Nenhum circuito encontrado na base de dados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>