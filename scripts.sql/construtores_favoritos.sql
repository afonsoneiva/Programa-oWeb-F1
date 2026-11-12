-- 2. Tabela de Construtores Favoritos dos Utilizadores
CREATE TABLE IF NOT EXISTS utilizador_construtores_favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilizador_id INT NOT NULL,
    construtor_id INT NOT NULL,
    data_adicao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_user_construtor (utilizador_id, construtor_id),
    CONSTRAINT fk_fav_construtor_user FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id) ON DELETE CASCADE,
    CONSTRAINT fk_fav_construtor_item FOREIGN KEY (construtor_id) REFERENCES construtores(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;