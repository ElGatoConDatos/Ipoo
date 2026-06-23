<?php

require_once 'PDO/BaseDatos.php';

require_once 'PDO/ArmaDAO.php';
require_once 'PDO/ArenaDAO.php';
require_once 'PDO/PersonajeDAO.php';
require_once 'PDO/DueloDAO.php';

require_once 'Duelo.php';
require_once 'Torneo.php';

$db = new BaseDatos();
$conexion = $db->getConexion();

$armaDAO = new ArmaDAO($conexion);
$arenaDAO = new ArenaDAO($conexion);
$personajeDAO = new PersonajeDAO($conexion, $armaDAO);
$dueloDAO = new DueloDAO($conexion);

$torneo = new Torneo(
    $personajeDAO,
    $armaDAO,
    $arenaDAO,
    $dueloDAO
);

do {

    echo "\n";
    echo "===== TORNEO DE DUELOS =====\n";
    echo "1. Listar personajes\n";
    echo "2. Listar armas\n";
    echo "3. Listar arenas\n";
    echo "4. Equipar arma\n";
    echo "5. Ejecutar duelos pendientes\n";
    echo "6. Ranking victorias\n";
    echo "7. Personaje con más victorias\n";
    echo "8. Arena con más duelos\n";
    echo "9. Porcentaje de victorias\n";
    echo "10. Crear Personaje\n";
    echo "11. Crear Duelo\n";
    echo "0. Salir\n";

    echo "Opción: ";
    $opcion = (int) trim(fgets(STDIN));

    switch ($opcion) {

        case 1:

            $personajes = $torneo->listarPersonajes();

            if (empty($personajes)) {
                echo "No hay personajes registrados.\n";
            } else {
                foreach ($personajes as $p) {
                    echo $p->datosPersonaje() . "\n";
                }
            }

            break;

        case 2:

            echo "Estado (disponible/equipada/rota o ENTER): ";
            $estado = strtolower(trim(fgets(STDIN)));

            $armas = $torneo->listarArmas($estado);

            if (empty($armas)) {
                echo "\n No existen armas con estado '$estado'.\n";
            } else {
                foreach ($armas as $arma) {
                    echo $arma->datosArma() . "\n";
                }
            }

            break;

        case 3:

            $arenas = $torneo->listarArenas();

            if (empty($arenas)) {
                echo "No hay arenas registradas.\n";
            } else {
                foreach ($arenas as $arena) {
                    echo $arena->datosArena() . "\n";
                }
            }

            break;

        case 4:

            echo "ID Personaje: ";
            $idPersonaje = (int) trim(fgets(STDIN));

            echo "ID Arma: ";
            $idArma = (int) trim(fgets(STDIN));

            if ($torneo->equiparArma($idPersonaje, $idArma)) {
                echo "Arma equipada correctamente.\n";
            } else {
                echo "No se pudo equipar el arma.\n";
            }

            break;

        case 5:

            $torneo->ejecutarDuelosPendientes();

            echo "Duelos ejecutados.\n";

            break;

        case 6:

            $ranking = $torneo->rankingVictorias();

            if (empty($ranking)) {
                echo "No hay victorias registradas.\n";
            } else {

                foreach ($ranking as $fila) {

                    $personaje = $torneo->buscarPersonaje(
                        (int) $fila['personaje_id']
                    );

                    if ($personaje) {
                        echo $personaje->getNombre();
                        echo " - ";
                        echo $fila['total_victorias'];
                        echo " victorias\n";
                    }
                }
            }

            break;

        case 7:

            $resultado = $torneo->personajeMasVictorias();

            if (!$resultado) {

                echo "No hay datos disponibles.\n";

            } else {

                $personaje = $torneo->buscarPersonaje(
                    (int) $resultado['personaje_id']
                );

                if ($personaje) {
                    echo $personaje->getNombre();
                    echo " - ";
                    echo $resultado['victorias'];
                    echo " victorias\n";
                }
            }

            break;

        case 8:

            $resultado = $torneo->arenaMasDuelos();

            if (!$resultado) {

                echo "No hay datos disponibles.\n";

            } else {

                $arena = $torneo->buscarArena(
                    (int) $resultado['idArena']
                );

                if ($arena) {
                    echo $arena->getNombre();
                    echo " - ";
                    echo $resultado['total_duelos'];
                    echo " duelos\n";
                }
            }

            break;

        case 9:

            echo "ID personaje: ";
            $id = (int) trim(fgets(STDIN));

            echo "Porcentaje: ";
            echo $torneo->porcentajeVictorias($id);
            echo "%\n";

            break;
        case 10:

            echo "Nombre: ";
            $nombre = trim(fgets(STDIN));

            echo "Clase (guerrero/mago/arquero): ";
            $clase = strtolower(trim(fgets(STDIN)));

            echo "Nivel: ";
            $nivel = (int) trim(fgets(STDIN));

            $personaje = null;

            switch ($clase) {

                case 'guerrero':

                    echo "Fuerza: ";
                    $fuerza = (float) trim(fgets(STDIN));

                    echo "Armadura: ";
                    $armadura = (float) trim(fgets(STDIN));

                    $personaje = new Guerrero(
                        0,
                        $nombre,
                        $nivel,
                        100,
                        100,
                        0,
                        0,
                        'disponible',
                        $fuerza,
                        $armadura
                    );

                    break;

                case 'mago':

                    echo "Mana: ";
                    $mana = (float) trim(fgets(STDIN));

                    echo "Inteligencia: ";
                    $inteligencia = (float) trim(fgets(STDIN));

                    $personaje = new Mago(
                        0,
                        $nombre,
                        $nivel,
                        100,
                        100,
                        0,
                        0,
                        'disponible',
                        $mana,
                        $inteligencia
                    );

                    break;

                case 'arquero':

                    echo "Precisión: ";
                    $precision = (float) trim(fgets(STDIN));

                    echo "Velocidad: ";
                    $velocidad = (float) trim(fgets(STDIN));

                    $personaje = new Arquero(
                        0,
                        $nombre,
                        $nivel,
                        100,
                        100,
                        0,
                        0,
                        'disponible',
                        $precision,
                        $velocidad
                    );

                    break;

                default:
                    echo "Clase inválida.\n";
                    break;
            }

            if ($personaje !== null) {

                if ($torneo->agregarPersonaje($personaje)) {
                    echo "Personaje creado correctamente.\n";
                } else {
                    echo "Error al crear personaje.\n";
                }
            }

            break;

        case 11:
            echo "\n=== PERSONAJES DISPONIBLES ===\n";

            foreach ($torneo->listarPersonajes("disponible") as $p) {
                echo $p->getId() . " - " . $p->getNombre() . "\n";
            }

            echo "\n=== ARENAS ===\n";

            foreach ($torneo->listarArenas() as $a) {
                echo $a->getId() . " - " . $a->getNombre() . "\n";
            }

            echo "ID Personaje 1: ";
            $idP1 = (int) trim(fgets(STDIN));

            echo "ID Personaje 2: ";
            $idP2 = (int) trim(fgets(STDIN));

            echo "ID Arena: ";
            $idArena = (int) trim(fgets(STDIN));

            $p1 = $torneo->buscarPersonaje($idP1);
            $p2 = $torneo->buscarPersonaje($idP2);
            $arena = $torneo->buscarArena($idArena);

            if (!$p1 || !$p2 || !$arena) {

                echo "Personajes o arena inexistentes.\n";

            } elseif ($idP1 === $idP2) {

                echo "Un personaje no puede enfrentarse a sí mismo.\n";

            } else {

                $duelo = new Duelo(
                    0,
                    $p1,
                    $p2,
                    $arena,
                    date('Y-m-d H:i:s'),
                    'pendiente'
                );

                if ($torneo->registrarDuelo($duelo)) {
                    echo "Duelo registrado correctamente.\n";
                } else {
                    echo "Error al registrar duelo.\n";
                }
            }

            break;

        case 0:

            echo "Fin del programa.\n";

            break;

        default:

            echo "Opción inválida.\n";
    }

} while ($opcion !== 0);