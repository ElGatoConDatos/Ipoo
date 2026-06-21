
USE torneo_duelos;

INSERT INTO armas (nombre, tipo, danioBase, nivelMinimo, estado) VALUES
('Espada de Hierro', 'espada', 20, 1, 'disponible'),
('Báculo Arcano', 'baculo', 25, 2, 'disponible'),
('Arco Élfico', 'arco', 18, 1, 'disponible'),
('Hacha Pesada', 'hacha', 30, 3, 'disponible'),
('Daga Rápida', 'daga', 12, 1, 'disponible');

INSERT INTO arenas (nombre, dificultad, capacidadPublico, clima) VALUES
('Coliseo Central', 3, 5000, 'normal'),
('Bosque Nublado', 4, 1200, 'niebla'),
('Templo de la Tormenta', 5, 2000, 'tormenta'),
('Puerto Bajo la Lluvia', 2, 800, 'lluvia');

INSERT INTO personajes (
    nombre, tipoPersonaje, nivel, puntosVida, energia,
    fuerza, armadura
) VALUES
('Thorgar', 'guerrero', 3, 100, 90, 18, 12);

INSERT INTO personajes (
    nombre, tipoPersonaje, nivel, puntosVida, energia,
    mana, inteligencia
) VALUES
('Elandra', 'mago', 4, 80, 100, 35, 20);

INSERT INTO personajes (
    nombre, tipoPersonaje, nivel, puntosVida, energia,
    precisionPersonaje, velocidad
) VALUES
('Lorian', 'arquero', 2, 90, 95, 22, 18);

INSERT INTO personajes (
    nombre, tipoPersonaje, nivel, puntosVida, energia,
    fuerza, armadura
) VALUES
('Brakka', 'guerrero', 1, 100, 80, 14, 8);