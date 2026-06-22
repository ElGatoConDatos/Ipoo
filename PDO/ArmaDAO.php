<?php
class ArmaDAO{
    private PDO $conexion;
    public function __construct(PDO $conexion){
        $this->conexion = $conexion;
    }
    
   public function listar(string $estado): array{
        $sql = "SELECT * FROM armas WHERE estado = :estado";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':estado' => $estado]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id): ?Arma{
    $sql = "SELECT * FROM armas WHERE id = ?";
    $stmt = $this->conexion->prepare($sql);
    $stmt->execute([$id]);

    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$fila) {
        return null;
    }

    return new Arma(
        $fila['id'],
        $fila['nombre'],
        $fila['tipo'],
        $fila['danioBase'],
        $fila['nivelMinimo'],
        $fila['estado']
    );
}

    public function actualizar(Arma $arma): bool{
    $sql = "UPDATE armas SET
            nombre = :nombre,
            tipo = :tipo,
            danioBase = :danioBase,
            nivelMinimo = :nivelMinimo,
            estado = :estado
            WHERE id = :id";

    $stmt = $this->conexion->prepare($sql);

    return $stmt->execute([
        ':nombre' => $arma->getNombre(),
        ':tipo' => $arma->getTipo(),
        ':danioBase' => $arma->getDanioBase(),
        ':nivelMinimo' => $arma->getNivelMinimo(),
        ':estado' => strtolower($arma->getEstado()),
        ':id' => $arma->getId()
    ]);
}

    public function alta(Arma $arma): bool{
        $sql = "INSERT INTO armas
        (nombre, tipo, danioBase, nivelMinimo, estado)
        VALUES
        (:nombre, :tipo, :danioBase, :nivelMinimo, :estado)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':nombre' => $arma->getNombre(),
            ':tipo' => strtolower($arma->getTipo()),
            ':danioBase' => $arma->getDanioBase(),
            ':nivelMinimo' => $arma->getNivelMinimo(),
            ':estado' => strtolower($arma->getEstado())
        ]);
    }

    public function baja(int $id): bool{
        $sql = "DELETE FROM armas WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }
    
}