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