<?php
class PersonajeDAO{
    private PDO $conexion;
    public function __construct(PDO $conexion){
        $this->conexion = $conexion;
    }
    
    public function alta(Personaje $personaje): bool{
        $sql = "INSERT INTO personajes
        (nombre, tipoPersonaje, nivel, puntosVida, energia, duelosGanados, duelosPerdidos, estado)
        VALUES
        (:nombre, :tipoPersonaje, :nivel, :puntosVida, :energia, :duelosGanados, :duelosPerdidos, :estado)";
        $stmt = $this->conexion->prepare($sql);
        RETURN $stmt->execute([
            ':nombre' => $personaje->getNombre(),
            ':tipoPersonaje' => strtolower($personaje->getClase()),
            ':nivel' => $personaje->getNivel(),
            ':puntosVida' => $personaje->getPuntosVida(),
            ':energia' => $personaje->getEnergia(),
            ':duelosGanados' => $personaje->getDuelosGanados(),
            ':duelosPerdidos' => $personaje->getDuelosPerdidos(),
            ':estado' => $personaje->getEstado(),
        ]);
    }

    public function baja(int $id): bool{
        $sql = "DELETE FROM personajes WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }

        public function buscarPorId(int $id): ?Personaje {
        $sql = "SELECT * FROM personajes WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$fila) return null;

        // 1. Instanciamos el objeto según la clase
        $personaje = null;
        if ($fila['tipoPersonaje'] == 'guerrero') {
            $personaje = new Guerrero($fila['id'], $fila['nombre'], $fila['nivel'], $fila['puntosVida'], $fila['energia'], $fila['duelosGanados'], $fila['duelosPerdidos'], $fila['estado'], 10, 10);
        } elseif ($fila['tipoPersonaje'] == 'mago') {
            $personaje = new Mago($fila['id'], $fila['nombre'], $fila['nivel'], $fila['puntosVida'], $fila['energia'], $fila['duelosGanados'], $fila['duelosPerdidos'], $fila['estado'], 15, 15);
        } elseif ($fila['tipoPersonaje'] == 'arquero') {
            $personaje = new Arquero($fila['id'], $fila['nombre'], $fila['nivel'], $fila['puntosVida'], $fila['energia'], $fila['duelosGanados'], $fila['duelosPerdidos'], $fila['estado'], 20, 20);
        }

        // 2. RECUPERAR Y ASIGNAR EL ARMA (LA CLAVE DEL PROBLEMA)
        if ($personaje && !empty($fila['idArmaEquipada'])) {
            // Necesitas usar ArmaDAO para buscar el objeto Arma real
            $armaDAO = new ArmaDAO($this->conexion);
            $arma = $armaDAO->buscarPorId($fila['idArmaEquipada']);
            
            if ($arma) {
                $personaje->setArmaEquipada($arma);
            }
        }

        return $personaje;
    }
    public function actualizar(Personaje $personaje): bool {
        $sql = "UPDATE personajes SET
                nombre = :nombre,
                tipoPersonaje = :tipoPersonaje,
                nivel = :nivel,
                puntosVida = :puntosVida,
                energia = :energia,
                duelosGanados = :duelosGanados,
                duelosPerdidos = :duelosPerdidos,
                estado = :estado,
                idArmaEquipada = :idArmaEquipada
                WHERE id = :id";
                
        $stmt = $this->conexion->prepare($sql);
        
        // Obtenemos el ID del arma si existe, si no, enviamos null
        $arma = $personaje->getArmaEquipada(); // Asegúrate de tener este método en Personaje
        $idArma = ($arma !== null) ? $arma->getId() : null;

        return $stmt->execute([
            ':nombre' => $personaje->getNombre(),
            ':tipoPersonaje' => strtolower($personaje->getClase()),
            ':nivel' => $personaje->getNivel(),
            ':puntosVida' => $personaje->getPuntosVida(),
            ':energia' => $personaje->getEnergia(),
            ':duelosGanados' => $personaje->getDuelosGanados(),
            ':duelosPerdidos' => $personaje->getDuelosPerdidos(),
            ':estado' => $personaje->getEstado(),
            ':idArmaEquipada' => $idArma,
            ':id' => $personaje->getId()
        ]);
    }

        public function listar(string $estado): array {
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
        
        // Convertimos cada ID en un objeto real usando el método que ya tienes
        foreach ($ids as $id) {
            $listaDeObjetos[] = $this->buscarPorId($id);
        }
        return $listaDeObjetos;
    }
    
}