<?php
namespace app\models;

use app\core\Db;

class Circuitos {
    private $db;

    public function __construct() {
        $this->db = new Db();
    }

    /**
     * Obtém a lista completa de circuitos com os nomes do piloto e construtor vencedores
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
            $conditions[] = '(CAST(c.id AS CHAR) LIKE ? OR c.nome LIKE ? OR c.grande_premio LIKE ? OR c.pais LIKE ? OR c.cidade LIKE ? OR CAST(c.extensao_km AS CHAR) LIKE ? OR CAST(c.num_voltas AS CHAR) LIKE ? OR p.nome LIKE ? OR co.nome LIKE ?)';
            $search = '%' . $filters['q'] . '%';
            $types .= 'sssssssss';
            array_push($values, $search, $search, $search, $search, $search, $search, $search, $search, $search);
        }

        if ($conditions) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        $sql .= ' ORDER BY c.id ASC';

        return $this->db->execQuery($sql, $types === '' ? [] : [$types, $values]);
    }

    public function getById(int $id) {
        $sql = "SELECT * FROM circuitos WHERE id = ?";
        $result = $this->db->execQuery($sql, ['i', [$id]]);
        return $result[0] ?? null;
    }

    public function create(array $data) {
        $sql = "INSERT INTO circuitos (nome, grande_premio, pais, cidade, extensao_km, num_voltas, piloto_vencedor_id, construtor_vencedor_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        return $this->db->execQuery(
            $sql,
            ['ssssdiii', [
                $data['nome'],
                $data['grande_premio'],
                $data['pais'],
                $data['cidade'],
                $data['extensao_km'],
                $data['num_voltas'],
                $data['piloto_vencedor_id'],
                $data['construtor_vencedor_id']
            ]],
            'circuitos'
        );
    }

    public function update(int $id, array $data) {
        $sql = "UPDATE circuitos
                SET nome = ?, grande_premio = ?, pais = ?, cidade = ?, extensao_km = ?, num_voltas = ?,
                    piloto_vencedor_id = ?, construtor_vencedor_id = ?
                WHERE id = ?";
        return $this->db->execQuery(
            $sql,
            ['ssssdiiii', [
                $data['nome'],
                $data['grande_premio'],
                $data['pais'],
                $data['cidade'],
                $data['extensao_km'],
                $data['num_voltas'],
                $data['piloto_vencedor_id'],
                $data['construtor_vencedor_id'],
                $id
            ]]
        );
    }

    public function delete(int $id) {
        $sql = "DELETE FROM circuitos WHERE id = ?";
        return $this->db->execQuery($sql, ['i', [$id]], 'circuitos');
    }
}
?>