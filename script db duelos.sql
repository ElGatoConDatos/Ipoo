CREATE DATABASE IF NOT EXISTS torneo_duelos
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE torneo_duelos;

-- =====================================================
-- TABLA: armas
-- =====================================================

CREATE TABLE armas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    tipo VARCHAR(40) NOT NULL,
    danioBase INT NOT NULL,
    nivelMinimo INT NOT NULL,
    estado ENUM('disponible', 'equipada', 'rota') NOT NULL DEFAULT 'disponible'
);

-- =====================================================
-- TABLA: arenas
-- =====================================================

CREATE TABLE arenas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    dificultad INT NOT NULL,
    capacidadPublico INT NOT NULL,
    clima ENUM('normal', 'lluvia', 'tormenta', 'niebla') NOT NULL DEFAULT 'normal'
);

-- =====================================================
-- TABLA: personajes
-- =====================================================

CREATE TABLE personajes (
    id INT AUTO_INCREMENT PRIMARY KEY,

    nombre VARCHAR(80) NOT NULL,
    tipoPersonaje ENUM('guerrero', 'mago', 'arquero') NOT NULL,

    nivel INT NOT NULL DEFAULT 1,
    puntosVida INT NOT NULL DEFAULT 100,
    energia INT NOT NULL DEFAULT 100,

    duelosGanados INT NOT NULL DEFAULT 0,
    duelosPerdidos INT NOT NULL DEFAULT 0,

    estado ENUM('disponible', 'lesionado', 'retirado') NOT NULL DEFAULT 'disponible',

    idArmaEquipada INT NULL,

    -- Atributos específicos de Guerrero
    fuerza INT NULL,
    armadura INT NULL,

    -- Atributos específicos de Mago
    mana INT NULL,
    inteligencia INT NULL,

    -- Atributos específicos de Arquero
    precisionPersonaje INT NULL,
    velocidad INT NULL,

    CONSTRAINT fk_personajes_armas
        FOREIGN KEY (idArmaEquipada)
        REFERENCES armas(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

-- =====================================================
-- TABLA: duelos
-- =====================================================

CREATE TABLE duelos (
    id INT AUTO_INCREMENT PRIMARY KEY,

    idPersonaje1 INT NOT NULL,
    idPersonaje2 INT NOT NULL,
    idArena INT NOT NULL,

    fecha DATE NOT NULL,

    estado ENUM('pendiente', 'realizado', 'cancelado') NOT NULL DEFAULT 'pendiente',

    idGanador INT NULL,

    poderPersonaje1 DECIMAL(10,2) NULL,
    poderPersonaje2 DECIMAL(10,2) NULL,
    danioAplicado DECIMAL(10,2) NULL,

    CONSTRAINT fk_duelos_personaje1
        FOREIGN KEY (idPersonaje1)
        REFERENCES personajes(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_duelos_personaje2
        FOREIGN KEY (idPersonaje2)
        REFERENCES personajes(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_duelos_arena
        FOREIGN KEY (idArena)
        REFERENCES arenas(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_duelos_ganador
        FOREIGN KEY (idGanador)
        REFERENCES personajes(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

-- =====================================================
-- VALIDACIÓN: evitar duelo contra sí mismo
-- =====================================================

ALTER TABLE duelos
ADD CONSTRAINT chk_duelo_personajes_distintos
CHECK (idPersonaje1 <> idPersonaje2);
