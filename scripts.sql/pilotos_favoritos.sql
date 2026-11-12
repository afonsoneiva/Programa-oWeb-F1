-- 1. Tabela de Pilotos Favoritos dos Utilizadores
CREATE TABLE IF NOT EXISTS utilizador_pilotos_favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilizador_id INT NOT NULL,
    piloto_id INT NOT NULL,
    data_adicao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_user_piloto (utilizador_id, piloto_id),
    CONSTRAINT fk_fav_piloto_user FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id) ON DELETE CASCADE,
    CONSTRAINT fk_fav_piloto_item FOREIGN KEY (piloto_id) REFERENCES pilotos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;