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

// Una función para limpiar la consola, si la leen, pueden ver que funciona distinto dependiendo de su sistema operativo, escojan la que deban
function limpiarPantalla()
{
    // ** Dependindo la versión del sistema operativo y de PHP, utilizar la función que mejor funcione **

    //shell_exec("cls");
    //shell_exec("clear");
    //system("cls");
    //pclose(popen('cls','w'));
    //for ($i = 0; $i < 50; $i++) echo "\r\n";
    //echo chr(27).chr(91).'H'.chr(27).chr(91).'J'; // funciona bien
    //popen('clear', 'w'); // linux
    popen('cls', 'w'); // funciona bien en windows
}

// Funcion principalmente para el menú, pero puede ser reutilizado sin problema, lo que hace es dar un margen de números para evitar números fuera del rango deseado
function opcionValida(int $min, int $max)
{
    do {
        $numero = (int)trim(fgets(STDIN));
        if (!($numero >= $min  && $numero <= $max)) {
            echo "⛔ Opción inválida. Por favor ingrese un número del " . $min . " al " . $max . ".\n";
        } else
            return $numero;
    } while (!($numero >= $min  && $numero <= $max));
}

// Funcion para que solo puedan escojer positivos Float 
function soloPositivo()
{
    do {
        $numero = (float)trim(fgets(STDIN));
        if (!($numero > 0)) {
            echo "⛔ Número inválido. Por favor que el número sea Mayor a 0.\n";
        } else
            return $numero;
    } while (!($numero > 0));
}

