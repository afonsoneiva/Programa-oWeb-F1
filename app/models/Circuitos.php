<?php
namespace app\models;

use app\core\Model;

class Circuitos extends Model {
    protected $table = 'circuitos';
    protected $distinctOptionFields = ['grande_premio', 'pais', 'cidade'];

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

    //==================================================================
    // Estatísticas (página inicial)
    //==================================================================

    /**
     * Pilotos com mais vitórias (inclui a cor da equipa atual do piloto).
     */
    public function vitoriasPorPiloto(int $limite = 5): array {
        $sql = "SELECT p.id, p.nome, co.nome AS equipa, COUNT(*) AS vitorias
                FROM circuitos c
                JOIN pilotos p ON p.id = c.piloto_vencedor_id
                LEFT JOIN construtores co ON co.id = p.construtor_id
                GROUP BY p.id, p.nome, co.nome
                ORDER BY vitorias DESC, p.nome ASC
                LIMIT ?";
        return array_map(function ($linha) {
            return $linha + ['cor' => Construtores::cor($linha['equipa'])];
        }, $this->db->execQuery($sql, ['i', [$limite]]));
    }

    /**
     * Equipas com mais vitórias.
     */
    public function vitoriasPorConstrutor(int $limite = 5): array {
        $sql = "SELECT co.id, co.nome, COUNT(*) AS vitorias
                FROM circuitos c
                JOIN construtores co ON co.id = c.construtor_vencedor_id
                GROUP BY co.id, co.nome
                ORDER BY vitorias DESC, co.nome ASC
                LIMIT ?";
        return array_map(function ($linha) {
            return $linha + ['cor' => Construtores::cor($linha['nome'])];
        }, $this->db->execQuery($sql, ['i', [$limite]]));
    }

    /**
     * Total de quilómetros de corrida da época (extensão × voltas).
     */
    public function kmTotais(): float {
        $result = $this->db->execQuery("SELECT COALESCE(SUM(extensao_km * num_voltas), 0) AS km FROM circuitos");
        return (float) $result[0]['km'];
    }

    /**
     * Circuitos "recordistas": mais longo, mais curto e com mais voltas.
     */
    public function curiosidades(): array {
        $primeiro = function (string $ordem) {
            $result = $this->db->execQuery("SELECT * FROM circuitos ORDER BY {$ordem} LIMIT 1");
            return $result[0] ?? null;
        };
        return array_filter([
            'mais_longo' => $primeiro('extensao_km DESC'),
            'mais_curto' => $primeiro('extensao_km ASC'),
            'mais_voltas' => $primeiro('num_voltas DESC'),
        ]);
    }

    /**
     * Regra de eliminação de um circuito:
     *  - só o administrador pode eliminar;
     *  - apenas se ainda não tiver resultados (piloto ou equipa vencedora) registados.
     *
     * @return string|null  motivo pelo qual não pode ser eliminado (null = pode)
     */
    public static function motivoBloqueio(array $circuito, $utilizador) {
        if (($utilizador['perfil'] ?? '') !== 'admin') {
            return 'Apenas administradores podem eliminar circuitos.';
        }
        $temPiloto = $circuito['piloto_vencedor_id'] !== null;
        $temEquipa = $circuito['construtor_vencedor_id'] !== null;
        if ($temPiloto && $temEquipa) {
            return 'Não pode ser eliminado: já tem piloto e equipa vencedores registados.';
        }
        if ($temPiloto) {
            return 'Não pode ser eliminado: já tem piloto vencedor registado.';
        }
        if ($temEquipa) {
            return 'Não pode ser eliminado: já tem equipa vencedora registada.';
        }
        return null;
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
