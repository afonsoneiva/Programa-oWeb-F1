<?php
namespace app\models;

use app\core\Model;

class Construtores extends Model {
    protected $table = 'construtores';
    protected $distinctOptionFields = ['pais', 'motor'];

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

        $sql = "SELECT co.*,
                       (SELECT COUNT(*) FROM pilotos p WHERE p.construtor_id = co.id) AS num_pilotos,
                       (SELECT COUNT(*) FROM circuitos ci WHERE ci.construtor_vencedor_id = co.id) AS vitorias
                FROM construtores co";
        $construtores = $this->select($sql, $conditions, $types, $values, 'id ASC');
        return array_map(function ($construtor) {
            return $construtor + ['cor' => self::cor($construtor['nome'])];
        }, $construtores);
    }

    /**
     * Acrescenta ao construtor o número de pilotos e de vitórias
     * (necessários para decidir se pode ser eliminado).
     */
    public function comContagens(array $construtor): array {
        $id = (int) $construtor['id'];
        $pilotos = $this->db->execQuery("SELECT COUNT(*) AS total FROM pilotos WHERE construtor_id = ?", ['i', [$id]]);
        $vitorias = $this->db->execQuery("SELECT COUNT(*) AS total FROM circuitos WHERE construtor_vencedor_id = ?", ['i', [$id]]);
        return $construtor + [
            'num_pilotos' => (int) ($pilotos[0]['total'] ?? 0),
            'vitorias' => (int) ($vitorias[0]['total'] ?? 0)
        ];
    }

    /**
     * Regra de eliminação de uma equipa (o array tem de incluir 'num_pilotos' e 'vitorias'):
     *  - nunca se tiver pilotos ou vitórias;
     *  - o administrador pode apagar qualquer outra;
     *  - o utilizador normal só pode apagar as que criou.
     *
     * @return string|null  motivo pelo qual não pode ser eliminada (null = pode)
     */
    public static function motivoBloqueio(array $construtor, $utilizador) {
        if (!$utilizador) {
            return 'Inicia sessão para eliminar equipas.';
        }
        $pilotos = (int) $construtor['num_pilotos'];
        $vitorias = (int) $construtor['vitorias'];
        $impedimentos = [];
        if ($pilotos > 0) {
            $impedimentos[] = $pilotos . ($pilotos === 1 ? ' piloto' : ' pilotos');
        }
        if ($vitorias > 0) {
            $impedimentos[] = $vitorias . ($vitorias === 1 ? ' vitória' : ' vitórias');
        }
        if ($impedimentos) {
            return 'Não pode ser eliminada: tem ' . implode(' e ', $impedimentos) . '.';
        }
        $criadoPor = $construtor['criado_por'] ?? null;
        $ehAutor = $criadoPor !== null && (int) $criadoPor === (int) $utilizador['id'];
        if (($utilizador['perfil'] ?? '') !== 'admin' && !$ehAutor) {
            return 'Só podes eliminar equipas que tenhas criado.';
        }
        return null;
    }

    public static function podeApagar(array $construtor, $utilizador): bool {
        return self::motivoBloqueio($construtor, $utilizador) === null;
    }

    public function create(array $data) {
        $sql = "INSERT INTO construtores (nome, pais, motor, criado_por) VALUES (?, ?, ?, ?)";
        return $this->db->execQuery($sql, ['sssi', [$data['nome'], $data['pais'], $data['motor'], $data['criado_por']]], $this->table);
    }

    public function update(int $id, array $data) {
        $sql = "UPDATE construtores SET nome = ?, pais = ?, motor = ? WHERE id = ?";
        return $this->db->execQuery($sql, ['sssi', [$data['nome'], $data['pais'], $data['motor'], $id]]);
    }
}