do {
    limpiarPantalla();
    echo "===== TORNEO DE DUELOS =====\n";

    echo "\n--- PERSONAJES ---\n";
    echo "1. Crear personaje\n";
    echo "2. Modificar personaje\n";
    echo "3. Listar personajes\n";
    echo "4. Listar personajes disponibles\n";
    echo "5. Listar personajes lesionados\n";
    echo "6. Listar personajes retirados\n";
    echo "7. Recuperar personaje lesionado\n";

    echo "\n--- ARMAS ---\n";
    echo "8. Crear arma\n";
    echo "9. Modificar arma\n";
    echo "10. Eliminar arma\n";
    echo "11. Listar armas\n";
    echo "12. Mostrar armas equipadas\n";
    echo "13. Equipar arma\n";

    echo "\n--- ARENAS ---\n";
    echo "14. Crear arena\n";
    echo "15. Modificar arena\n";
    echo "16. Eliminar arena\n";
    echo "17. Listar arenas\n";

    echo "\n--- DUELOS ---\n";
    echo "18. Crear duelo\n";
    echo "19. Ejecutar duelos pendientes\n";
    echo "20. Listar duelos realizados\n";
    echo "21. Listar duelos pendientes\n";
    echo "22. Historial de personaje\n";

    echo "\n--- ESTADISTICAS ---\n";
    echo "23. Ranking de victorias\n";
    echo "24. Personaje con más victorias\n";
    echo "25. Porcentaje de victorias\n";
    echo "26. Arena con más duelos\n";

    echo "\n0. Salir\n";
    echo "============================\n\n";
    echo "Opción: ";
    $opcion = opcionValida(0, 26);
    limpiarPantalla();
    switch ($opcion) {

        case 1:
            echo "===== CREADOR DE PERSONAJES =====\n\n";
            echo "Nombre: ";
            $nombre = trim(fgets(STDIN));

            echo "Nivel: ";
            $nivel = opcionValida(1, 100);

            echo "Clase (guerrero/mago/arquero): ";
            $clase = strtolower(trim(fgets(STDIN)));

            $personaje = null;

            switch ($clase) {

                case 'guerrero':

                    echo "Fuerza: ";
                    $fuerza = soloPositivo();

                    echo "Armadura: ";
                    $armadura = soloPositivo();

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
                    $mana = soloPositivo();

                    echo "Inteligencia: ";
                    $inteligencia = soloPositivo();

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
                    $precision = soloPositivo();

                    echo "Velocidad: ";
                    $velocidad = soloPositivo();

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
                    echo "----------------------------\n\n";
                    echo "Clase inválida.\n\n";
                    echo "Presione ENTER para continuar...";
                    trim(fgets(STDIN));
                    break;
            }
            if ($personaje !== null) {
                echo "----------------------------\n\n";
                if ($torneo->agregarPersonaje($personaje)) {
                    echo "Personaje creado correctamente.\n\n";
                } else {
                    echo "Error al crear personaje.\n\n";
                }
            }
            echo "Presione ENTER para continuar...";
            trim(fgets(STDIN));
            break;

        case 2:
            echo "===== MODIFICADOR DE PERSONAJES =====\n\n";
            $personajes = $torneo->listarPersonajes();

            if (empty($personajes)) {
                echo "----------------------------\n\n";
                echo "No hay personajes registrados.\n";
                echo "Presione ENTER para continuar...";
                trim(fgets(STDIN));
                break;
            } else {
                foreach ($personajes as $p) {
                    echo $p->datosPersonaje();
                    echo "----------------------------\n\n";
                }

                echo "ID Personaje: ";
                $id = (int) trim(fgets(STDIN));
                limpiarPantalla();
                echo "===== MODIFICADOR DE PERSONAJES =====\n";
                $personaje = $torneo->buscarPersonaje($id);

                if (!$personaje) {

                    echo "Personaje inexistente.\n\n";
                    echo "Presione ENTER para continuar...";
                    trim(fgets(STDIN));
                    break;
                }

                echo "Nuevo nombre: ";
                $personaje->setNombre(trim(fgets(STDIN)));

                echo "Nuevo nivel: ";
                $personaje->setNivel(opcionValida(1, 100));

                if ($personaje instanceof Guerrero) {

                    echo "Fuerza: ";
                    $personaje->setFuerza(soloPositivo());

                    echo "Armadura: ";
                    $personaje->setArmadura(soloPositivo());
                } elseif ($personaje instanceof Mago) {

                    echo "Mana: ";
                    $personaje->setMana(soloPositivo());

                    echo "Inteligencia: ";
                    $personaje->setInteligencia(soloPositivo());
                } elseif ($personaje instanceof Arquero) {

                    echo "Precision: ";
                    $personaje->setPrecision(soloPositivo());

                    echo "Velocidad: ";
                    $personaje->setVelocidad(soloPositivo());
                }
                echo "----------------------------\n\n";
                if ($torneo->actualizarPersonaje($personaje)) {
                    echo "Personaje actualizado.\n\n";
                } else {
                    echo "Error al actualizar.\n\n";
                }
                echo "Presione ENTER para continuar...";
                trim(fgets(STDIN));
                break;
            }

        case 3:
            echo "===== LISTA DE PERSONAJES =====\n\n";
            $personajes = $torneo->listarPersonajes();

            if (empty($personajes)) {
                echo "----------------------------\n\n";
                echo "No hay personajes registrados.\n";
            } else {
                foreach ($personajes as $p) {
                    echo $p->datosPersonaje();
                    echo "----------------------------\n\n";
                }
            }
            echo "Presione ENTER para continuar...";
            trim(fgets(STDIN));
            break;

        case 4:
            echo "===== PERSONAJES DISPONIBLES =====\n\n";
            $personajes = $torneo->listarPersonajes('disponible');

            if (empty($personajes)) {
                echo "----------------------------\n\n";
                echo "No hay personajes disponibles.\n";
            } else {
                foreach ($personajes as $p) {
                    echo $p->datosPersonaje();
                    echo "----------------------------\n\n";
                }
            }
            echo "Presione ENTER para continuar...";
            trim(fgets(STDIN));
            break;
        case 5:
            echo "===== PERSONAJES LESIONADOS =====\n\n";
            $personajes = $torneo->listarPersonajes('lesionado');

            if (empty($personajes)) {
                echo "----------------------------\n\n";
                echo "No hay personajes lesionados.\n";
            } else {
                foreach ($personajes as $p) {
                    echo $p->datosPersonaje();
                    echo "----------------------------\n\n";
                }
            }
            echo "Presione ENTER para continuar...";
            trim(fgets(STDIN));
            break;

        case 6:
            echo "===== PERSONAJES RETIRADOS =====\n\n";
            $personajes = $torneo->listarPersonajes('retirado');

            if (empty($personajes)) {
                echo "----------------------------\n\n";
                echo "No hay personajes retirados.\n\n";
            } else {
                foreach ($personajes as $p) {
                    echo $p->datosPersonaje();
                    echo "----------------------------\n\n";
                }
            }
            echo "Presione ENTER para continuar...";
            trim(fgets(STDIN));
            break;

        case 7:
            echo "===== RECUPERAR UN PERSONAJE =====\n\n";
            $personajes = $torneo->listarPersonajes('lesionado');

            if (empty($personajes)) {
                echo "----------------------------\n\n";
                echo "No hay personajes registrados.\n";
                echo "Presione ENTER para continuar...";
                trim(fgets(STDIN));
                break;
            } else {
                foreach ($personajes as $p) {
                    echo $p->datosPersonaje();
                    echo "----------------------------\n\n";
                }

                echo "ID Personaje: ";
                $id = (int) trim(fgets(STDIN));
                limpiarPantalla();
                echo "----------------------------\n\n";
                if ($torneo->recuperarPersonajeLesionado($id)) {
                    echo "Personaje recuperado correctamente.\n";
                } else {
                    echo "No se pudo recuperar.\n\n";
                }
                echo "Presione ENTER para continuar...";
                trim(fgets(STDIN));
                break;
            }
        case 8:
            echo "===== CREADOR DE ARENAS =====\n\n";
            echo "Nombre: ";
            $nombre = trim(fgets(STDIN));

            echo "Tipo: ";
            $tipo = trim(fgets(STDIN));

            echo "Daño Base: ";
            $danioBase = soloPositivo();

            echo "Nivel Minimo: ";
            $nivelMinimo = opcionValida(1, 100);

            $arma = new Arma(
                0,
                $nombre,
                $tipo,
                $danioBase,
                $nivelMinimo,
                'disponible'
            );
            echo "----------------------------\n\n";
            if ($torneo->agregarArma($arma)) {
                echo "Arma creada correctamente.\n\n";
            } else {
                echo "Error al crear arma.\n\n";
            }
            echo "Presione ENTER para continuar...";
            trim(fgets(STDIN));
            break;

        case 9:
            echo "===== MODIFICADOR DE ARMAS =====\n\n";
            $armas = $torneo->listarArmas();
            if (empty($armas)) {
                echo "----------------------------\n\n";
                echo "No existen armas aún.\n\n";
            } else {
                foreach ($armas as $arma) {
                    echo $arma->datosArma();
                    echo "----------------------------\n\n";
                }
            }
            echo "ID Arma: ";
            $id = (int) trim(fgets(STDIN));

            $arma = $torneo->buscarArma($id);

            if (!$arma) {
                echo "----------------------------\n\n";
                echo "Arma inexistente.\n\n";
            } else {
                limpiarPantalla();
                echo "===== MODIFICADOR DE ARMAS =====\n\n";
                echo "Nombre: ";
                $arma->setNombre(trim(fgets(STDIN)));

                echo "Tipo: ";
                $arma->setTipo(trim(fgets(STDIN)));

                echo "Daño Base: ";
                $arma->setDanioBase(soloPositivo());

                echo "Nivel Mínimo: ";
                $arma->setNivelMinimo(opcionValida(1, 100));

                echo "Estado (disponible/equipada/rota): ";
                $arma->setEstado(trim(fgets(STDIN)));
                echo "----------------------------\n\n";
                if ($torneo->actualizarArma($arma)) {
                    echo "Arma modificada.\n\n";
                } else {
                    echo "Error al modificar.\n\n";
                }
            }
            echo "Presione ENTER para continuar...";
            trim(fgets(STDIN));
            break;

        case 10:
            echo "===== ELIMINADOR DE ARMAS =====\n\n";
            $armas = $torneo->listarArmas();
            if (empty($armas)) {
                echo "----------------------------\n\n";
                echo "No existen armas aún.\n\n";
            } else {
                foreach ($armas as $arma) {
                    echo $arma->datosArma();
                    echo "----------------------------\n\n";
                }
                echo "ID Arma: ";
                $id = (int) trim(fgets(STDIN));
                echo "----------------------------\n\n";
                if ($torneo->eliminarArma($id)) {
                    echo "Arma eliminada.\n\n";
                } else {
                    echo "No se pudo eliminar.\n\n";
                }
            }
            echo "Presione ENTER para continuar...";
            trim(fgets(STDIN));
            break;

        case 11:
            echo "===== LISTA DE ARMAS =====\n\n";
            echo "Estado (disponible/equipada/rota o ENTER): ";
            $estado = strtolower(trim(fgets(STDIN)));

            $armas = $torneo->listarArmas($estado);

            if (empty($armas)) {
                echo "\n No existen armas con estado '$estado'.\n";
            } else {
                foreach ($armas as $arma) {
                    echo $arma->datosArma();
                    echo "----------------------------\n\n";
                }
            }

            break;

        case 12:

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

        case 13:

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

        case 14:

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

        case 15:

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

        case 16:

            echo "ID Arena: ";
            $id = (int) trim(fgets(STDIN));

            if ($torneo->eliminarArena($id)) {
                echo "Arena eliminada.\n";
            } else {
                echo "No se pudo eliminar.\n";
            }

            break;

        case 17:

            $arenas = $torneo->listarArenas();

            if (empty($arenas)) {
                echo "No hay arenas registradas.\n";
            } else {
                foreach ($arenas as $arena) {
                    echo $arena->datosArena() . "\n";
                }
            }

            break;

        case 18:
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

        case 19:

            $torneo->ejecutarDuelosPendientes();

            echo "Duelos ejecutados.\n";

            break;

        case 20:

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

        case 21:

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

        case 22:

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

        case 23:

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

        case 24:

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

        case 25:

            echo "ID personaje: ";
            $id = (int) trim(fgets(STDIN));

            echo "Porcentaje: ";
            echo $torneo->porcentajeVictorias($id);
            echo "%\n";

            break;

        case 26:

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
