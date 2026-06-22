<?php
class DueloDAO{
    private PDO $conexion;
    public function __construct(PDO $conexion){
        $this->conexion = $conexion;
    }
    
    public function historialPersonaje(int $personajeId): array{
        $sql = "SELECT * FROM duelos WHERE personaje1_id = ? OR personaje2_id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$personajeId, $personajeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function arenaMasDuelos(): ?array{
        $sql = "SELECT arena_id, COUNT(*) AS total_duelos FROM duelos GROUP BY arena_id ORDER BY total_duelos DESC LIMIT 1";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

     public function personajeMasVictorias(): ?array{
        $sql = "SELECT personaje1_id AS personaje_id, COUNT(*) AS victorias FROM duelos WHERE resultado = 'personaje1' GROUP BY personaje1_id
                UNION ALL
                SELECT personaje2_id AS personaje_id, COUNT(*) AS victorias FROM duelos WHERE resultado = 'personaje2' GROUP BY personaje2_id
                ORDER BY victorias DESC LIMIT 1";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarPendientes(): array{
        $sql = "SELECT * FROM duelos WHERE resultado IS NULL";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function rankingVictorias(): array{
        $sql = "SELECT personaje_id, SUM(victorias) AS total_victorias FROM (
                    SELECT personaje1_id AS personaje_id, COUNT(*) AS victorias FROM duelos WHERE resultado = 'personaje1' GROUP BY personaje1_id
                    UNION ALL
                    SELECT personaje2_id AS personaje_id, COUNT(*) AS victorias FROM duelos WHERE resultado = 'personaje2' GROUP BY personaje2_id
                ) AS subquery GROUP BY personaje_id ORDER BY total_victorias DESC";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
   public function listar(): array{
        $sql = "SELECT * FROM duelos";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id): ?Arena
{
    $sql = "SELECT * FROM arenas WHERE id = ?";
    $stmt = $this->conexion->prepare($sql);
    $stmt->execute([$id]);

    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$fila) {
        return null;
    }

    return new Arena(
        $fila['id'],
        $fila['nombre'],
        $fila['dificultad'],
        $fila['capacidadPublico'],
        $fila['clima']
    );
}

    public function alta(Duelo $duelo): bool{
        $sql = "INSERT INTO duelos
        (personaje1_id, personaje2_id, arena_id, resultado)
        VALUES
        (:personaje1_id, :personaje2_id, :arena_id, :resultado)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':personaje1_id' => $duelo->getPersonaje1()->getId(),
            ':personaje2_id' => $duelo->getPersonaje2()->getId(),
            ':arena_id' => $duelo->getArena()->getId(),
            ':resultado' => strtolower($duelo->resumenDuelo()),
        ]);
    }
    public function actualizar(Duelo $duelo): bool{
        $sql = "UPDATE duelos SET
        personaje1_id = :personaje1_id,
        personaje2_id = :personaje2_id,
        arena_id = :arena_id,
        resultado = :resultado
        WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':personaje1_id' => $duelo->getPersonaje1()->getId(),
            ':personaje2_id' => $duelo->getPersonaje2()->getId(),
            ':arena_id' => $duelo->getArena()->getId(),
            ':resultado' => strtolower($duelo->resumenDuelo()),
            ':id' => $duelo->getId()
        ]);
    }
    public function listarDuelosPorPersonaje(int $personajeId): array{
        $sql = "SELECT * FROM duelos WHERE personaje1_id = ? OR personaje2_id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$personajeId, $personajeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function porcentajeVictorias(int $personajeId): float{
        $sql = "SELECT
                    (SELECT COUNT(*) FROM duelos WHERE (personaje1_id = ? AND resultado = 'personaje1') OR (personaje2_id = ? AND resultado = 'personaje2')) AS victorias,
                    (SELECT COUNT(*) FROM duelos WHERE personaje1_id = ? OR personaje2_id = ?) AS total_duelos";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$personajeId, $personajeId, $personajeId, $personajeId]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($resultado['total_duelos'] == 0) {
            return 0.0;
        }
        
        return ($resultado['victorias'] / $resultado['total_duelos']) * 100;
    }
}