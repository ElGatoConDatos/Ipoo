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

    echo "===== TORNEO DE DUELOS =====\n";

    echo "\n--- PERSONAJES ---\n";
    echo "1. Crear personaje\n";
    echo "2. Modificar personaje\n";
    echo "3. Eliminar personaje\n";
    echo "4. Listar personajes\n";
    echo "5. Listar personajes disponibles\n";
    echo "6. Listar personajes lesionados\n";
    echo "7. Listar personajes retirados\n";
    echo "8. Recuperar personaje lesionado\n";

    echo "\n--- ARMAS ---\n";
    echo "9. Crear arma\n";
    echo "10. Modificar arma\n";
    echo "11. Eliminar arma\n";
    echo "12. Listar armas\n";
    echo "13. Mostrar armas equipadas\n";
    echo "14. Equipar arma\n";

    echo "\n--- ARENAS ---\n";
    echo "15. Crear arena\n";
    echo "16. Modificar arena\n";
    echo "17. Eliminar arena\n";
    echo "18. Listar arenas\n";

    echo "\n--- DUELOS ---\n";
    echo "19. Crear duelo\n";
    echo "20. Ejecutar duelos pendientes\n";
    echo "21. Listar duelos realizados\n";
    echo "22. Listar duelos pendientes\n";
    echo "23. Historial de personaje\n";

    echo "\n--- ESTADISTICAS ---\n";
    echo "24. Ranking de victorias\n";
    echo "25. Personaje con más victorias\n";
    echo "26. Porcentaje de victorias\n";
    echo "27. Arena con más duelos\n";

    echo "\n0. Salir\n";
    $opcion = (int) trim(fgets(STDIN));

    switch ($opcion) {

        case 1:

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

            case 2:

            echo "ID Personaje: ";
            $id = (int) trim(fgets(STDIN));

            $personaje = $torneo->buscarPersonaje($id);

            if (!$personaje) {

                echo "Personaje inexistente.\n";
                break;
            }

            echo "Nuevo nombre: ";
            $personaje->setNombre(trim(fgets(STDIN)));

            echo "Nuevo nivel: ";
            $personaje->setNivel((int) trim(fgets(STDIN)));

            if ($personaje instanceof Guerrero) {

                echo "Fuerza: ";
                $personaje->setFuerza((float) trim(fgets(STDIN)));

                echo "Armadura: ";
                $personaje->setArmadura((float) trim(fgets(STDIN)));
            } elseif ($personaje instanceof Mago) {

                echo "Mana: ";
                $personaje->setMana((float) trim(fgets(STDIN)));

                echo "Inteligencia: ";
                $personaje->setInteligencia((float) trim(fgets(STDIN)));
            } elseif ($personaje instanceof Arquero) {

                echo "Precision: ";
                $personaje->setPrecision((float) trim(fgets(STDIN)));

                echo "Velocidad: ";
                $personaje->setVelocidad((float) trim(fgets(STDIN)));
            }

            if ($torneo->actualizarPersonaje($personaje)) {
                echo "Personaje actualizado.\n";
            } else {
                echo "Error al actualizar.\n";
            }

            break;

            case 3:

            echo "ID Personaje: ";
            $id = (int) trim(fgets(STDIN));

            if ($torneo->eliminarPersonaje($id)) {
                echo "Personaje eliminado.\n";
            } else {
                echo "No se pudo eliminar.\n";
            }

            break;

            case 4:

            $personajes = $torneo->listarPersonajes();

            if (empty($personajes)) {
                echo "No hay personajes registrados.\n";
            } else {
                foreach ($personajes as $p) {
                    echo $p->datosPersonaje() . "\n";
                }
            }

            break;

            case 5:

            $personajes = $torneo->listarPersonajes('disponible');

            if (empty($personajes)) {
                echo "No hay personajes disponibles.\n";
            } else {
                foreach ($personajes as $p) {
                    echo $p->datosPersonaje() . "\n";
                }
            }

            break;
                case 6:

            $personajes = $torneo->listarPersonajes('lesionado');

            if (empty($personajes)) {
                echo "No hay personajes lesionados.\n";
            } else {
                foreach ($personajes as $p) {
                    echo $p->datosPersonaje() . "\n";
                }
            }

            break;

            case 7:

            $personajes = $torneo->listarPersonajes('retirado');

            if (empty($personajes)) {
                echo "No hay personajes retirados.\n";
            } else {
                foreach ($personajes as $p) {
                    echo $p->datosPersonaje() . "\n";
                }
            }

            break;

                case 8:

            echo "ID Personaje: ";
            $id = (int) trim(fgets(STDIN));

            if ($torneo->recuperarPersonajeLesionado($id)) {
                echo "Personaje recuperado.\n";
            } else {
                echo "No se pudo recuperar.\n";
            }

            break;

            case 9:

            echo "Nombre: ";
            $nombre = trim(fgets(STDIN));

            echo "Tipo: ";
            $tipo = trim(fgets(STDIN));

            echo "Daño Base: ";
            $danioBase = (float) trim(fgets(STDIN));

            echo "Nivel Minimo: ";
            $nivelMinimo = (int) trim(fgets(STDIN));

            $arma = new Arma(
                0,
                $nombre,
                $tipo,
                $danioBase,
                $nivelMinimo,
                'disponible'
            );

            if ($torneo->agregarArma($arma)) {
                echo "Arma creada correctamente.\n";
            } else {
                echo "Error al crear arma.\n";
            }

            break;

            case 10:

            echo "ID Arma: ";
            $id = (int) trim(fgets(STDIN));

            $arma = $torneo->buscarArma($id);

            if (!$arma) {

                echo "Arma inexistente.\n";

            } else {

                echo "Nombre: ";
                $arma->setNombre(trim(fgets(STDIN)));

                echo "Tipo: ";
                $arma->setTipo(trim(fgets(STDIN)));

                echo "Daño Base: ";
                $arma->setDanioBase((float) trim(fgets(STDIN)));

                echo "Nivel Mínimo: ";
                $arma->setNivelMinimo((int) trim(fgets(STDIN)));

                echo "Estado (disponible/equipada/rota): ";
                $arma->setEstado(trim(fgets(STDIN)));

                if ($torneo->actualizarArma($arma)) {
                    echo "Arma modificada.\n";
                } else {
                    echo "Error al modificar.\n";
                }
            }

            break;

            case 11:

            echo "ID Arma: ";
            $id = (int) trim(fgets(STDIN));

            if ($torneo->eliminarArma($id)) {
                echo "Arma eliminada.\n";
            } else {
                echo "No se pudo eliminar.\n";
            }

            break;

                case 12:

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

            case 13:

            $equipados = $torneo->armasEquipadas();

            if (empty($equipados)) {

                echo "No hay armas equipadas.\n";

            } else {

                foreach ($equipados as $fila) {

                    echo $fila['personaje']->getNombre();
                    echo " -> ";
                    echo $fila['arma']->getNombre();
                    echo "\n";
                }
            }

            break;

            case 14:

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

            case 15:

            echo "Nombre: ";
            $nombre = trim(fgets(STDIN));

            echo "Dificultad: ";
            $dificultad = (int) trim(fgets(STDIN));

            echo "Capacidad Publico: ";
            $capacidad = (int) trim(fgets(STDIN));

            echo "Clima (normal/lluvia/tormenta/niebla): ";
            $clima = strtolower(trim(fgets(STDIN)));

            $arena = new Arena(
                0,
                $nombre,
                $dificultad,
                $capacidad,
                $clima
            );

            if ($torneo->agregarArena($arena)) {
                echo "Arena creada correctamente.\n";
            } else {
                echo "Error al crear arena.\n";
            }

            break;

            case 16:

            echo "ID Arena: ";
            $id = (int) trim(fgets(STDIN));

            $arena = $torneo->buscarArena($id);

            if (!$arena) {

                echo "Arena inexistente.\n";

            } else {

                echo "Nombre: ";
                $arena->setNombre(trim(fgets(STDIN)));

                echo "Dificultad: ";
                $arena->setDificultad((int) trim(fgets(STDIN)));

                echo "Capacidad Público: ";
                $arena->setCapacidadPublico((int) trim(fgets(STDIN)));

                echo "Clima: ";
                $arena->setClima(trim(fgets(STDIN)));

                if ($torneo->actualizarArena($arena)) {
                    echo "Arena modificada.\n";
                } else {
                    echo "Error al modificar.\n";
                }
            }

            break;

            case 17:

            echo "ID Arena: ";
            $id = (int) trim(fgets(STDIN));

            if ($torneo->eliminarArena($id)) {
                echo "Arena eliminada.\n";
            } else {
                echo "No se pudo eliminar.\n";
            }

            break;

            case 18:

            $arenas = $torneo->listarArenas();

            if (empty($arenas)) {
                echo "No hay arenas registradas.\n";
            } else {
                foreach ($arenas as $arena) {
                    echo $arena->datosArena() . "\n";
                }
            }

            break;

            case 19:
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

            case 20:

            $torneo->ejecutarDuelosPendientes();

            echo "Duelos ejecutados.\n";

            break;

            case 21:

            $duelos = $torneo->listarDuelosRealizados();

            if (empty($duelos)) {

                echo "No hay duelos realizados.\n";

            } else {

                foreach ($duelos as $duelo) {

                    $p1 = $torneo->buscarPersonaje(
                        $duelo['idPersonaje1']
                    );

                    $p2 = $torneo->buscarPersonaje(
                        $duelo['idPersonaje2']
                    );

                    echo "Duelo #{$duelo['id']} | ";
                    echo $p1->getNombre();
                    echo " VS ";
                    echo $p2->getNombre();
                    echo " | ";
                    echo $duelo['fecha'];
                    echo "\n";
                }
            }

            break;

            case 22:

            $duelos = $torneo->listarDuelosPendientes();

            if (empty($duelos)) {

                echo "No hay duelos pendientes.\n";

            } else {

                foreach ($duelos as $duelo) {

                    $p1 = $torneo->buscarPersonaje(
                        $duelo['idPersonaje1']
                    );

                    $p2 = $torneo->buscarPersonaje(
                        $duelo['idPersonaje2']
                    );

                    echo "Duelo #{$duelo['id']} | ";
                    echo $p1->getNombre();
                    echo " VS ";
                    echo $p2->getNombre();
                    echo " | ";
                    echo $duelo['fecha'];
                    echo "\n";
                }
            }

            break;

            case 23:

            echo "ID Personaje: ";
            $id = (int) trim(fgets(STDIN));

            $historial = $torneo->historialPersonaje($id);

            if (empty($historial)) {

                echo "No hay duelos registrados.\n";

            } else {

                foreach ($historial as $duelo) {

                    $p1 = $torneo->buscarPersonaje(
                        $duelo['idPersonaje1']
                    );

                    $p2 = $torneo->buscarPersonaje(
                        $duelo['idPersonaje2']
                    );

                    echo "Duelo #{$duelo['id']} | ";
                    echo $p1->getNombre();
                    echo " VS ";
                    echo $p2->getNombre();
                    echo " | Estado: ";
                    echo $duelo['estado'];
                    echo " | Fecha: ";
                    echo $duelo['fecha'];
                    echo "\n";
                }
            }

            break;

            case 24:

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

            case 25:

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

            case 26:

            echo "ID personaje: ";
            $id = (int) trim(fgets(STDIN));

            echo "Porcentaje: ";
            echo $torneo->porcentajeVictorias($id);
            echo "%\n";

            break;

            case 27:

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

        case 0:

            echo "Fin del programa.\n";

            break;

        default:

            echo "Opción inválida.\n";
    }

} while ($opcion !== 0);