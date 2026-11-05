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