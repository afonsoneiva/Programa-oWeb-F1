<?php if ($auth_user): ?>
    <form action="<?php echo $url_alias; ?>/auth/logout" method="POST" class="d-inline-flex align-items-center gap-2 m-0">
        <span class="small text-muted text-nowrap">👤 <?php echo htmlspecialchars($auth_user['nome'], ENT_QUOTES, 'UTF-8'); ?></span>
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
        <button type="submit" class="btn btn-outline-dark">Terminar Sessão</button>
    </form>
<?php else: ?>
    <a href="<?php echo $url_alias; ?>/auth/login" class="btn btn-outline-dark">Iniciar Sessão</a>
<?php endif; ?>
