-- 4. TABELA DE UTILIZADORES (Para Autenticação / Sessões)
CREATE TABLE utilizadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    perfil VARCHAR(20) DEFAULT 'admin'
);

-- Inserir Utilizador Administrador Inicial
INSERT INTO utilizadores (nome, email, password, perfil) VALUES
('Admin F1', 'admin@f1.pt', '123456', 'admin');

-- 1. Adicionar as colunas para os favoritos na tabela pilotos
ALTER TABLE pilotos
ADD COLUMN piloto_favorito_id INT NULL AFTER construtor_id,
ADD COLUMN construtor_favorito_id INT NULL AFTER piloto_favorito_id,
ADD COLUMN circuito_favorito_id INT NULL AFTER construtor_favorito_id;

-- 2. Adicionar as chaves estrangeiras (garantindo integridade referencial)
ALTER TABLE pilotos
ADD CONSTRAINT fk_piloto_fav_piloto 
    FOREIGN KEY (piloto_favorito_id) REFERENCES pilotos(id) ON DELETE SET NULL,
ADD CONSTRAINT fk_piloto_fav_construtor 
    FOREIGN KEY (construtor_favorito_id) REFERENCES construtores(id) ON DELETE SET NULL,
ADD CONSTRAINT fk_piloto_fav_circuito 
    FOREIGN KEY (circuito_favorito_id) REFERENCES circuitos(id) ON DELETE SET NULL;

-- 3. Exemplo de atualização para preencher dados iniciais (ajusta os IDs conforme a tua tabela)
UPDATE pilotos SET piloto_favorito_id = 1, construtor_favorito_id = 1, circuito_favorito_id = 1 WHERE id = 2;
UPDATE pilotos SET piloto_favorito_id = 2, construtor_favorito_id = 2, circuito_favorito_id = 3 WHERE id = 1;