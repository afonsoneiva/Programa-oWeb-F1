<?php
namespace app\models;

use app\core\Db;

class Pilotos {
    private $db;

    public function __construct() {
        $this->db = new Db();
    }

    public function getAll(array $filters = []) {
        $sql = "SELECT p.*, c.nome AS nome_construtor
                FROM pilotos p 
                LEFT JOIN construtores c ON p.construtor_id = c.id";
        $conditions = [];
        $types = '';
        $values = [];

        if (($filters['nacionalidade'] ?? '') !== '') {
            $conditions[] = 'p.nacionalidade = ?';
            $types .= 's';
            $values[] = $filters['nacionalidade'];
        }
        if (($filters['construtor_id'] ?? '') !== '') {
            $conditions[] = 'p.construtor_id = ?';
            $types .= 'i';
            $values[] = $filters['construtor_id'];
        }
        if (($filters['q'] ?? '') !== '') {
            $conditions[] = '(CAST(p.id AS CHAR) LIKE ? OR p.nome LIKE ? OR CAST(p.numero AS CHAR) LIKE ? OR p.nacionalidade LIKE ? OR c.nome LIKE ?)';
            $search = '%' . $filters['q'] . '%';
            $types .= 'sssss';
            array_push($values, $search, $search, $search, $search, $search);
        }

        if ($conditions) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        $sql .= ' ORDER BY p.id ASC';

        return $this->db->execQuery($sql, $types === '' ? [] : [$types, $values]);
    }

    public function getById(int $id) {
        $sql = "SELECT * FROM pilotos WHERE id = ?";
        $result = $this->db->execQuery($sql, ['i', [$id]]);
        return $result[0] ?? null;
    }

    public function create(array $data) {
        $sql = "INSERT INTO pilotos (nome, numero, nacionalidade, construtor_id) VALUES (?, ?, ?, ?)";
        return $this->db->execQuery(
            $sql,
            ['sisi', [$data['nome'], $data['numero'], $data['nacionalidade'], $data['construtor_id']]],
            'pilotos'
        );
    }

    public function update(int $id, array $data) {
        $sql = "UPDATE pilotos SET nome = ?, numero = ?, nacionalidade = ?, construtor_id = ? WHERE id = ?";
        return $this->db->execQuery(
            $sql,
            ['sisii', [$data['nome'], $data['numero'], $data['nacionalidade'], $data['construtor_id'], $id]]
        );
    }

    public function delete(int $id) {
        $sql = "DELETE FROM pilotos WHERE id = ?";
        return $this->db->execQuery($sql, ['i', [$id]], 'pilotos');
    }
}
?>