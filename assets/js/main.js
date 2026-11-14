/* ==========================================================
   Portal F1 — JavaScript da aplicação
   ========================================================== */

document.addEventListener('DOMContentLoaded', () => {
    const modalConfirmacao = document.querySelector('#confirmarEliminacao');
    let formularioAEliminar = null;

    document.addEventListener('submit', (evento) => {
        const form = evento.target;
        if (!(form instanceof HTMLFormElement) || !form.matches('form[data-confirm]')) {
            return;
        }

        if (form.dataset.confirmed === 'true') {
            delete form.dataset.confirmed;
            return;
        }

        if (modalConfirmacao) {
            evento.preventDefault();
            formularioAEliminar = form;
            modalConfirmacao.querySelector('[data-confirm-message]').textContent = form.dataset.confirm;
            bootstrap.Modal.getOrCreateInstance(modalConfirmacao).show();
        }
    });

    modalConfirmacao?.querySelector('[data-confirm-submit]').addEventListener('click', () => {
        if (!formularioAEliminar) {
            return;
        }

        const form = formularioAEliminar;
        formularioAEliminar = null;
        form.dataset.confirmed = 'true';
        form.requestSubmit();
        bootstrap.Modal.getOrCreateInstance(modalConfirmacao).hide();
    });

    document.querySelectorAll('input[data-piloto-numero]').forEach((campo) => {
        const validarNumeroPiloto = () => {
            campo.setCustomValidity(campo.valueAsNumber === 17 ? 'O número 17 não está disponível.' : '');
        };
        campo.addEventListener('input', validarNumeroPiloto);
        validarNumeroPiloto();
    });

    const inicializarTooltips = (alvo = document) => {
        alvo.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((elemento) => {
            if (!bootstrap.Tooltip.getInstance(elemento)) {
                new bootstrap.Tooltip(elemento);
            }
        });
    };

    inicializarTooltips();

    document.querySelectorAll('form[data-live-search]').forEach((form) => {
        const campo = form.querySelector('input[name="q"]');
        const seletorResultados = form.dataset.liveResults;
        const resultados = seletorResultados ? document.querySelector(seletorResultados) : null;

        if (!campo || !resultados) {
            console.error('Formulário de pesquisa dinâmica sem campo ou área de resultados.', form);
            return;
        }

        let temporizador;
        let pedido;

        const pesquisar = async () => {
            const termo = campo.value.trim();
            if (form.dataset.liveEmptyUrl && termo === '') {
                pedido?.abort();
                resultados.replaceChildren();
                resultados.setAttribute('aria-busy', 'false');
                window.history.replaceState(null, '', form.dataset.liveEmptyUrl);
                return;
            }

            const url = new URL(form.action, window.location.href);
            url.search = new URLSearchParams(new FormData(form)).toString();
            pedido?.abort();
            const pedidoAtual = new AbortController();
            pedido = pedidoAtual;
            resultados.setAttribute('aria-busy', 'true');

            try {
                const resposta = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    signal: pedidoAtual.signal
                });
                if (!resposta.ok) {
                    throw new Error(`A pesquisa falhou (${resposta.status}).`);
                }

                const documento = new DOMParser().parseFromString(await resposta.text(), 'text/html');
                const novosResultados = documento.querySelector(seletorResultados);
                if (!novosResultados) {
                    throw new Error('A resposta da pesquisa não contém a área de resultados esperada.');
                }

                resultados.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((elemento) => {
                    bootstrap.Tooltip.getInstance(elemento)?.dispose();
                });
                resultados.innerHTML = novosResultados.innerHTML;
                inicializarTooltips(resultados);
                window.history.replaceState(null, '', `${url.pathname}${url.search}`);
            } catch (erro) {
                if (erro.name !== 'AbortError') {
                    console.error(erro);
                    resultados.innerHTML = '<div class="alert alert-warning" role="alert">Não foi possível atualizar os resultados. Tenta novamente.</div>';
                }
            } finally {
                if (!pedidoAtual.signal.aborted) {
                    resultados.setAttribute('aria-busy', 'false');
                }
            }
        };

        campo.addEventListener('input', () => {
            window.clearTimeout(temporizador);
            temporizador = window.setTimeout(pesquisar, 250);
        });

        form.addEventListener('submit', (evento) => {
            evento.preventDefault();
            window.clearTimeout(temporizador);
            pesquisar();
        });
    });
});
