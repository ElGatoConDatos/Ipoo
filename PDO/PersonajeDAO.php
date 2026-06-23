<?php
require_once __DIR__ . '/../Personaje.php';
require_once __DIR__ . '/ArmaDAO.php';
class PersonajeDAO
{
    private PDO $conexion;
    private ArmaDAO $armaDAO;
    public function __construct(PDO $conexion, ArmaDAO $armaDAO)
    {
        $this->conexion = $conexion;
        $this->armaDAO = $armaDAO;
    }

    public function alta(Personaje $personaje): bool
    {
        $fuerza = null;
        $armadura = null;
        $mana = null;
        $inteligencia = null;
        $precision = null;
        $velocidad = null;

        if ($personaje instanceof Guerrero) {
            $fuerza = $personaje->getFuerza();
            $armadura = $personaje->getArmadura();
        }

        if ($personaje instanceof Mago) {
            $mana = $personaje->getMana();
            $inteligencia = $personaje->getInteligencia();
        }

        if ($personaje instanceof Arquero) {
            $precision = $personaje->getPrecision();
            $velocidad = $personaje->getVelocidad();
        }

        $sql = "INSERT INTO personajes
    (
        nombre,
        tipoPersonaje,
        nivel,
        puntosVida,
        energia,
        duelosGanados,
        duelosPerdidos,
        estado,
        idArmaEquipada,
        fuerza,
        armadura,
        mana,
        inteligencia,
        precisionPersonaje,
        velocidad
    )
    VALUES
    (
        :nombre,
        :tipoPersonaje,
        :nivel,
        :puntosVida,
        :energia,
        :duelosGanados,
        :duelosPerdidos,
        :estado,
        :idArmaEquipada,
        :fuerza,
        :armadura,
        :mana,
        :inteligencia,
        :precisionPersonaje,
        :velocidad
    )";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':nombre' => $personaje->getNombre(),
            ':tipoPersonaje' => strtolower($personaje->getClase()),
            ':nivel' => $personaje->getNivel(),
            ':puntosVida' => $personaje->getPuntosVida(),
            ':energia' => $personaje->getEnergia(),
            ':duelosGanados' => $personaje->getDuelosGanados(),
            ':duelosPerdidos' => $personaje->getDuelosPerdidos(),
            ':estado' => $personaje->getEstado(),
            ':idArmaEquipada' => $personaje->getArmaEquipada()?->getId(),
            ':fuerza' => $fuerza,
            ':armadura' => $armadura,
            ':mana' => $mana,
            ':inteligencia' => $inteligencia,
            ':precisionPersonaje' => $precision,
            ':velocidad' => $velocidad
        ]);
    }

    public function baja(int $id): bool
    {
        $sql = "DELETE FROM personajes WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function buscarPorId(int $id): ?Personaje
    {
        $sql = "SELECT * FROM personajes WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$fila)
            return null;

        // 1. Instanciamos el objeto según la clase
        $personaje = null;

        switch (strtolower($fila['tipoPersonaje'])) {

            case 'guerrero':
                $personaje = new Guerrero(
                    (int) $fila['id'],
                    (string) $fila['nombre'],
                    (int) $fila['nivel'],
                    (float) $fila['puntosVida'],
                    (float) $fila['energia'],
                    (int) $fila['duelosGanados'],
                    (int) $fila['duelosPerdidos'],
                    (string) $fila['estado'],
                    (float) ($fila['fuerza'] ?? 0),
                    (float) ($fila['armadura'] ?? 0)
                );
                break;

            case 'mago':
                $personaje = new Mago(
                    (int) $fila['id'],
                    (string) $fila['nombre'],
                    (int) $fila['nivel'],
                    (float) $fila['puntosVida'],
                    (float) $fila['energia'],
                    (int) $fila['duelosGanados'],
                    (int) $fila['duelosPerdidos'],
                    (string) $fila['estado'],
                    (float) ($fila['mana'] ?? 0),
                    (float) ($fila['inteligencia'] ?? 0)
                );
                break;

            case 'arquero':
                $personaje = new Arquero(
                    (int) $fila['id'],
                    (string) $fila['nombre'],
                    (int) $fila['nivel'],
                    (float) $fila['puntosVida'],
                    (float) $fila['energia'],
                    (int) $fila['duelosGanados'],
                    (int) $fila['duelosPerdidos'],
                    (string) $fila['estado'],
                    (float) ($fila['precisionPersonaje'] ?? 0),
                    (float) ($fila['velocidad'] ?? 0)
                );
                break;
        }

        if ($personaje && $fila['idArmaEquipada'] !== null) {

            $arma = $this->armaDAO->buscarPorId(
                (int) $fila['idArmaEquipada']
            );

            if ($arma !== null) {
                $personaje->setArmaEquipada($arma);
            }
        }

        return $personaje;
    }
    public function actualizar(Personaje $personaje): bool
    {
        $fuerza = null;
        $armadura = null;
        $mana = null;
        $inteligencia = null;
        $precision = null;
        $velocidad = null;

        if ($personaje instanceof Guerrero) {
            $fuerza = $personaje->getFuerza();
            $armadura = $personaje->getArmadura();
        }

        if ($personaje instanceof Mago) {
            $mana = $personaje->getMana();
            $inteligencia = $personaje->getInteligencia();
        }

        if ($personaje instanceof Arquero) {
            $precision = $personaje->getPrecision();
            $velocidad = $personaje->getVelocidad();
        }

        $sql = "UPDATE personajes SET
            nombre = :nombre,
            tipoPersonaje = :tipoPersonaje,
            nivel = :nivel,
            puntosVida = :puntosVida,
            energia = :energia,
            duelosGanados = :duelosGanados,
            duelosPerdidos = :duelosPerdidos,
            estado = :estado,
            idArmaEquipada = :idArmaEquipada,
            fuerza = :fuerza,
            armadura = :armadura,
            mana = :mana,
            inteligencia = :inteligencia,
            precisionPersonaje = :precisionPersonaje,
            velocidad = :velocidad
            WHERE id = :id";

        $stmt = $this->conexion->prepare($sql);

        $arma = $personaje->getArmaEquipada();
        $idArma = ($arma !== null) ? $arma->getId() : null;

        return $stmt->execute([
            ':id' => $personaje->getId(),
            ':nombre' => $personaje->getNombre(),
            ':tipoPersonaje' => strtolower($personaje->getClase()),
            ':nivel' => $personaje->getNivel(),
            ':puntosVida' => $personaje->getPuntosVida(),
            ':energia' => $personaje->getEnergia(),
            ':duelosGanados' => $personaje->getDuelosGanados(),
            ':duelosPerdidos' => $personaje->getDuelosPerdidos(),
            ':estado' => strtolower($personaje->getEstado()),
            ':idArmaEquipada' => $idArma,
            ':fuerza' => $fuerza,
            ':armadura' => $armadura,
            ':mana' => $mana,
            ':inteligencia' => $inteligencia,
            ':precisionPersonaje' => $precision,
            ':velocidad' => $velocidad
        ]);
    }

    public function listar(string $estado): array
    {
        $sql = "SELECT id FROM personajes";
        if ($estado !== "") {
            $sql .= " WHERE estado = :estado";
        }
        $stmt = $this->conexion->prepare($sql);
        if ($estado !== "") {
            $stmt->execute([':estado' => $estado]);
        } else {
            $stmt->execute();
        }

        $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $listaDeObjetos = [];

        // Convertimos cada ID en un objeto real usando el método buscarPorId
        foreach ($ids as $id) {
            $listaDeObjetos[] = $this->buscarPorId($id);
        }
        return $listaDeObjetos;
    }

}