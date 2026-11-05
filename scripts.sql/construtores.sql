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