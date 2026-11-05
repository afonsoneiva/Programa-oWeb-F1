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
    public function getAll() {
        $sql = "SELECT c.*, 
                       p.nome AS piloto_vencedor, 
                       co.nome AS construtor_vencedor
                FROM circuitos c
                LEFT JOIN pilotos p ON c.piloto_vencedor_id = p.id
                LEFT JOIN construtores co ON c.construtor_vencedor_id = co.id
                ORDER BY c.id ASC";
        return $this->db->execQuery($sql);
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