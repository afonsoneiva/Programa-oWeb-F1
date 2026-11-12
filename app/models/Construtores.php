<?php
namespace app\models;

use app\core\Model;

class Construtores extends Model {
    protected $table = 'construtores';

    /** Cor oficial de cada equipa (usada nos badges das listagens). */
    private const CORES = [
        'McLaren-Mercedes' => '#FF8000',
        'Ferrari' => '#DC0000',
        'Red Bull Racing-Honda RBPT' => '#1E41FF',
        'Mercedes' => '#00D2BE',
        'Aston Martin Aramco-Mercedes' => '#006F62',
        'Alpine-Renault' => '#0090FF',
        'Haas-Ferrari' => '#B6BABD',
        'Racing Bulls-Honda RBPT' => '#6692FF',
        'Williams-Mercedes' => '#005AFF',
        'Kick Sauber-Ferrari' => '#52E252',
    ];
    private const COR_PADRAO = '#6C757D';

    /**
     * Cor da equipa a partir do nome (ou cor neutra, se não for conhecida).
     */
    public static function cor($nome): string {
        return self::CORES[$nome ?? ''] ?? self::COR_PADRAO;
    }

    /**
     * Lista de construtores com filtros opcionais (q, pais, motor).
     */
    public function getAll(array $filters = []) {
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
            $types .= 'ssss';
            array_push($values, ...array_fill(0, 4, '%' . $filters['q'] . '%'));
        }

        $construtores = $this->select('SELECT * FROM construtores', $conditions, $types, $values, 'id ASC');
        return array_map(function ($construtor) {
            return $construtor + ['cor' => self::cor($construtor['nome'])];
        }, $construtores);
    }

    public function create(array $data) {
        $sql = "INSERT INTO construtores (nome, pais, motor) VALUES (?, ?, ?)";
        return $this->db->execQuery($sql, ['sss', [$data['nome'], $data['pais'], $data['motor']]], $this->table);
    }

    public function update(int $id, array $data) {
        $sql = "UPDATE construtores SET nome = ?, pais = ?, motor = ? WHERE id = ?";
        return $this->db->execQuery($sql, ['sssi', [$data['nome'], $data['pais'], $data['motor'], $id]]);
    }
}
