<?php
namespace app\models;

use app\core\Model;

class Favoritos extends Model {
    protected const ENTIDADES = [
        'pilotos' => ['tabela' => 'utilizador_pilotos_favoritos', 'coluna' => 'piloto_id'],
        'construtores' => ['tabela' => 'utilizador_construtores_favoritos', 'coluna' => 'construtor_id'],
        'circuitos' => ['tabela' => 'utilizador_circuitos_favoritos', 'coluna' => 'circuito_id']
    ];

    public function getIds(string $entidade, int $utilizadorId): array {
        $config = $this->configuracao($entidade);
        $favoritos = $this->db->execQuery(
            "SELECT {$config['coluna']} AS item_id FROM {$config['tabela']} WHERE utilizador_id = ?",
            ['i', [$utilizadorId]]
        );

        return array_map('intval', array_column($favoritos, 'item_id'));
    }

    public function toggle(string $entidade, int $utilizadorId, int $itemId): void {
        $config = $this->configuracao($entidade);
        $existente = $this->db->execQuery(
            "SELECT id FROM {$config['tabela']} WHERE utilizador_id = ? AND {$config['coluna']} = ?",
            ['ii', [$utilizadorId, $itemId]]
        );

        if ($existente) {
            $this->db->execQuery(
                "DELETE FROM {$config['tabela']} WHERE utilizador_id = ? AND {$config['coluna']} = ?",
                ['ii', [$utilizadorId, $itemId]]
            );
            return;
        }

        $this->db->execQuery(
            "INSERT INTO {$config['tabela']} (utilizador_id, {$config['coluna']}) VALUES (?, ?)",
            ['ii', [$utilizadorId, $itemId]],
            $config['tabela']
        );
    }

    private function configuracao(string $entidade): array {
        if (!isset(self::ENTIDADES[$entidade])) {
            throw new \InvalidArgumentException('Entidade de favoritos inválida.');
        }

        return self::ENTIDADES[$entidade];
    }
}
