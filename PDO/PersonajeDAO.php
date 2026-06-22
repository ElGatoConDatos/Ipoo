<?php
class PersonajeDAO{
    private PDO $conexion;
    public function __construct(PDO $conexion){
        $this->conexion = $conexion;
    }
    
    public function alta(Personaje $personaje): bool{
        $sql = "INSERT INTO personajes
        (nombre, tipoPersonaje, nivel, vida, energia, duelosGanados, duelosPerdidos, estado)
        VALUES
        (:nombre, :tipoPersonaje, :nivel, :vida, :energia, :duelosGanados, :duelosPerdidos, :estado)";
        $stmt = $this->conexion->prepare($sql);
        RETURN $stmt->execute([
            ':nombre' => $personaje->getNombre(),
            ':tipoPersonaje' => strtolower($personaje->getClase()),
            ':nivel' => $personaje->getNivel(),
            ':puntosVida' => $personaje->getPuntosVida(),
            ':energia' => $personaje->getEnergia(),
            ':DuelosGanados' => $personaje->getDuelosGanados(),
            ':DuelosPerdidos' => $personaje->getDuelosPerdidos(),
            ':estado' => $personaje->getEstado(),
        ]);
    }

    public function baja(int $id): bool{
        $sql = "DELETE FROM personajes WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function buscarPorId(int $id): ?Personaje{
        $sql = "SELECT * FROM personajes WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($fila['tipoPersonaje'] == 'guerrero') {
            return new Guerrero(
                $fila['id'],
                $fila['nombre'],
                $fila['nivel'],
                $fila['puntosVida'],
                $fila['energia'],
                $fila['duelosGanados'],
                $fila['duelosPerdidos'],
                $fila['estado'],
                10,
                10
            );
        } elseif ($fila['tipoPersonaje'] == 'mago') {
            return new Mago(
                $fila['id'],
                $fila['nombre'],
                $fila['nivel'],
                $fila['puntosVida'],
                $fila['energia'],
                $fila['duelosGanados'],
                $fila['duelosPerdidos'],
                $fila['estado'],
                15,
                15
            );
        } elseif ($fila['tipoPersonaje'] == 'arquero') {
            return new Arquero(
                $fila['id'],
                $fila['nombre'],
                $fila['nivel'],
                $fila['puntosVida'],
                $fila['energia'],
                $fila['duelosGanados'],
                $fila['duelosPerdidos'],
                $fila['estado'],
                20,
                20
            );
        }
        return null;
    }

    public function actualizar(Personaje $personaje): bool{
        $sql = "UPDATE personajes SET
        nombre = :nombre,
        tipoPersonaje = :tipoPersonaje,
        nivel = :nivel,
        puntosVida = :puntosVida,
        energia = :energia,
        duelosGanados = :duelosGanados,
        duelosPerdidos = :duelosPerdidos,
        estado = :estado
        WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':nombre' => $personaje->getNombre(),
            ':tipo' => strtolower($personaje->getClase()),
            ':nivel' => $personaje->getNivel(),
            ':puntosVida' => $personaje->getPuntosVida(),
            ':energia' => $personaje->getEnergia(),
            ':DuelosGanados' => $personaje->getDuelosGanados(),
            ':DuelosPerdidos' => $personaje->getDuelosPerdidos(),
            ':estado' => $personaje->getEstado(),
            ':id' => $personaje->getId()
        ]);
    }

    public function listar(string $estado): array{
        $sql = "SELECT * FROM personajes";
        if ($estado !== null) {
            $sql .= " WHERE estado = :estado";
        }
        $stmt = $this->conexion->prepare($sql);
        if ($estado !== null) {
            $stmt->execute([':estado' => $estado]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}