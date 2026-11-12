<?php
namespace app\controllers;

use app\core\Controller;

class AuthController extends Controller {

    public function index() {
        $this->login();
    }

    public function login() {
        if ($this->currentUser()) {
            $this->redirect('');
        }

        $errors = [];
        $email = '';
        $next = $this->safeNext($_POST['next'] ?? $_GET['next'] ?? '');

        if ($this->isPost()) {
            // O token CSRF já foi validado no construtor de Controller
            $email = $this->postedString('email');
            $password = $_POST['password'] ?? '';
            $password = is_string($password) ? $password : '';

            if ($email === '' || $password === '') {
                $errors[] = 'Indica o email e a password.';
            } else {
                $utilizador = $this->model('Utilizadores')->authenticate($email, $password);
                if ($utilizador) {
                    $this->startSession($utilizador);
                    $this->redirect($next);
                }
                $errors[] = 'Email ou password incorretos.';
            }
        }

        $this->view('auth/login', [
            'titulo' => 'Iniciar Sessão',
            'email' => $email,
            'next' => $next,
            'errors' => $errors
        ]);
    }

    /**
     * Criação de conta (perfil "utilizador": pode adicionar e editar, mas não eliminar).
     */
    public function register() {
        if ($this->currentUser()) {
            $this->redirect('');
        }

        $errors = [];
        $conta = ['nome' => '', 'email' => ''];

        if ($this->isPost()) {
            $conta = [
                'nome' => $this->postedString('nome'),
                'email' => $this->postedString('email')
            ];
            $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
            $confirmacao = is_string($_POST['password_confirm'] ?? null) ? $_POST['password_confirm'] : '';
            $utilizadoresModel = $this->model('Utilizadores');

            $errors = $this->validateRequiredStrings($conta, [
                'nome' => ['Nome', 100],
                'email' => ['Email', 150]
            ]);
            if ($conta['email'] !== '' && !filter_var($conta['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Indica um email válido.';
            } elseif ($conta['email'] !== '' && $utilizadoresModel->getByEmail($conta['email'])) {
                $errors[] = 'Já existe uma conta com este email.';
            }
            if ($this->stringLength($password) < 8) {
                $errors[] = 'A password tem de ter pelo menos 8 caracteres.';
            } elseif ($password !== $confirmacao) {
                $errors[] = 'As passwords não coincidem.';
            }

            if (!$errors) {
                $utilizador = $utilizadoresModel->create($conta['nome'], $conta['email'], $password);
                $this->startSession($utilizador);
                $this->redirect('?success=registered');
            }
        }

        $this->view('auth/register', [
            'titulo' => 'Criar Conta',
            'conta' => $conta,
            'errors' => $errors
        ]);
    }

    public function logout() {
        if (!$this->isPost()) {
            $this->methodNotAllowed();
            return;
        }

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        $this->redirect('');
    }

    /**
     * Guarda o utilizador na sessão (com novo ID de sessão, para evitar session fixation).
     */
    private function startSession(array $utilizador) {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int) $utilizador['id'],
            'nome' => $utilizador['nome'],
            'email' => $utilizador['email'],
            'perfil' => $utilizador['perfil']
        ];
    }

    /**
     * Só aceita caminhos internos relativos (evita open redirect).
     */
    private function safeNext($next): string {
        if (!is_string($next) || !preg_match('#^[A-Za-z0-9/_\-?=&%.]*$#', $next) || strpos($next, '//') !== false) {
            return '';
        }
        return ltrim($next, '/');
    }
}
