/* ==========================================================
   Portal F1 — JavaScript da aplicação
   ========================================================== */

document.addEventListener('DOMContentLoaded', () => {
    const modalConfirmacao = document.querySelector('#confirmarEliminacao');
    const modalTracado = document.querySelector('#modalTracado');
    let formularioAEliminar = null;

    modalTracado?.addEventListener('show.bs.modal', (evento) => {
        const botao = evento.relatedTarget;
        const imagem = modalTracado.querySelector('.imagem-tracado');
        const titulo = modalTracado.querySelector('#modalTracadoTitulo');

        if (!(botao instanceof HTMLElement) || !botao.dataset.imagemTracado || !botao.dataset.nomeCircuito || !imagem || !titulo) {
            console.error('Não foi possível carregar o traçado do circuito selecionado.');
            return;
        }

        titulo.textContent = `Traçado do ${botao.dataset.nomeCircuito}`;
        imagem.src = botao.dataset.imagemTracado;
        imagem.alt = `Traçado do circuito ${botao.dataset.nomeCircuito}`;
    });

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
