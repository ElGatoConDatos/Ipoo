<?php
include_once 'PDO/BaseDatos.php';
include_once 'PDO/ArmaDao.php';
include_once 'PDO/PersonajeDao.php';
include_once 'PDO/ArenaDao.php';
include_once 'PDO/DueloDao.php';
include_once 'Arma.php';
include_once 'Personaje.php';
include_once 'Arena.php';
include_once 'Duelo.php';
include_once 'Torneo.php';

$bd = new BaseDatos();
$conexion = $bd->getConexion();
$armaDAO = new ArmaDAO($conexion);
$personajeDAO = new PersonajeDAO($conexion);
$arenaDAO = new ArenaDAO($conexion);
$dueloDAO = new DueloDAO($conexion);

function menu()
{
    echo "\n";
    echo "===== TORNEO DE DUELOS =====\n";
    echo "1 - Registrar personaje\n";   
    echo "2 - Registrar arma\n";    
    echo "3 - Registrar arena\n";
    echo "4 - Equipar arma\n";
    echo "5 - Registrar duelo\n";
    echo "6 - Ejecutar duelos pendientes\n";
    echo "7 - Recuperar personaje lesionado\n";
    echo "8 - Ranking personajes\n";
    echo "9 - Historial personaje\n";
    echo "10 - Listar personajes\n";
    echo "11 - Listar personajes disponibles\n";
    echo "12 - Listar personajes lesionados\n";
    echo "13 - Listar personajes retirados\n";
    echo "14 - Listar armas disponibles\n";
    echo "15 - Listar arenas\n";
    echo "16 - Listar duelos realizados\n";
    echo "17 - Listar duelos pendientes\n";
    echo "18 - Personaje con más victorias\n";
    echo "19 - Arena con más duelos\n";
    echo "0 - Salir\n";
}

