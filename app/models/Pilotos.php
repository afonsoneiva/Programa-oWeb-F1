<?php
namespace app\models;

use app\core\Model;

class Pilotos extends Model {
    protected $table = 'pilotos';

    /**
     * Lista de pilotos (com o nome da equipa) e filtros opcionais
     * (q, nacionalidade, construtor_id).
     */
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
            $types .= 'sssss';
            array_push($values, ...array_fill(0, 5, '%' . $filters['q'] . '%'));
        }

        $pilotos = $this->select($sql, $conditions, $types, $values, 'p.id ASC');
        return array_map(function ($piloto) {
            return $piloto + ['cor_equipa' => Construtores::cor($piloto['nome_construtor'])];
        }, $pilotos);
    }

    public function create(array $data) {
        $sql = "INSERT INTO pilotos (nome, numero, nacionalidade, construtor_id) VALUES (?, ?, ?, ?)";
        return $this->db->execQuery(
            $sql,
            ['sisi', [$data['nome'], $data['numero'], $data['nacionalidade'], $data['construtor_id']]],
            $this->table
        );
    }

    public function update(int $id, array $data) {
        $sql = "UPDATE pilotos SET nome = ?, numero = ?, nacionalidade = ?, construtor_id = ? WHERE id = ?";
        return $this->db->execQuery(
            $sql,
            ['sisii', [$data['nome'], $data['numero'], $data['nacionalidade'], $data['construtor_id'], $id]]
        );
    }
}
