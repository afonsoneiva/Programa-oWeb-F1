<div class="row justify-content-center">
    <div class="col-sm-10 col-md-6 col-lg-4">
        <h1 class="fw-bold text-danger text-center mb-4">🏁 Iniciar Sessão</h1>

        <form action="<?php echo url('auth/login'); ?>" method="POST" class="card card-body shadow-sm">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="next" value="<?php echo e($data['next']); ?>">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required autofocus autocomplete="username" value="<?php echo e($data['email']); ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-danger w-100">Entrar</button>
        </form>
    </div>
</div>
