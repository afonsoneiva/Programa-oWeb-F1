<div class="row justify-content-center">
    <div class="col-sm-10 col-md-6 col-lg-5">
        <h1 class="fw-bold text-danger text-center mb-4">🏎️ Criar Conta</h1>

        <form action="<?php echo url('auth/register'); ?>" method="POST" class="card card-body shadow-sm">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" id="nome" name="nome" class="form-control" maxlength="100" required autofocus autocomplete="name" value="<?php echo e($data['conta']['nome']); ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" maxlength="150" required autocomplete="email" value="<?php echo e($data['conta']['email']); ?>">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" minlength="8" required autocomplete="new-password" aria-describedby="ajudaPassword">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="password_confirm" class="form-label">Confirmar password</label>
                    <input type="password" id="password_confirm" name="password_confirm" class="form-control" minlength="8" required autocomplete="new-password">
                </div>
            </div>
            <p id="ajudaPassword" class="form-text mt-0">Mínimo de 8 caracteres.</p>
            <button type="submit" class="btn btn-danger w-100">Criar Conta</button>
            <p class="text-center small mt-3 mb-0">
                Já tens conta? <a href="<?php echo url('auth/login'); ?>" class="link-danger">Inicia sessão</a>
            </p>
        </form>
    </div>
</div>
