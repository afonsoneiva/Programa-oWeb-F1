<?php
namespace app\models;

use app\core\Model;

class Utilizadores extends Model {
    protected $table = 'utilizadores';

    public function getByEmail(string $email) {
        $result = $this->db->execQuery("SELECT * FROM utilizadores WHERE email = ?", ['s', [$email]]);
        return $result[0] ?? null;
    }

    /**
     * Cria uma conta nova. A password é guardada em hash.
     */
    public function create(string $nome, string $email, string $password, string $perfil = 'utilizador') {
        $sql = "INSERT INTO utilizadores (nome, email, password, perfil) VALUES (?, ?, ?, ?)";
        return $this->db->execQuery(
            $sql,
            ['ssss', [$nome, $email, password_hash($password, PASSWORD_DEFAULT), $perfil]],
            $this->table
        );
    }

    public function updatePassword(int $id, string $hash) {
        return $this->db->execQuery("UPDATE utilizadores SET password = ? WHERE id = ?", ['si', [$hash, $id]]);
    }

    /**
     * Verifica as credenciais e devolve o utilizador (ou null).
     * Passwords antigas guardadas em texto simples são convertidas
     * automaticamente para hash no primeiro login bem-sucedido.
     */
    public function authenticate(string $email, string $password) {
        $utilizador = $this->getByEmail($email);
        if (!$utilizador) {
            // Trabalho equivalente a um verify, para o tempo de resposta não revelar se o email existe
            password_hash($password, PASSWORD_DEFAULT);
            return null;
        }

        $guardada = $utilizador['password'];
        $eHash = !empty(password_get_info($guardada)['algo']);

        $valida = $eHash ? password_verify($password, $guardada) : hash_equals($guardada, $password);
        if (!$valida) {
            return null;
        }

        if (!$eHash || password_needs_rehash($guardada, PASSWORD_DEFAULT)) {
            $this->updatePassword((int) $utilizador['id'], password_hash($password, PASSWORD_DEFAULT));
        }

        return $utilizador;
    }
}
