-- 3. Tabela de Circuitos Favoritos dos Utilizadores
CREATE TABLE IF NOT EXISTS utilizador_circuitos_favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilizador_id INT NOT NULL,
    circuito_id INT NOT NULL,
    data_adicao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_user_circuito (utilizador_id, circuito_id),
    CONSTRAINT fk_fav_circuito_user FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id) ON DELETE CASCADE,
    CONSTRAINT fk_fav_circuito_item FOREIGN KEY (circuito_id) REFERENCES circuitos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;