<?php
include_once 'personaje.php';

class Arma{
    private int $id;
    private string $nombre;
    private string $tipo;
    private float $danioBase;
    private int $nivelMinimo;
    private string $estado;

    //CONSTRUCT
    public function __construct(int $id, string $nombre, string $tipo, float $danioBase, int $nivelMinimo, string $estado){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->danioBase = $danioBase;
        $this->nivelMinimo = $nivelMinimo;
        $this->estado = $estado;
    }

    // SETTERS
    public function setId(int $id): void{
        $this->id = $id;
    }
    public function setNombre(string $nombre): void{
        $this->nombre = $nombre;
    }
    public function setTipo(string $tipo): void{
        $this->tipo = $tipo;
    }
    public function setDanioBase(float $danioBase): void{
        $this->danioBase = $danioBase;
    }
    public function setNivelMinimo(int $nivelMinimo): void{
        $this->nivelMinimo = $nivelMinimo;
    }
    public function setEstado(string $estado): void{
        $this->estado = $estado;
    }
    // GETTERS 
    public function getId(): int{
        return $this->id;
    }
    public function getNombre(): string{
        return $this->nombre;
    }
    public function getTipo(): string{
        return $this->tipo;
    }
    public function getDanioBase(): float{
        return $this->danioBase;
    }
    public function getNivelMinimo(): int{
        return $this->nivelMinimo;
    }
    public function getEstado(): string{
        return $this->estado;
    }

    public function calcularDanio(){
        return $this->getDanioBase() * ($this->getNivelMinimo() * 0.2);
    }

    public function equiparArma(): void{
        $this->setEstado("Equipada");
    }

    public function puedeSerEquipadaPor(Personaje $personaje): bool{
        $tieneRequisitos = false;
        if($this->getEstado() == "Disponible"){
            if ($personaje->getNivel() >= $this->getNivelMinimo()){
                $tieneRequisitos = true;
            }
        }
        return $tieneRequisitos;
    }
}