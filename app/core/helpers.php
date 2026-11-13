<?php
//======================================================================
// Funções auxiliares globais, usadas sobretudo nas views
//======================================================================

/**
 * Escapa um valor para ser apresentado em HTML (previne XSS).
 */
function e($valor): string {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

/**
 * Gera um URL interno da aplicação: url('pilotos/create') -> /Programa-oWeb-F1/pilotos/create
 */
function url(string $caminho = ''): string {
    return BASE_URL . '/' . ltrim($caminho, '/');
}

/**
 * URL de um ficheiro estático com a data de modificação como versão
 * (?v=...), para o browser não usar uma cópia antiga em cache.
 */
function asset(string $caminho): string {
    $versao = @filemtime($caminho);
    return url($caminho) . ($versao ? '?v=' . $versao : '');
}

/**
 * Utilizador autenticado (array) ou null.
 */
function auth_user() {
    return $_SESSION['user'] ?? null;
}

/**
 * Indica se o utilizador autenticado é administrador.
 */
function is_admin(): bool {
    return (auth_user()['perfil'] ?? '') === 'admin';
}

/**
 * Token CSRF da sessão (criado na primeira utilização).
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/**
 * Campo escondido com o token CSRF, a incluir em todos os formulários POST.
 */
function csrf_field(): string {
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}
