<?php
class ArenaDAO
{
    private PDO $conexion;
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function alta(Arena $arena): bool
    {
        $sql = "INSERT INTO arenas
        (nombre, dificultad, capacidadPublico, clima)
        VALUES
        (:nombre, :dificultad, :capacidadPublico, :clima)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':nombre' => $arena->getNombre(),
            ':dificultad' => strtolower($arena->getDificultad()),
            ':capacidadPublico' => $arena->getCapacidadPublico(),
            ':clima' => strtolower($arena->getClima()),
        ]);
    }

    public function baja(int $id): bool
    {
        $sql = "DELETE FROM arenas WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function modificar(Arena $arena): bool
    {
        $sql = "UPDATE arenas SET
        id = :id,
        nombre = :nombre,
        dificultad = :dificultad,
        capacidadPublico = :capacidadPublico,
        clima = :clima
        WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':id' => $arena->getId(),
            ':nombre' => $arena->getNombre(),
            ':dificultad' => $arena->getDificultad(),
            ':capacidadPublico' => $arena->getCapacidadPublico(),
            ':clima' => $arena->getClima(),

        ]);
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
    public function listar(): array
    {
        $sql = "SELECT * FROM arenas";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}