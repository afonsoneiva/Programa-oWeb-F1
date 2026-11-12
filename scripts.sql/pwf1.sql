-- ======================================================================
-- Portal F1 — Script de instalação da base de dados
-- Cria a base de dados "pwf1", todas as tabelas e os dados iniciais.
-- Importar no phpMyAdmin (separador "Importar") ou:
--   mysql -u root < scripts.sql/pwf1.sql
-- ======================================================================

CREATE DATABASE IF NOT EXISTS pwf1 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pwf1;

-- Apagar tabelas existentes (ordem inversa das dependências)
DROP TABLE IF EXISTS utilizadores;
DROP TABLE IF EXISTS circuitos;
DROP TABLE IF EXISTS pilotos;
DROP TABLE IF EXISTS construtores;

-- 1. TABELA DE CONSTRUTORES (EQUIPAS)
CREATE TABLE construtores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    pais VARCHAR(50) NOT NULL,
    motor VARCHAR(50) NOT NULL
);

-- Inserir Construtores (IDs de 1 a 10)
INSERT INTO construtores (id, nome, pais, motor) VALUES
(1, 'McLaren-Mercedes', 'Reino Unido', 'Mercedes'),
(2, 'Ferrari', 'Itália', 'Ferrari'),
(3, 'Red Bull Racing-Honda RBPT', 'Áustria', 'Honda RBPT'),
(4, 'Mercedes', 'Alemanha', 'Mercedes'),
(5, 'Aston Martin Aramco-Mercedes', 'Reino Unido', 'Mercedes'),
(6, 'Alpine-Renault', 'França', 'Renault'),
(7, 'Haas-Ferrari', 'Estados Unidos', 'Ferrari'),
(8, 'Racing Bulls-Honda RBPT', 'Itália', 'Honda RBPT'),
(9, 'Williams-Mercedes', 'Reino Unido', 'Mercedes'),
(10, 'Kick Sauber-Ferrari', 'Suíça', 'Ferrari');

-- 2. TABELA DE PILOTOS
CREATE TABLE pilotos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    numero INT NOT NULL,
    nacionalidade VARCHAR(50) NOT NULL,
    construtor_id INT,
    FOREIGN KEY (construtor_id) REFERENCES construtores(id) ON DELETE SET NULL
);

-- Inserir Pilotos da Grelha 2025
INSERT INTO pilotos (id, nome, numero, nacionalidade, construtor_id) VALUES
(1, 'Lando Norris', 4, 'Britânico', 1),
(2, 'Oscar Piastri', 81, 'Australiano', 1),
(3, 'Charles Leclerc', 16, 'Monegasco', 2),
(4, 'Lewis Hamilton', 44, 'Britânico', 2),
(5, 'Max Verstappen', 1, 'Holandês', 3),
(6, 'Liam Lawson', 30, 'Neozelandês', 3),
(7, 'George Russell', 63, 'Britânico', 4),
(8, 'Kimi Antonelli', 12, 'Italiano', 4),
(9, 'Fernando Alonso', 14, 'Espanhol', 5),
(10, 'Lance Stroll', 18, 'Canadiano', 5),
(11, 'Pierre Gasly', 10, 'Francês', 6),
(12, 'Franco Colapinto', 43, 'Argentino', 6),
(13, 'Esteban Ocon', 31, 'Francês', 7),
(14, 'Oliver Bearman', 87, 'Britânico', 7),
(15, 'Yuki Tsunoda', 22, 'Japonês', 8),
(16, 'Isack Hadjar', 6, 'Francês', 8),
(17, 'Alexander Albon', 23, 'Tailandês', 9),
(18, 'Carlos Sainz Jr.', 55, 'Espanhol', 9),
(19, 'Nico Hülkenberg', 27, 'Alemão', 10),
(20, 'Gabriel Bortoleto', 5, 'Brasileiro', 10);

-- 3. TABELA DE CIRCUITOS (Ligada ao Piloto Vencedor e Construtor Vencedor)
CREATE TABLE circuitos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    grande_premio VARCHAR(100) NOT NULL,
    pais VARCHAR(50) NOT NULL,
    cidade VARCHAR(50) NOT NULL,
    extensao_km DECIMAL(4,3) NOT NULL,
    num_voltas INT NOT NULL,
    piloto_vencedor_id INT NULL,
    construtor_vencedor_id INT NULL,
    FOREIGN KEY (piloto_vencedor_id) REFERENCES pilotos(id) ON DELETE SET NULL,
    FOREIGN KEY (construtor_vencedor_id) REFERENCES construtores(id) ON DELETE SET NULL
);

