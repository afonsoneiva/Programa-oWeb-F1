<?php
namespace app\models;

use app\core\Model;

class Circuitos extends Model {
    protected $table = 'circuitos';

    /**
     * Lista de circuitos com os nomes do piloto e construtor vencedores
     * e filtros opcionais (q, grande_premio, pais, cidade).
     */
    public function getAll(array $filters = []) {
        $sql = "SELECT c.*,
                       p.nome AS piloto_vencedor,
                       co.nome AS construtor_vencedor
                FROM circuitos c
                LEFT JOIN pilotos p ON c.piloto_vencedor_id = p.id
                LEFT JOIN construtores co ON c.construtor_vencedor_id = co.id";
        $conditions = [];
        $types = '';
        $values = [];

        foreach (['grande_premio', 'pais', 'cidade'] as $field) {
            if (($filters[$field] ?? '') !== '') {
                $conditions[] = "c.{$field} = ?";
                $types .= 's';
                $values[] = $filters[$field];
            }
        }
        if (($filters['q'] ?? '') !== '') {
            $conditions[] = '(CAST(c.id AS CHAR) LIKE ? OR c.nome LIKE ? OR c.grande_premio LIKE ? OR c.pais LIKE ? OR c.cidade LIKE ?
                              OR CAST(c.extensao_km AS CHAR) LIKE ? OR CAST(c.num_voltas AS CHAR) LIKE ? OR p.nome LIKE ? OR co.nome LIKE ?)';
            $types .= str_repeat('s', 9);
            array_push($values, ...array_fill(0, 9, '%' . $filters['q'] . '%'));
        }

        return $this->select($sql, $conditions, $types, $values, 'c.id ASC');
    }

    public function create(array $data) {
        $sql = "INSERT INTO circuitos (nome, grande_premio, pais, cidade, extensao_km, num_voltas, piloto_vencedor_id, construtor_vencedor_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        return $this->db->execQuery($sql, ['ssssdiii', $this->valores($data)], $this->table);
    }

    public function update(int $id, array $data) {
        $sql = "UPDATE circuitos
                SET nome = ?, grande_premio = ?, pais = ?, cidade = ?, extensao_km = ?, num_voltas = ?,
                    piloto_vencedor_id = ?, construtor_vencedor_id = ?
                WHERE id = ?";
        return $this->db->execQuery($sql, ['ssssdiiii', [...$this->valores($data), $id]]);
    }

    /**
     * Valores pela ordem das colunas usadas no INSERT/UPDATE.
     */
    private function valores(array $data): array {
        return [
            $data['nome'],
            $data['grande_premio'],
            $data['pais'],
            $data['cidade'],
            $data['extensao_km'],
            $data['num_voltas'],
            $data['piloto_vencedor_id'],
            $data['construtor_vencedor_id']
        ];
    }
}
