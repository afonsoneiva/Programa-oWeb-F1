/* ==========================================================
   Portal F1 — JavaScript da aplicação
   ========================================================== */

document.addEventListener('DOMContentLoaded', () => {
    // Pede confirmação antes de submeter formulários marcados com data-confirm
    // (ex.: <form data-confirm="Eliminar este piloto?">)
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (evento) => {
            if (!window.confirm(form.dataset.confirm)) {
                evento.preventDefault();
            }
        });
    });
});
