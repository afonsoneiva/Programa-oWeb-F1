<div class="text-center py-5">
    <p class="display-1 fw-bold text-danger mb-0"><?php echo (int) http_response_code(); ?></p>
    <h1 class="h3 fw-bold mb-3"><?php echo e($data['titulo']); ?></h1>
    <p class="text-muted mb-4"><?php echo e($data['mensagem']); ?></p>
    <a href="<?php echo url(); ?>" class="btn btn-danger">Voltar ao Início</a>
</div>
