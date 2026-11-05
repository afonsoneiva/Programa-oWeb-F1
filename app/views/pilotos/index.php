<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($data['titulo']) ? $data['titulo'] : 'Pilotos F1'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold text-danger">🏎️ Pilotos 2025</h1>
            <div class="d-flex gap-2">
                <a href="<?php echo $url_alias; ?>/pilotos/create" class="btn btn-danger">Adicionar Piloto</a>
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
                            <th>Número</th>
                            <th>Nome</th>
                            <th>Nacionalidade</th>
                            <th>Equipa</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['pilotos'])): ?>
                            <?php foreach ($data['pilotos'] as $p): ?>
                                <tr>
                                    <td><?php echo (int) $p['id']; ?></td>
                                    <td><span class="badge bg-danger">#<?php echo (int) $p['numero']; ?></span></td>
                                    <td class="fw-bold"><?php echo htmlspecialchars($p['nome'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($p['nacionalidade'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($p['nome_construtor'] ?? 'Sem Equipa', ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-nowrap">
                                        <a href="<?php echo $url_alias; ?>/pilotos/update/<?php echo (int) $p['id']; ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                        <form action="<?php echo $url_alias; ?>/pilotos/delete/<?php echo (int) $p['id']; ?>" method="POST" class="d-inline" onsubmit="return confirm('Eliminar este piloto?');">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">Nenhum piloto encontrado na base de dados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>