do {
    echo "\nIngrese una opción: ";
    $opcion = trim(fgets(STDIN));

    switch ($opcion) {
        case 1:
            echo "Nombre del personaje: ";
            $nombre = trim(fgets(STDIN));

            echo "Clase de personaje (Guerrero, Mago, Arquero): ";
            $clase = trim(fgets(STDIN));

            if (strtolower($clase) == "guerrero") {
                $personaje = new Guerrero(0,$nombre,1,100,100,0,0,"disponible",10,10);
            } elseif (strtolower($clase) == "mago") {
                $personaje = new Mago(0,$nombre,1,100,100,0,0,"disponible",15,15);
            } elseif (strtolower($clase) == "arquero") {
                $personaje = new Arquero(0,$nombre,1,100,100,0,0,"disponible",20,20);
            }
            $personajeDAO->alta($personaje);
            if($personajeDAO->alta($personaje)){
                echo "Personaje registrado correctamente.\n";
            } else {
                echo "Error al registrar el personaje.\n";
            }
            break;
        case 2:
            echo "Nombre del arma: ";
            $nombre = trim(fgets(STDIN));

            echo "Tipo de arma (Cuerda, Fuego, Hielo): ";
            $tipo = trim(fgets(STDIN));

            echo "Daño de arma: ";
            $danio = trim(fgets(STDIN));

            echo "Nivel mínimo requerido para usar el arma: ";
            $nivelMinimo = trim(fgets(STDIN));

            $arma = new Arma(0,$nombre,$tipo,$danio,$nivelMinimo,"disponible");
            $armaDAO->alta($arma);
            break;
        case 3:
            echo "Nombre del arena: ";
            $nombre = trim(fgets(STDIN));

            echo "Dificultad de la arena (1-5): ";
            $dificultad = trim(fgets(STDIN));

            echo "Capacidad de la arena: ";
            $capacidad = trim(fgets(STDIN));

            echo "Clima de la arena (Soleado, Lluvia, Tormenta, Niebla): ";
            $clima = trim(fgets(STDIN));

            $arena = new Arena(0,$nombre,$dificultad,$capacidad,$clima);
            $arenaDAO->alta($arena);
            break;
        case 4:
            echo "ID del personaje: ";
            $idPersonaje = trim(fgets(STDIN));

            echo "ID del arma: ";
            $idArma = trim(fgets(STDIN));

            $personaje = $personajeDAO->buscarPorId($idPersonaje);
            $arma = $armaDAO->buscarPorId($idArma);

            if ($personaje && $arma) {
                if ($torneo->equiparArma($personaje, $arma)) {
                    $personajeDAO->actualizar($personaje);
                    $armaDAO->actualizar($arma);
                    echo "Arma equipada correctamente.\n";
                } else {
                    echo "No se pudo equipar el arma. Verifique los requisitos.\n";
                }
            }
            break;
        case 5:
            echo "ID del personaje 1: ";
            $idPersonaje1 = trim(fgets(STDIN));

            echo "ID del personaje 2: ";
            $idPersonaje2 = trim(fgets(STDIN));

            echo "ID de la arena: ";
            $idArena = trim(fgets(STDIN));

            $personaje1 = $personajeDAO->buscarPorId($idPersonaje1);
            $personaje2 = $personajeDAO->buscarPorId($idPersonaje2);
            $arena = $arenaDAO->buscarPorId($idArena);

            if ($personaje1 && $personaje2 && $arena) {
                $duelo = new Duelo(0, $personaje1, $personaje2, $arena, "22/06/26", "pendiente");
                $dueloDAO->alta($duelo);
            } else {
                echo "Uno o más elementos no encontrados.\n";
            }
            break;
        case 6:
            $duelosPendientes = $dueloDAO->listarPendientes();
            foreach ($duelosPendientes as $dueloData) {
                $personaje1 = $personajeDAO->buscarPorId($dueloData['personaje1_id']);
                $personaje2 = $personajeDAO->buscarPorId($dueloData['personaje2_id']);
                $arena = $arenaDAO->buscarPorId($dueloData['arena_id']);

                if ($personaje1 && $personaje2 && $arena) {
                    $duelo = new Duelo($dueloData['id'], $personaje1, $personaje2, $arena, $dueloData['fecha'], "pendiente");
                    $duelo->realizarDuelo();
                    $dueloDAO->actualizar($duelo);
                    $personajeDAO->actualizar($personaje1);
                    $personajeDAO->actualizar($personaje2);
                }
            }
            break;
        case 7:
            echo "ID del personaje a recuperar: ";
            $idPersonajeRecuperar = trim(fgets(STDIN));

            $personajeRecuperar = $personajeDAO->buscarPorId($idPersonajeRecuperar);
            if ($personajeRecuperar) {
                $personajeRecuperar->setEstado("disponible");
                $personajeDAO->actualizar($personajeRecuperar);
                echo "Personaje recuperado correctamente.\n";
            } else {
                echo "Personaje no encontrado.\n";
            }
            break;
        case 8:
            $ranking = $dueloDAO->rankingVictorias();
            foreach ($ranking as $posicion => $datos) {
                $personaje = $personajeDAO->buscarPorId($datos['personaje_id']);
                echo ($posicion + 1) . ". " . $personaje->getNombre() . " - " . $datos['total_victorias'] . " victorias\n";
            }
            break;
        case 9:
            echo "ID del personaje para historial: ";
            $idPersonajeHistorial = trim(fgets(STDIN));

            $historial = $dueloDAO->listarDuelosPorPersonaje($idPersonajeHistorial);
            foreach ($historial as $dueloData) {
                $personaje1 = $personajeDAO->buscarPorId($dueloData['personaje1_id']);
                $personaje2 = $personajeDAO->buscarPorId($dueloData['personaje2_id']);
                $arena = $arenaDAO->buscarPorId($dueloData['arena_id']);

                echo "Duelo ID: " . $dueloData['id'] . " - " . $personaje1->getNombre() . " vs " . $personaje2->getNombre() . " en " . $arena->getNombre() . " el " . $dueloData['fecha'] . "\n";
            }
            break;
        case 10:
            $personajes = $personajeDAO->listar("disponible");
            foreach ($personajes as $personaje) {
                echo "ID: " . $personaje->getId() . " - Nombre: " . $personaje->getNombre() . " - Clase: " . $personaje->getClase() . "\n";
            }
            break;
        case 11:
            $personajesDisponibles = $personajeDAO->listar("disponible");
            foreach ($personajesDisponibles as $personaje) {
                echo "ID: " . $personaje->getId() . " - Nombre: " . $personaje->getNombre() . " - Clase: " . $personaje->getClase() . "\n";
            }
            break;
        case 12:
            $personajesLesionados = $personajeDAO->listar("lesionado");
            foreach ($personajesLesionados as $personaje) {
                echo "ID: " . $personaje->getId() . " - Nombre: " . $personaje->getNombre() . " - Clase: " . $personaje->getClase() . "\n";
            }
            break;

        case 13:
            $personajesRetirados = $personajeDAO->listar("retirado");
            foreach ($personajesRetirados as $personaje) {
                echo "ID: " . $personaje->getId() . " - Nombre: " . $personaje->getNombre() . " - Clase: " . $personaje->getClase() . "\n";
            }
            break;
        case 14:
            $armasDisponibles = $armaDAO->listar("disponible");
            foreach ($armasDisponibles as $arma) {
                echo "ID: " . $arma->getId() . " - Nombre: " . $arma->getNombre() . " - Tipo: " . $arma->getTipo() . "\n";
            }
            break;
        case 15:
            $arenas = $arenaDAO->listar();
            foreach ($arenas as $arena) {
                echo "ID: " . $arena->getId() . " - Nombre: " . $arena->getNombre() . " - Dificultad: " . $arena->getDificultad() . "\n";
            }
            break;
        case 16:
            $duelosRealizados = $dueloDAO->listar();
            foreach ($duelosRealizados as $dueloData) {
                $personaje1 = $personajeDAO->buscarPorId($dueloData['personaje1_id']);
                $personaje2 = $personajeDAO->buscarPorId($dueloData['personaje2_id']);
                $arena = $arenaDAO->buscarPorId($dueloData['arena_id']);

                echo "Duelo ID: " . $dueloData['id'] . " - " . $personaje1->getNombre() . " vs " . $personaje2->getNombre() . " en " . $arena->getNombre() . " el " . $dueloData['fecha'] . "\n";
            }
            break;
        case 17:
            $duelosPendientes = $dueloDAO->listarPendientes();
            foreach ($duelosPendientes as $dueloData) {
                $personaje1 = $personajeDAO->buscarPorId($dueloData['personaje1_id']);
                $personaje2 = $personajeDAO->buscarPorId($dueloData['personaje2_id']);
                $arena = $arenaDAO->buscarPorId($dueloData['arena_id']);

                echo "Duelo ID: " . $dueloData['id'] . " - " . $personaje1->getNombre() . " vs " . $personaje2->getNombre() . " en " . $arena->getNombre() . " el " . $dueloData['fecha'] . "\n";
            }
            break;
        case 18:
            $personajeMasVictorias = $dueloDAO->personajeMasVictorias();
            if ($personajeMasVictorias) {
                $personaje = $personajeDAO->buscarPorId($personajeMasVictorias['personaje_id']);
                echo "Personaje con más victorias: " . $personaje->getNombre() . " - " . $personajeMasVictorias['victorias'] . " victorias\n";
            } else {
                echo "No hay duelos registrados.\n";
            }
            break;
        case 19:
            $arenaMasDuelos = $dueloDAO->arenaMasDuelos();
            if ($arenaMasDuelos) {
                $arena = $arenaDAO->buscarPorId($arenaMasDuelos['arena_id']);
                echo "Arena con más duelos: " . $arena->getNombre() . " - " . $arenaMasDuelos['total_duelos'] . " duelos\n";
            } else {
                echo "No hay duelos registrados.\n";
            }
            break;
        default:
            if ($opcion != 0) {
                echo "Opción no válida. Intente nuevamente.\n";
            }
            break;
    }
} while ($opcion != 0);
?>