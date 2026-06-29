<?php

class DueloDAO
{

    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function alta(Duelo $duelo): bool
    {

        $sql = "INSERT INTO duelos
        (
            idPersonaje1,
            idPersonaje2,
            idArena,
            fecha,
            estado,
            idGanador,
            poderPersonaje1,
            poderPersonaje2,
            danioAplicado
        )
        VALUES
        (
            :idPersonaje1,
            :idPersonaje2,
            :idArena,
            :fecha,
            :estado,
            :idGanador,
            :poder1,
            :poder2,
            :danio
        )";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':idPersonaje1' => $duelo->getPersonaje1()->getId(),
            ':idPersonaje2' => $duelo->getPersonaje2()->getId(),
            ':idArena' => $duelo->getArena()->getId(),
            ':fecha' => $duelo->getFecha(),
            ':estado' => $duelo->getEstado(),
            ':idGanador' => null,
            ':poder1' => 0,
            ':poder2' => 0,
            ':danio' => 0
        ]);
    }

    public function actualizar(Duelo $duelo): bool
    {

        $idGanador = null;

        if ($duelo->getGanador() !== null) {
            $idGanador = $duelo->getGanador()->getId();
        }

        $sql = "UPDATE duelos SET
                estado = :estado,
                idGanador = :idGanador,
                poderPersonaje1 = :poder1,
                poderPersonaje2 = :poder2,
                danioAplicado = :danio
                WHERE id = :id";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':estado' => $duelo->getEstado(),
            ':idGanador' => $idGanador,
            ':poder1' => $duelo->getPoderPersonaje1(),
            ':poder2' => $duelo->getPoderPersonaje2(),
            ':danio' => $duelo->getDanioAplicado(),
            ':id' => $duelo->getId()
        ]);
    }

    public function listar(): array
    {
        $sql = "SELECT * FROM duelos";

        $stmt = $this->conexion->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarPendientes(): array
    {
        $sql = "SELECT * FROM duelos WHERE estado = 'pendiente'";

        $stmt = $this->conexion->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function historialPersonaje(int $idPersonaje): array
    {

        $sql = "SELECT *
                FROM duelos
                WHERE idPersonaje1 = ?
                OR idPersonaje2 = ?
                ORDER BY fecha DESC";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute([$idPersonaje, $idPersonaje]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarDuelosPorPersonaje(int $idPersonaje): array
    {
        $sql = "
        SELECT *
        FROM duelos
        WHERE idPersonaje1 = ?
        OR idPersonaje2 = ?
        ORDER BY fecha DESC
    ";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute([
            $idPersonaje,
            $idPersonaje
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function rankingVictorias(): array
    {
        $sql = "
        SELECT
            idGanador AS personaje_id,
            COUNT(*) AS total_victorias
        FROM duelos
        WHERE idGanador IS NOT NULL
        GROUP BY idGanador
        ORDER BY total_victorias DESC
    ";

        $stmt = $this->conexion->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function personajeMasVictorias(): ?array
    {
        $sql = "
        SELECT
            idGanador AS personaje_id,
            COUNT(*) AS victorias
        FROM duelos
        WHERE idGanador IS NOT NULL
        GROUP BY idGanador
        ORDER BY victorias DESC
        LIMIT 1
    ";

        $stmt = $this->conexion->query($sql);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }

    public function arenaMasDuelos(): ?array
    {
        $sql = "
        SELECT
            idArena,
            COUNT(*) AS total_duelos
        FROM duelos
        GROUP BY idArena
        ORDER BY total_duelos DESC
        LIMIT 1
    ";

        $stmt = $this->conexion->query($sql);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }
    public function porcentajeVictorias(int $personajeId): float{
    $sql = "SELECT
                (SELECT COUNT(*) FROM duelos
                 WHERE (idPersonaje1 = ? AND idGanador = ?)
                    OR (idPersonaje2 = ? AND idGanador = ?)
                ) AS victorias,
                
                (SELECT COUNT(*) FROM duelos
                 WHERE idPersonaje1 = ? OR idPersonaje2 = ?
                ) AS total_duelos";

    $stmt = $this->conexion->prepare($sql);
    $stmt->execute([
        $personajeId,
        $personajeId,
        $personajeId,
        $personajeId,
        $personajeId,
        $personajeId
    ]);

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado['total_duelos'] == 0) {
        return 0;
    }

    return round(
        ($resultado['victorias'] / $resultado['total_duelos']) * 100,
        2
    );
}
public function buscarPorId(int $id): ?array
{
    $sql = "SELECT * FROM duelos WHERE id = ?";

    $stmt = $this->conexion->prepare($sql);
    $stmt->execute([$id]);

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    return $resultado ?: null;
}
public function listarRealizados(): array
{
    $sql = "SELECT 
                d.*, 
                j1.nombre AS jugador1, 
                j2.nombre AS jugador2
            FROM duelos d
            INNER JOIN personajes j1 ON d.idPersonaje1 = j1.id
            INNER JOIN personajes j2 ON d.idPersonaje2 = j2.id
            WHERE d.estado = 'realizado'
            ORDER BY d.fecha DESC";

    $stmt = $this->conexion->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}