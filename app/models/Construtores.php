<?php
namespace app\models;

use app\core\Db;

class Construtores {
    private $db;

    public function __construct() {
        $this->db = new Db();
    }

    /**
     * Obtém a lista completa de construtores/equipas
     */
    public function getAll(array $filters = []) {
        $sql = "SELECT * FROM construtores";
        $conditions = [];
        $types = '';
        $values = [];

        foreach (['pais', 'motor'] as $field) {
            if (($filters[$field] ?? '') !== '') {
                $conditions[] = "{$field} = ?";
                $types .= 's';
                $values[] = $filters[$field];
            }
        }

        if (($filters['q'] ?? '') !== '') {
            $conditions[] = '(CAST(id AS CHAR) LIKE ? OR nome LIKE ? OR pais LIKE ? OR motor LIKE ?)';
            $search = '%' . $filters['q'] . '%';
            $types .= 'ssss';
            array_push($values, $search, $search, $search, $search);
        }

        if ($conditions) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        $sql .= ' ORDER BY id ASC';

        return $this->db->execQuery($sql, $types === '' ? [] : [$types, $values]);
    }

    public function getById(int $id) {
        $sql = "SELECT * FROM construtores WHERE id = ?";
        $result = $this->db->execQuery($sql, ['i', [$id]]);
        return $result[0] ?? null;
    }

    public function create(array $data) {
        $sql = "INSERT INTO construtores (nome, pais, motor) VALUES (?, ?, ?)";
        return $this->db->execQuery($sql, ['sss', [$data['nome'], $data['pais'], $data['motor']]], 'construtores');
    }

    public function update(int $id, array $data) {
        $sql = "UPDATE construtores SET nome = ?, pais = ?, motor = ? WHERE id = ?";
        return $this->db->execQuery($sql, ['sssi', [$data['nome'], $data['pais'], $data['motor'], $id]]);
    }

    public function delete(int $id) {
        $sql = "DELETE FROM construtores WHERE id = ?";
        return $this->db->execQuery($sql, ['i', [$id]], 'construtores');
    }
}
?>