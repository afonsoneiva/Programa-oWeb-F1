<?php
namespace app\core;

/**
 * Classe base dos models.
 * Disponibiliza a ligação à base de dados e as operações comuns
 * a todas as tabelas (obter por id e eliminar).
 */
abstract class Model {
    protected $db;

    /** Nome da tabela associada ao model (definido em cada subclasse). */
    protected $table;
    protected $distinctOptionFields = [];

    public function __construct() {
        $this->db = new Db();
    }

    public function getById(int $id) {
        $result = $this->db->execQuery("SELECT * FROM {$this->table} WHERE id = ?", ['i', [$id]]);
        return $result[0] ?? null;
    }

    public function count(): int {
        $result = $this->db->execQuery("SELECT COUNT(*) AS total FROM {$this->table}");
        return (int) ($result[0]['total'] ?? 0);
    }

    public function distinctOptions(string $field): array {
        if (!in_array($field, $this->distinctOptionFields, true)) {
            throw new \InvalidArgumentException('Campo de opções inválido: ' . $field);
        }

        $result = $this->db->execQuery(
            "SELECT DISTINCT `{$field}` AS option_value FROM `{$this->table}`
             WHERE `{$field}` IS NOT NULL AND `{$field}` <> ''
             ORDER BY `{$field}` ASC"
        );
        return array_column($result, 'option_value');
    }

    public function delete(int $id) {
        return $this->db->execQuery("DELETE FROM {$this->table} WHERE id = ?", ['i', [$id]], $this->table);
    }

    /**
     * Executa um SELECT com condições opcionais (WHERE ... AND ...).
     *
     * @param string $sql        SELECT base (sem WHERE)
     * @param array  $conditions condições SQL com "?"
     * @param string $types      tipos dos parâmetros (bind_param)
     * @param array  $values     valores dos parâmetros
     * @param string $orderBy    cláusula ORDER BY
     */
    protected function select(string $sql, array $conditions, string $types, array $values, string $orderBy) {
        if ($conditions) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        $sql .= ' ORDER BY ' . $orderBy;

        return $this->db->execQuery($sql, $types === '' ? [] : [$types, $values]);
    }
}