-- Inserir os 24 Circuitos da Temporada 2025
INSERT INTO circuitos (nome, grande_premio, pais, cidade, extensao_km, num_voltas, piloto_vencedor_id, construtor_vencedor_id) VALUES
('Albert Park Circuit', 'GP da Austrália', 'Austrália', 'Melbourne', 5.278, 58, 1, 1),
('Shanghai International Circuit', 'GP da China', 'China', 'Xangai', 5.451, 56, 2, 1),
('Suzuka Circuit', 'GP do Japão', 'Japão', 'Suzuka', 5.807, 53, 5, 3),
('Bahrain International Circuit', 'GP do Bahrain', 'Bahrain', 'Sakhir', 5.412, 57, 2, 1),
('Jeddah Corniche Circuit', 'GP da Arábia Saudita', 'Arábia Saudita', 'Jidá', 6.174, 50, 2, 1),
('Miami International Autodrome', 'GP de Miami', 'Estados Unidos', 'Miami', 5.412, 57, 2, 1),
('Autodromo Enzo e Dino Ferrari', 'GP de Emília-Romanha', 'Itália', 'Ímola', 4.909, 63, 5, 3),
('Circuit de Monaco', 'GP do Mónaco', 'Mónaco', 'Monte Carlo', 3.337, 78, 1, 1),
('Circuit de Barcelona-Catalunya', 'GP de Espanha', 'Espanha', 'Montmeló', 4.657, 66, 2, 1),
('Circuit Gilles Villeneuve', 'GP do Canadá', 'Canadá', 'Montreal', 4.361, 70, 7, 4),
('Red Bull Ring', 'GP da Áustria', 'Áustria', 'Spielberg', 4.318, 71, 1, 1),
('Silverstone Circuit', 'GP da Grã-Bretanha', 'Reino Unido', 'Silverstone', 5.891, 52, 1, 1),
('Circuit de Spa-Francorchamps', 'GP da Bélgica', 'Bélgica', 'Stavelot', 7.004, 44, 2, 1),
('Hungaroring', 'GP da Hungria', 'Hungria', 'Mogyoród', 4.381, 70, 1, 1),
('Circuit Zandvoort', 'GP dos Países Baixos', 'Países Baixos', 'Zandvoort', 4.259, 72, 2, 1),
('Autodromo Nazionale Monza', 'GP de Itália', 'Itália', 'Monza', 5.793, 53, 5, 3),
('Baku City Circuit', 'GP do Azerbaijão', 'Azerbaijão', 'Baku', 6.003, 51, 1, 1),
('Marina Bay Street Circuit', 'GP de Singapura', 'Singapura', 'Singapura', 4.940, 62, 1, 1),
('Circuit of the Americas', 'GP dos Estados Unidos', 'Estados Unidos', 'Austin', 5.513, 56, 3, 2),
('Autódromo Hermanos Rodríguez', 'GP do México', 'México', 'Cidade do México', 4.304, 71, 4, 2),
('Autódromo José Carlos Pace', 'GP de São Paulo', 'Brasil', 'São Paulo', 4.309, 71, 5, 3),
('Las Vegas Strip Circuit', 'GP de Las Vegas', 'Estados Unidos', 'Las Vegas', 6.201, 50, 7, 4),
('Lusail International Circuit', 'GP do Catar', 'Catar', 'Lusail', 5.419, 57, 5, 3),
('Yas Marina Circuit', 'GP de Abu Dhabi', 'Emirados Árabes Unidos', 'Abu Dhabi', 5.281, 58, 1, 1);

-- 4. TABELA DE UTILIZADORES (Autenticação / Sessões)
CREATE TABLE utilizadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    perfil VARCHAR(20) NOT NULL DEFAULT 'utilizador'  -- 'admin' ou 'utilizador'
);

-- Utilizador administrador inicial
-- Email: admin@f1.pt | Password: 123456 (guardada em hash com password_hash() do PHP)
INSERT INTO utilizadores (nome, email, password, perfil) VALUES
('Admin F1', 'admin@f1.pt', '$2y$10$QJ41cj/iNeFwODN1L./yZuddqq8LlJRBDCVqKS/msIOfeISkCYfwe', 'admin');
