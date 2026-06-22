<?php
// Incluimos todas nuestras clases
include_once 'Personaje.php'; // Aquí están las clases abstractas y sus hijas
include_once 'Arma.php';
include_once 'Arena.php';
include_once 'Duelo.php';
include_once 'Torneo.php';

// 1. Creamos algunos personajes
$guerrero = new Guerrero(1, "Thor", 10, 100.0, 50.0, 0, 0, "Disponible", 80.0, 50.0);
$mago = new Mago(2, "Merlín", 10, 80.0, 100.0, 0, 0, "Disponible", 90.0, 30.0);

// 2. Creamos una Arena y un Arma
$arena = new Arena(1, "Coliseo", 3, 10, "Soleado");
$arena2 = new Arena(3, "Coliseo", 2, 5, "Soleado");
$espada = new Arma(1,"Sagrada","Espada", 20.0,10,"Disponible");
$espada2 = new Arma(67,"PEPE","ROCKET", 20.0,10,"Disponible");

// 3. Inicializamos el Torneo
$torneo = new Torneo([], [], [], []);

// 4. Agregamos al torneo
$torneo->agregarPersonaje($guerrero);
$torneo->agregarPersonaje($mago);
$torneo->agregarArena($arena);
$torneo->agregarArena($arena2);
$torneo->agregarArma($espada);
$torneo->agregarArma($espada2);
// 5. Equipamos el arma
$torneo->equiparArma($guerrero, $espada);
$torneo->equiparArma($mago,$espada2);

// 6. Creamos y realizamos un duelo
$duelo = new Duelo(101, $guerrero, $mago, $arena, "2026-06-19", "pendiente");
$duelo2 = new Duelo(103, $guerrero, $mago, $arena, "2026-06-19", "pendiente");
echo "=== REALIZANDO DUELO ===\n";
echo $torneo->realizarDuelo($duelo) . "\n";

// 7. Listamos resultados
echo $torneo->listarPersonajes();

echo $torneo->rankingPersonajes();

echo $torneo->listarDuelos();

echo $torneo->listarArenas();

echo $torneo->listarArmas();

$torneo->registrarDuelo($duelo);

$torneo->registrarDuelo($duelo2);


echo $torneo->listarDuelos();