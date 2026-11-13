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
        $sql = "SELECT p.*, c.nome AS nome_construtor,
                       (SELECT COUNT(*) FROM circuitos ci WHERE ci.piloto_vencedor_id = p.id) AS vitorias
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

    /**
     * Número de corridas ganhas pelo piloto.
     */
    public function vitorias(int $id): int {
        $result = $this->db->execQuery("SELECT COUNT(*) AS total FROM circuitos WHERE piloto_vencedor_id = ?", ['i', [$id]]);
        return (int) ($result[0]['total'] ?? 0);
    }

    /**
     * Regra de eliminação de um piloto (o array tem de incluir 'vitorias'):
     *  - nunca se tiver vitórias;
     *  - o administrador pode apagar qualquer outro;
     *  - o utilizador normal só pode apagar os que criou.
     *
     * @return string|null  motivo pelo qual não pode ser eliminado (null = pode)
     */
    public static function motivoBloqueio(array $piloto, $utilizador) {
        if (!$utilizador) {
            return 'Inicia sessão para eliminar pilotos.';
        }
        $vitorias = (int) $piloto['vitorias'];
        if ($vitorias > 0) {
            return 'Não pode ser eliminado: tem ' . $vitorias . ($vitorias === 1 ? ' vitória.' : ' vitórias.');
        }
        $ehAutor = $piloto['criado_por'] !== null && (int) $piloto['criado_por'] === (int) $utilizador['id'];
        if (($utilizador['perfil'] ?? '') !== 'admin' && !$ehAutor) {
            return 'Só podes eliminar pilotos que tenhas criado.';
        }
        return null;
    }

    public static function podeApagar(array $piloto, $utilizador): bool {
        return self::motivoBloqueio($piloto, $utilizador) === null;
    }

    public function create(array $data) {
        $sql = "INSERT INTO pilotos (nome, numero, nacionalidade, construtor_id, criado_por) VALUES (?, ?, ?, ?, ?)";
        return $this->db->execQuery(
            $sql,
            ['sisii', [$data['nome'], $data['numero'], $data['nacionalidade'], $data['construtor_id'], $data['criado_por']]],
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
