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

-- Inserir Circuitos da Época 2025 com Vencedores da Corrida e Construtor
INSERT INTO circuitos (nome, grande_premio, pais, cidade, extensao_km, num_voltas, piloto_vencedor_id, construtor_vencedor_id) VALUES
('Albert Park Circuit', 'GP da Austrália', 'Austrália', 'Melbourne', 5.278, 58, 1, 1),       -- Lando Norris / McLaren
('Shanghai International Circuit', 'GP da China', 'China', 'Xangai', 5.451, 56, 2, 1),           -- Oscar Piastri / McLaren
('Suzuka Circuit', 'GP do Japão', 'Japão', 'Suzuka', 5.807, 53, 5, 3),                          -- Max Verstappen / Red Bull
('Bahrain International Circuit', 'GP do Barém', 'Barém', 'Sakhir', 5.412, 57, 2, 1),          -- Oscar Piastri / McLaren
('Jeddah Corniche Circuit', 'GP da Arábia Saudita', 'Arábia Saudita', 'Jidá', 6.174, 50, 2, 1),  -- Oscar Piastri / McLaren
('Miami International Autodrome', 'GP de Miami', 'EUA', 'Miami', 5.412, 57, 2, 1),             -- Oscar Piastri / McLaren
('Autodromo Enzo e Dino Ferrari', 'GP de Emilia-Romagna', 'Itália', 'Imola', 4.909, 63, 5, 3),   -- Max Verstappen / Red Bull
('Circuit de Monaco', 'GP de Mónaco', 'Mónaco', 'Monte Carlo', 3.337, 78, 1, 1),               -- Lando Norris / McLaren
('Circuit de Barcelona-Catalunya', 'GP de Espanha', 'Espanha', 'Montmeló', 4.657, 66, 2, 1),    -- Oscar Piastri / McLaren
('Circuit Gilles Villeneuve', 'GP do Canadá', 'Canadá', 'Montreal', 4.361, 70, 7, 4),           -- George Russell / Mercedes
('Red Bull Ring', 'GP da Áustria', 'Áustria', 'Spielberg', 4.318, 71, 1, 1),                    -- Lando Norris / McLaren
('Silverstone Circuit', 'GP da Grã-Bretanha', 'Reino Unido', 'Silverstone', 5.891, 52, 1, 1),   -- Lando Norris / McLaren
('Circuit de Spa-Francorchamps', 'GP da Bélgica', 'Bélgica', 'Stavelot', 7.004, 44, 2, 1),       -- Oscar Piastri / McLaren
('Hungaroring', 'GP da Hungria', 'Hungria', 'Mogyoród', 4.381, 70, 1, 1),                       -- Lando Norris / McLaren
('Circuit Zandvoort', 'GP dos Países Baixos', 'Países Baixos', 'Zandvoort', 4.259, 72, 2, 1),  -- Oscar Piastri / McLaren
('Autodromo Nazionale Monza', 'GP de Itália', 'Itália', 'Monza', 5.793, 53, 5, 3);              -- Max Verstappen / Red Bull