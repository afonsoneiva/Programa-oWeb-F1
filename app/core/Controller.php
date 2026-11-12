<?php
namespace app\core;

/**
 * Classe base dos controladores: instancia models, apresenta views
 * (dentro do layout comum) e reúne a lógica partilhada de pedidos,
 * validação e autenticação.
 */
class Controller {

  /**
   * Todos os pedidos POST têm de trazer um token CSRF válido.
   */
  public function __construct() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$this->validCsrf()) {
      http_response_code(403);
      $this->view('erro', [
        'titulo' => 'Sessão expirada',
        'mensagem' => 'O formulário expirou. Volta atrás, atualiza a página e tenta novamente.'
      ]);
      exit;
    }
  }

  //==================================================================
  // Models e views
  //==================================================================

  /**
   * Método para a invocação de um model.
   */
  public function model(string $model) {
    $classe = 'app\\models\\' . $model;
    return new $classe();
  }

  /**
   * Apresenta uma view dentro do layout (cabeçalho + conteúdo + rodapé).
   * Na view fica disponível o array $data.
   */
  public function view(string $view, array $data = []) {
    $ficheiroView = 'app/views/' . $view . '.php';

    if (!file_exists($ficheiroView)) {
      $this->pageNotFound();
      return;
    }

    require 'app/views/layout/header.php';
    require $ficheiroView;
    require 'app/views/layout/footer.php';
  }

  /**
   * Página de erro 404.
   */
  public function pageNotFound() {
    http_response_code(404);
    $this->view('erro', [
      'titulo' => 'Página não encontrada',
      'mensagem' => 'A página que procuras não existe ou foi removida.'
    ]);
  }

  protected function methodNotAllowed() {
    http_response_code(405);
    $this->view('erro', [
      'titulo' => 'Método não permitido',
      'mensagem' => 'Esta ação só pode ser executada através de um formulário.'
    ]);
  }

  protected function redirect(string $path) {
    header('Location: ' . url($path));
    exit;
  }

  protected function isPost(): bool {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
  }

  //==================================================================
  // Leitura e validação de dados do pedido
  //==================================================================

  protected function validId($id) {
    $validatedId = filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    return $validatedId === false ? null : $validatedId;
  }

  protected function postedString(string $key): string {
    $value = $_POST[$key] ?? '';
    return is_string($value) ? trim($value) : '';
  }

  protected function queryString(string $key): string {
    $value = $_GET[$key] ?? '';
    return is_string($value) ? trim($value) : '';
  }

  protected function stringLength(string $value): int {
    return function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
  }

  /**
   * Valida campos de texto obrigatórios com tamanho máximo.
   *
   * @param array $valores  ['campo' => valor]
   * @param array $regras   ['campo' => ['Etiqueta', tamanhoMáximo]]
   * @return array          mensagens de erro
   */
  protected function validateRequiredStrings(array $valores, array $regras): array {
    $errors = [];
    foreach ($regras as $field => [$label, $maxLength]) {
      $valor = $valores[$field] ?? '';
      if ($valor === '') {
        $errors[] = $label . ' é obrigatório.';
      } elseif ($this->stringLength($valor) > $maxLength) {
        $errors[] = $label . ' excede o limite de ' . $maxLength . ' caracteres.';
      }
    }
    return $errors;
  }

  /**
   * Mensagem de sucesso a partir do parâmetro ?success= do URL.
   * @param string $entidade  ex.: 'Piloto'
   */
  protected function successMessage(string $entidade) {
    $acoes = [
      'created' => 'adicionado',
      'updated' => 'atualizado',
      'deleted' => 'eliminado'
    ];
    $success = $_GET['success'] ?? '';
    if (!is_string($success) || !isset($acoes[$success])) {
      return null;
    }
    return $entidade . ' ' . $acoes[$success] . ' com sucesso.';
  }

  //==================================================================
  // Autenticação
  //==================================================================

  protected function currentUser() {
    return auth_user();
  }

  /**
   * Garante que existe sessão iniciada; caso contrário redireciona
   * para o login, guardando a página pretendida em "next".
   */
  protected function requireLogin() {
    if ($this->currentUser()) {
      return;
    }
    $url = $_GET['url'] ?? '';
    $next = is_string($url) && !$this->isPost() ? $url : '';
    $this->redirect('auth/login' . ($next !== '' ? '?next=' . urlencode($next) : ''));
  }

  protected function validCsrf(): bool {
    $token = $_POST['csrf'] ?? '';
    return is_string($token) && !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
  }
}
