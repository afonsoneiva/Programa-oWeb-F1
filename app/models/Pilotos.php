<?php
namespace app\models;

use app\core\Model;

class Pilotos extends Model {
    protected $table = 'pilotos';
    protected $distinctOptionFields = ['nacionalidade'];

    private const FOTOS = [
        'Lando Norris' => 'https://media.formula1.com/image/upload/c_thumb,g_face,w_96,h_96/q_auto/d_common:f1:2025:fallback:driver:2025fallbackdriverright.webp/v1740000001/common/f1/2025/mclaren/lannor01/2025mclarenlannor01right.webp',
        'Oscar Piastri' => 'https://media.formula1.com/image/upload/c_thumb,g_face,w_96,h_96/q_auto/d_common:f1:2025:fallback:driver:2025fallbackdriverright.webp/v1740000001/common/f1/2025/mclaren/oscpia01/2025mclarenoscpia01right.webp',
        'Charles Leclerc' => 'https://media.formula1.com/image/upload/c_thumb,g_face,w_96,h_96/q_auto/d_common:f1:2025:fallback:driver:2025fallbackdriverright.webp/v1740000001/common/f1/2025/ferrari/chalec01/2025ferrarichalec01right.webp',
        'Lewis Hamilton' => 'https://media.formula1.com/image/upload/c_thumb,g_face,w_96,h_96/q_auto/d_common:f1:2025:fallback:driver:2025fallbackdriverright.webp/v1740000001/common/f1/2025/ferrari/lewham01/2025ferrarilewham01right.webp',
        'Max Verstappen' => 'https://media.formula1.com/image/upload/c_thumb,g_face,w_96,h_96/q_auto/d_common:f1:2025:fallback:driver:2025fallbackdriverright.webp/v1740000001/common/f1/2025/redbullracing/maxver01/2025redbullracingmaxver01right.webp',
        'Liam Lawson' => 'https://media.formula1.com/image/upload/c_thumb,g_face,w_96,h_96/q_auto/d_common:f1:2025:fallback:driver:2025fallbackdriverright.webp/v1740000001/common/f1/2025/redbullracing/lialaw01/2025redbullracinglialaw01right.webp',
        'George Russell' => 'https://media.formula1.com/image/upload/c_thumb,g_face,w_96,h_96/q_auto/d_common:f1:2025:fallback:driver:2025fallbackdriverright.webp/v1740000001/common/f1/2025/mercedes/georus01/2025mercedesgeorus01right.webp',
        'Kimi Antonelli' => 'https://media.formula1.com/image/upload/c_thumb,g_face,w_96,h_96/q_auto/d_common:f1:2025:fallback:driver:2025fallbackdriverright.webp/v1740000001/common/f1/2025/mercedes/andant01/2025mercedesandant01right.webp',
        'Fernando Alonso' => 'https://media.formula1.com/image/upload/c_thumb,g_face,w_96,h_96/q_auto/d_common:f1:2025:fallback:driver:2025fallbackdriverright.webp/v1740000001/common/f1/2025/astonmartin/feralo01/2025astonmartinferalo01right.webp',
        'Lance Stroll' => 'https://media.formula1.com/image/upload/c_thumb,g_face,w_96,h_96/q_auto/d_common:f1:2025:fallback:driver:2025fallbackdriverright.webp/v1740000001/common/f1/2025/astonmartin/lanstr01/2025astonmartinlanstr01right.webp',
        'Pierre Gasly' => 'https://media.formula1.com/image/upload/c_thumb,g_face,w_96,h_96/q_auto/d_common:f1:2025:fallback:driver:2025fallbackdriverright.webp/v1740000001/common/f1/2025/alpine/piegas01/2025alpinepiegas01right.webp',
        'Franco Colapinto' => 'https://media.formula1.com/image/upload/c_thumb,g_face,w_96,h_96/q_auto/d_common:f1:2025:fallback:driver:2025fallbackdriverright.webp/v1740000001/common/f1/2025/alpine/fracol01/2025alpinefracol01right.webp',
        'Esteban Ocon' => 'https://media.formula1.com/image/upload/c_thumb,g_face,w_96,h_96/q_auto/d_common:f1:2025:fallback:driver:2025fallbackdriverright.webp/v1740000001/common/f1/2025/haasf1team/estoco01/2025haasf1teamestoco01right.webp',
        'Oliver Bearman' => 'https://media.formula1.com/image/upload/c_thumb,g_face,w_96,h_96/q_auto/d_common:f1:2025:fallback:driver:2025fallbackdriverright.webp/v1740000001/common/f1/2025/haasf1team/olibea01/2025haasf1teamolibea01right.webp',
        'Yuki Tsunoda' => 'https://media.formula1.com/image/upload/c_thumb,g_face,w_96,h_96/q_auto/d_common:f1:2025:fallback:driver:2025fallbackdriverright.webp/v1740000001/common/f1/2025/racingbulls/yuktsu01/2025racingbullsyuktsu01right.webp',
        'Isack Hadjar' => 'https://media.formula1.com/image/upload/c_thumb,g_face,w_96,h_96/q_auto/d_common:f1:2025:fallback:driver:2025fallbackdriverright.webp/v1740000001/common/f1/2025/racingbulls/isahad01/2025racingbullsisahad01right.webp',
        'Alexander Albon' => 'https://media.formula1.com/image/upload/c_thumb,g_face,w_96,h_96/q_auto/d_common:f1:2025:fallback:driver:2025fallbackdriverright.webp/v1740000001/common/f1/2025/williams/alealb01/2025williamsalealb01right.webp',
        'Carlos Sainz Jr.' => 'https://media.formula1.com/image/upload/c_thumb,g_face,w_96,h_96/q_auto/d_common:f1:2025:fallback:driver:2025fallbackdriverright.webp/v1740000001/common/f1/2025/williams/carsai01/2025williamscarsai01right.webp',
        'Nico Hülkenberg' => 'https://media.formula1.com/image/upload/c_thumb,g_face,w_96,h_96/q_auto/d_common:f1:2025:fallback:driver:2025fallbackdriverright.webp/v1740000001/common/f1/2025/kicksauber/nichul01/2025kicksaubernichul01right.webp',
        'Gabriel Bortoleto' => 'https://media.formula1.com/image/upload/c_thumb,g_face,w_96,h_96/q_auto/d_common:f1:2025:fallback:driver:2025fallbackdriverright.webp/v1740000001/common/f1/2025/kicksauber/gabbor01/2025kicksaubergabbor01right.webp',
    ];

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
            return $piloto + [
                'cor_equipa' => Construtores::cor($piloto['nome_construtor']),
                'foto' => self::FOTOS[$piloto['nome']] ?? null,
            ];
        }, $pilotos);
    }

    /**
     * Número de corridas ganhas pelo piloto.
     */
    public function vitorias(int $id): int {
        $result = $this->db->execQuery("SELECT COUNT(*) AS total FROM circuitos WHERE piloto_vencedor_id = ?", ['i', [$id]]);
        return (int) ($result[0]['total'] ?? 0);
    }

    public function numeroEmUso(int $numero, ?int $excluirId = null): bool {
        $sql = 'SELECT COUNT(*) AS total FROM pilotos WHERE numero = ?';
        $types = 'i';
        $values = [$numero];

        if ($excluirId !== null) {
            $sql .= ' AND id <> ?';
            $types .= 'i';
            $values[] = $excluirId;
        }

        $result = $this->db->execQuery($sql, [$types, $values]);
        return (int) ($result[0]['total'] ?? 0) > 0;
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
        $criadoPor = $piloto['criado_por'] ?? null;
        $ehAutor = $criadoPor !== null && (int) $criadoPor === (int) $utilizador['id'];
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
