</main>

<footer class="bg-dark text-white-50 py-3 mt-auto">
    <div class="container d-flex flex-column flex-sm-row justify-content-between gap-1 small">
        <span>Portal F1 · Programação Web 2026/2027 · ESTG-UNIPVC</span>
    </div>
</footer>

<div class="modal fade" id="confirmarEliminacao" tabindex="-1" aria-labelledby="confirmarEliminacaoTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="confirmarEliminacaoTitulo">Confirmar eliminação</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body" data-confirm-message></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" data-confirm-submit>Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo url('assets/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?php echo asset('assets/js/main.js'); ?>"></script>
</body>
</html>
