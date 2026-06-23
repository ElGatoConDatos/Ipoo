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
function limpiarPantalla() {
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
function opcionValida(int $min, int $max){
    do{
        $numero = (int)trim(fgets(STDIN));
        if (!($numero >= $min  && $numero <= $max)) {
            echo "⛔ Opción inválida. Por favor ingrese un número del ".$min." al ".$max.".\n";
        }
        else
            return $numero;
    }while(!($numero >= $min  && $numero <= $max));
}

// Funcion para que solo puedan escojer positivos Float 
function soloPositivo(){
    do{
        $numero = (float)trim(fgets(STDIN));
        if (!($numero > 0)) {
            echo "⛔ Número inválido. Por favor que el número sea Mayor a 0.\n";
        }
        else
            return $numero;
    }while(!($numero > 0 ));
}
do {
    limpiarPantalla();
    echo "===== TORNEO DE DUELOS =====\n";
    echo "1. Crear Personaje\n"; // Ya
    echo "2. Crear Arma\n"; // Ya
    echo "3. Crear Arena\n"; // Ya
    echo "4. Equipar arma\n"; // Ya
    echo "5. Crear Duelo\n"; // Ya 
    echo "6. Ejecutar duelos pendientes\n"; // Ya Pero falta Nombres del PJ1 Y PJ2, mirar case:6
    echo "7. Recuperar Personajes Lesionados\n"; // Ya, pero parece que falta que se guarde en DAO (En mi caso)
    echo "8. Ranking victorias\n";  // Parece estar, testear
    //echo "9. Historial de personaje\n"; // No

    // LO QUE FALTA
    // Terminar lo de Ejecutar Duelos Pendientes, falta solo sacar el nombre de los PJ (Ver en la base de datos y poner la salida)
    // Recuperar personajes lesionados
    // Historial de duelos de un personaje
    // Falta Listar Solamente Personajes Lesionados o Retirados, y personajes que están listos para duelar.
    // Mostrar Arma equipada por cada personaje, estilo Arma x x x, la tiene X, solo faltaría colocar quien la tiene.
    // Historial de Duelos Realizados // Lista de Duelos Pendientes
    // De cada personaje // Falta Crear Arma, Crear Arena

    //Extra, hacer una opción 10. que adentro haya estos, son del Ejercicio 8 de Consultas Obligatorias
    // Ejemplo 10. Extra
    // Dentro de eso salga otro mini menu diciendo
    // 1. Listar personajes (En Listar Personajes, que te permita mostrarlos TODOS o por algún estado en particular)
    // 2. Listar armas disponibles
    // 3. Mostrar el arma Equipada por cada personaje (Ejemplo, "(ID: 3) Espada Legendaria, Equipada por: (ID: 1) Thor" )
    // 4. Mostrar los duelos por separado (Realizados / Pendientes)
    // 5. Mostrar Historial de duelos de un personaje (Sean perdidas / Canceladas / Ganadas)
    // 6. Mostrar % de victorias de cada personaje (Dar al usuario la opción de elegir si TODOS o uno en particular)
    // 5. Ranking de arenas donde más duelos se realizaron (Realmente solo piden la que más tiene pero queda re croto)

    echo "0. Salir\n";
    echo "============================\n\n";
    echo "Opción: ";
    $opcion = opcionValida(0, 10);
    limpiarPantalla();
    switch ($opcion) {

        case 1:{ // Crear Personaje
            echo "===== CREADOR DE PERSONAJES =====\n";
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
                    break;
            }

            if ($personaje !== null) {

                if ($torneo->agregarPersonaje($personaje)) {
                    echo "Personaje creado correctamente.\n";
                } else {
                    echo "Error al crear personaje.\n";
                }
                echo "----------------------------\n\n";
            }
            echo "Presione ENTER para continuar...";
            trim(fgets(STDIN));
            break;
        }

        case 2:{ // Crear arma
            echo "===== CREADOR DE ARMAS =====\n";
            echo "Nombre del Arma: ";
            $nombre = trim(fgets(STDIN));

            echo "Tipo del Arma: ";
            $tipo = trim(fgets(STDIN));

            echo "Daño base: ";
            $danio = soloPositivo();

            echo "Nivel mínimo (1-100): ";
            $nivelMinimo = opcionValida(1, 100);

            $arma = new Arma(
                0,
                $nombre,
                $tipo,
                $danio,
                $nivelMinimo,
                "disponible"
            );

            $torneo->agregarArma($arma);
            echo "----------------------------\n\n";
            echo "Arma creada correctamente.\n\n";
            echo "Presione ENTER para continuar...";
            trim(fgets(STDIN));
            break;
        }
        case 3:{ // Crear arena
            echo "===== CREADOR DE ARENAS =====\n";
            echo "Nombre de la Arena: ";
            $nombre = trim(fgets(STDIN));

            echo "Dificultad (1-5): ";
            $dificultad = opcionValida(1,5);

            echo "Capacidad de público: ";
            $capacidad = opcionValida(0, 50000);

            echo "Clima (Soleado / Lluvia / Tormenta / Niebla): ";
            $clima = strtolower(trim(fgets(STDIN)));

            $arena = new Arena(
                0,
                $nombre,
                $dificultad,
                $capacidad,
                $clima
            );

            $torneo->agregarArena($arena);
            echo "----------------------------\n\n";
            echo "Arena creada correctamente.\n\n";
            echo "Presione ENTER para continuar...";
            trim(fgets(STDIN));
            break;
        }
        case 4:{ // Equipar Arma
            $personajes = $torneo->listarPersonajes();
            $armas = $torneo->listarArmas("disponible");

            if (empty($personajes)) {
                echo "No hay Personajes registrados.\n";
            }
            else {
                if (empty($armas)) {
                    echo "No hay Armas registradas.\n";
                }
                else {
                    echo "===== LISTA DE PERSONAJES =====\n\n";
                    foreach ($personajes as $p) {
                        echo $p->datosPersonaje();
                        echo "----------------------------\n\n";
                    }
                    echo "Elija Personaje (ID): ";
                    $idPersonaje = (int) trim(fgets(STDIN));
                    limpiarPantalla();
                    echo "===== LISTA DE ARMAS =====\n\n";
                    foreach ($armas as $a) {
                        echo $a->datosArma();
                        echo "----------------------------\n\n";
                    }

                    echo "Elija Arma a equipar (ID): ";
                    $idArma = (int) trim(fgets(STDIN));

                    if ($torneo->equiparArma($idPersonaje, $idArma)) {
                        echo "Arma equipada correctamente.\n\n";
                    } else {
                        echo "No se pudo equipar el arma.\n\n";
                    }
            }
            echo "Presione ENTER para continuar...";
            trim(fgets(STDIN));
            break;
            }
        }
        case 5:{ // Crear Duelo
            echo "\n=== PERSONAJES DISPONIBLES ===\n";
            foreach ($torneo->listarPersonajes("disponible") as $p) {
                echo "(ID: " . $p->getId() . ") " . $p->getNombre() . "\n";
            }

            echo "ID Personaje 1: ";
            $idP1 = (int) trim(fgets(STDIN));

            echo "ID Personaje 2: ";
            $idP2 = (int) trim(fgets(STDIN));

            limpiarPantalla();
            echo "\n=== ARENAS ===\n";

            foreach ($torneo->listarArenas() as $a) {
                echo "(ID: " . $a->getId() . ") " . $a->getNombre() . "\n";
            }

            echo "ID Arena: ";
            $idArena = (int) trim(fgets(STDIN));
            limpiarPantalla();

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
            echo "Presione ENTER para continuar...";
            trim(fgets(STDIN));
            break;
        }
        case 6:{ // Ejecutar duelos pendientes
            $pendientes = $dueloDAO->listarPendientes();

            if (empty($pendientes)) {
                echo "No hay duelos pendientes.\n\n";
            }
            else{
                foreach ($pendientes as $duelo) {
                    echo "ID: " . $duelo['id'] . "\n";
                    echo "Fecha: " . $duelo['fecha'] . "\n";
                    echo "Personaje 1 | " . $duelo['nombrePersonaje2'] . "(ID: " . $duelo['idPersonaje1'] . ")\n";
                    echo "Personaje 2 | " . $duelo['nombrePersonaje2'] . "(ID: " . $duelo['idPersonaje2'] . ")\n";
                    echo "Arena ID: " . $duelo['idArena'] . "\n";
                    echo "-------------------------\n";
                }
                echo "¿Desea Ejecutar los duelos? (S/N)\n";
                $ejecutarlos = strtolower(trim(fgets(STDIN)));
                if($ejecutarlos == "s"){
                    $torneo->ejecutarDuelosPendientes();
                    echo "Duelos ejecutados.\n\n";
                }
                else{
                    echo "Duelos postergados.\n\n";
                }
            }
            echo "Presione ENTER para continuar...";
            trim(fgets(STDIN));
            break;
        }
        case 7:{
            echo "\n=== PERSONAJES LESIONADOS ===\n";
            foreach ($torneo->listarPersonajes("lesionado") as $p) {
                echo "(ID: " . $p->getId() . ") " . $p->getNombre() . ", Estado: Lesionado\n";
            }
            echo "-------------------------\n\n";
            echo "¿Desea Recuperar a los Personajes lesionados? (S/N)\n";
                $recuperarlos = strtolower(trim(fgets(STDIN)));
                if($recuperarlos == "s"){
                    foreach ($torneo->listarPersonajes("lesionado") as $p) {
                        $p->recuperarVida(30.0);
                        $p->recuperarEnergia(10.0);
                        $p->setEstado("disponible");
                    }
                    echo "-------------------------\n";
                    echo "Personajes Recuperados!\n\n";
                    echo "Presione ENTER para continuar...";
                    trim(fgets(STDIN));
                    break;
                }
                else{
                    echo "-------------------------\n";
                    echo "Presione ENTER para continuar...";
                    trim(fgets(STDIN));
                    break;
                }
                
        }
        case 8:{

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
            echo "Presione ENTER para continuar...";
            trim(fgets(STDIN));
            break;
        }
        /* case 111:{

            $personajes = $torneo->listarPersonajes();

            if (empty($personajes)) {
                echo "No hay personajes registrados.\n";
            } else {
                echo "===== LISTA DE PERSONAJES =====\n\n";
                foreach ($personajes as $p) {
                    echo $p->datosPersonaje();
                    echo "----------------------------\n\n";
                }
            }
            echo "Presione ENTER para continuar...";
            trim(fgets(STDIN));
            break;
        }
        case 211:{
            
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
            echo "Presione ENTER para continuar...";
            trim(fgets(STDIN));
            break;
        }
        case 323:{

            $arenas = $torneo->listarArenas();

            if (empty($arenas)) {
                echo "No hay arenas registradas.\n";
            } else {
                foreach ($arenas as $arena) {
                    echo $arena->datosArena() . "\n";
                }
            }

            break;
        }
        case 7:{

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
        }
        case 8:{

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
        }
        case 9:{

            echo "ID personaje: ";
            $id = (int) trim(fgets(STDIN));

            echo "Porcentaje: ";
            echo $torneo->porcentajeVictorias($id);
            echo "%\n";

            break;
        
        }
        case 12:{
            echo "\n=== PERSONAJES LESIONADOS ===\n";
            $personajes = $torneo->listarPersonajes("lesionado");

            if (empty($personajes)) {
                echo "No hay personajes lesionados.\n";
            } else {
                foreach ($personajes as $p) {
                    echo $p->datosPersonaje() . "\n";
                    echo "============================\n";
                }
            }

            break;
        }
        case 13:{ // Personajes retirados

            $personajes = $torneo->listarPersonajes("retirado");

            if (empty($personajes)) {
                echo "No hay personajes retirados.\n";
            } else {
                foreach ($personajes as $p) {
                    echo $p->datosPersonaje() . "\n";
                }
            }

            break;
        }
        case 14:{ // Personajes listos para duelar

            $personajes = $torneo->listarPersonajes();

            $encontrados = false;

            foreach ($personajes as $p) {
                if ($p->puedeDuelar()) {
                    echo $p->datosPersonaje() . "\n";
                    $encontrados = true;
                }
            }

            if (!$encontrados) {
                echo "No hay personajes listos para duelar.\n";
            }

            break;
        }
        case 15:{ // Armas con dueño

            $armas = $torneo->listarArmas();
            $personajes = $torneo->listarPersonajes();

            foreach ($armas as $arma) {

                echo $arma->datosArma();

                $duenio = null;

                foreach ($personajes as $p) {
                    $armaEquipada = $p->getArmaEquipada();

                    if (
                        $armaEquipada !== null &&
                        $armaEquipada->getId() === $arma->getId()
                    ) {
                        $duenio = $p;
                        break;
                    }
                }

                if ($duenio !== null) {
                    echo "Equipada por: " . $duenio->getNombre() . "\n";
                } else {
                    echo "Equipada por: Nadie\n";
                }

                echo "\n";
            }

            break;
        }
        case 16:{ // Historial general de duelos

            $duelos = $dueloDAO->listar();

            if (empty($duelos)) {
                echo "No existen duelos.\n";
            }

            foreach ($duelos as $duelo) {

                echo "ID: " . $duelo->getId() . "\n";
                echo "Fecha: " . $duelo->getFecha() . "\n";
                echo "Estado: " . $duelo->getEstado() . "\n";
                echo "P1: " . $duelo->getPersonaje1()->getNombre() . "\n";
                echo "P2: " . $duelo->getPersonaje2()->getNombre() . "\n";

                if ($duelo->getGanador() !== null) {
                    echo "Ganador: " . $duelo->getGanador()->getNombre() . "\n";
                }

                echo "-------------------------\n";
            }

            break;
        }
        case 17:{ // Duelos pendientes

            $pendientes = $dueloDAO->listarPendientes();

            if (empty($pendientes)) {
                echo "No hay duelos pendientes.\n";
            }

            foreach ($pendientes as $duelo) {

                echo "ID: " . $duelo['id'] . "\n";
                echo "Fecha: " . $duelo['fecha'] . "\n";
                echo "Personaje 1 ID: " . $duelo['idPersonaje1'] . "\n";
                echo "Personaje 2 ID: " . $duelo['idPersonaje2'] . "\n";
                echo "Arena ID: " . $duelo['idArena'] . "\n";
                echo "-------------------------\n";
            }

            break;
        }
        case 18:{ // Historial de un personaje

            echo "ID Personaje: ";
            $idPersonaje = (int) trim(fgets(STDIN));

            $duelos = $dueloDAO->listar();

            $encontrado = false;

            foreach ($duelos as $duelo) {

                if (
                    $duelo->getPersonaje1()->getId() == $idPersonaje ||
                    $duelo->getPersonaje2()->getId() == $idPersonaje
                ) {

                    echo "Duelo #" . $duelo->getId() . "\n";
                    echo "Fecha: " . $duelo->getFecha() . "\n";
                    echo "Estado: " . $duelo->getEstado() . "\n";

                    if ($duelo->getGanador() !== null) {
                        echo "Ganador: " . $duelo->getGanador()->getNombre() . "\n";
                    }

                    echo "-------------------------\n";

                    $encontrado = true;
                }
            }

            if (!$encontrado) {
                echo "No tiene duelos registrados.\n";
            }

            break;
        }
        case 19:{ // Porcentaje de victorias de todos

            $personajes = $torneo->listarPersonajes();

            foreach ($personajes as $p) {

                echo $p->getNombre();
                echo " -> ";
                echo $torneo->porcentajeVictorias($p->getId());
                echo "%\n";
            }

            break;
        }
        */
        case 0:{

            echo "Fin del programa.\n";

            break;
        }
        default:{

            echo "Opción inválida.\n";
        }
    }

} while ($opcion !== 0);