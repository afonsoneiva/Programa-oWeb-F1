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

-- 2. Inserir os 24 Circuitos da Temporada 2025
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
('Circuit Gilles Villeneuve', 'GP do Canadá', 'Montreal', 'Montreal', 4.361, 70, 7, 4),
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