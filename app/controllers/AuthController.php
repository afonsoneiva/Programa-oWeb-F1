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
                    // Novo ID de sessão para evitar session fixation
                    session_regenerate_id(true);
                    $_SESSION['user'] = [
                        'id' => (int) $utilizador['id'],
                        'nome' => $utilizador['nome'],
                        'email' => $utilizador['email'],
                        'perfil' => $utilizador['perfil']
                    ];
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
     * Só aceita caminhos internos relativos (evita open redirect).
     */
    private function safeNext($next): string {
        if (!is_string($next) || !preg_match('#^[A-Za-z0-9/_\-?=&%.]*$#', $next) || strpos($next, '//') !== false) {
            return '';
        }
        return ltrim($next, '/');
    }
}
