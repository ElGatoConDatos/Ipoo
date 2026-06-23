<?php
class Torneo
{
    private PersonajeDAO $personajeDAO;
    private ArmaDAO $armaDAO;
    private ArenaDAO $arenaDAO;
    private DueloDAO $dueloDAO;

    public function __construct(
        PersonajeDAO $personajeDAO,
        ArmaDAO $armaDAO,
        ArenaDAO $arenaDAO,
        DueloDAO $dueloDAO
    ) {
        $this->personajeDAO = $personajeDAO;
        $this->armaDAO = $armaDAO;
        $this->arenaDAO = $arenaDAO;
        $this->dueloDAO = $dueloDAO;
    }
    public function agregarPersonaje(Personaje $p): bool
    {
        return $this->personajeDAO->alta($p);
    }

    public function agregarArma(Arma $a): bool
    {
        return $this->armaDAO->alta($a);
    }

    public function agregarArena(Arena $a): bool
    {
        return $this->arenaDAO->alta($a);
    }

    public function registrarDuelo(Duelo $d): bool
    {
        return $this->dueloDAO->alta($d);
    }
    public function equiparArma(int $idPersonaje, int $idArma): bool
    {
        $personaje = $this->personajeDAO->buscarPorId($idPersonaje);
        $arma = $this->armaDAO->buscarPorId($idArma);

        if (!$personaje || !$arma) {
            return false;
        }

        if (!$arma->puedeSerEquipadaPor($personaje)) {
            return false;
        }

        $personaje->setArmaEquipada($arma);
        $arma->equiparArma();

        $this->personajeDAO->actualizar($personaje);
        $this->armaDAO->actualizar($arma);

        return true;
    }
    public function ejecutarDuelosPendientes(): void
    {
        $pendientes = $this->dueloDAO->listarPendientes();

        foreach ($pendientes as $data) {

            $p1 = $this->personajeDAO->buscarPorId($data['idPersonaje1']);
            $p2 = $this->personajeDAO->buscarPorId($data['idPersonaje2']);
            $arena = $this->arenaDAO->buscarPorId($data['idArena']);

            $duelo = new Duelo(
                $data['id'],
                $p1,
                $p2,
                $arena,
                $data['fecha'],
                $data['estado']
            );

            $duelo->realizarDuelo();

            $this->dueloDAO->actualizar($duelo);
            $this->personajeDAO->actualizar($p1);
            $this->personajeDAO->actualizar($p2);
        }
    }
    public function buscarPersonaje(int $id): ?Personaje
    {
        return $this->personajeDAO->buscarPorId($id);
    }

    public function buscarArma(int $id): ?Arma
    {
        return $this->armaDAO->buscarPorId($id);
    }

    public function buscarArena(int $id): ?Arena
    {
        return $this->arenaDAO->buscarPorId($id);
    }
    public function listarPersonajes(string $estado = ""): array
    {
        return $this->personajeDAO->listar($estado);
    }

    public function listarArmas(string $estado = ""): array
    {
        return $this->armaDAO->listar($estado);
    }

    public function listarArenas(): array
    {
        return $this->arenaDAO->listar();
    }
    public function rankingVictorias(): array
    {
        return $this->dueloDAO->rankingVictorias();
    }

    public function personajeMasVictorias(): ?array
    {
        return $this->dueloDAO->personajeMasVictorias();
    }

    public function arenaMasDuelos(): ?array
    {
        return $this->dueloDAO->arenaMasDuelos();
    }

    public function porcentajeVictorias(int $id): float
    {
        return $this->dueloDAO->porcentajeVictorias($id);
    }
}
