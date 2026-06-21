<?php
include_once 'arena.php';


class Duelo{
    private int $id;
    private Personaje $personaje1;
    private Personaje $personaje2;
    private Arena $arena;
    private string $fecha;
    private string $estado;
    private string $ganador;

    
    //CONSTRUCT
    public function __construct(int $id, Personaje $personaje1, Personaje $personaje2, Arena $arena, string $fecha, string $estado){
        $this->id = $id;
        $this->personaje1 = $personaje1;
        $this->personaje2 = $personaje2;
        $this->arena = $arena;
        $this->estado = $estado;
        $this->ganador = "";
    }
    
    
    //SETTER
    public function setId(int $id): void{
        $this->id = $id;
    }
    public function setPersonaje1(Personaje $personaje1): void{
        $this->personaje1 = $personaje1;
    }
    public function setPersonaje2(Personaje $personaje2): void{
        $this->personaje2 = $personaje2;
    }
    public function setArena(Arena $arena): void{
        $this->arena = $arena;
    }
    public function setFecha(string $fecha): void{
        $this->fecha = $fecha;
    }
    public function setEstado(string $estado): void{
        $this->estado = $estado;
    }
    public function setGanador(string $ganador): void{
        $this->ganador = $ganador;
    }

    //GETTER
    public function getId(): int{
        return $this->id;
    }
    public function getPersonaje1(): Personaje{
        return $this->personaje1;
    }
    public function getPersonaje2(): Personaje{
        return $this->personaje2;
    }
    public function getArena(): Arena{
        return $this->arena;
    }
    public function getFecha(): string{
        return $this->fecha;
    }
    public function getEstado(): string{
        return $this->estado;
    }
    public function getGanador(): string{
        return $this->ganador;
    }

    



    
    public function puedeRealizarse(): bool{
        $respuesta = false;
        if ($this->getPersonaje1()->getId() !== $this->getPersonaje2()->getId()){
            if($this->getPersonaje1()->puedeDuelar() && $this->getPersonaje2()->puedeDuelar()){
                $respuesta = true;
            }
        }
        return $respuesta;
    }
    
    public function realizarDuelo(): void {
    if (!$this->puedeRealizarse()) {
        $this->setEstado("Cancelado");
    }

    $p1PoderFinal = $this->getPersonaje1()->calcularPoderTotal() + $this->getArena()->calcularModificadorArena($this->getPersonaje1());
    $p2PoderFinal = $this->getPersonaje2()->calcularPoderTotal() + $this->getArena()->calcularModificadorArena($this->getPersonaje2());


    $ganador = ($p1PoderFinal > $p2PoderFinal) ? $this->getPersonaje1() : $this->getPersonaje2();
    $perdedor = ($p1PoderFinal > $p2PoderFinal) ? $this->getPersonaje2() : $this->getPersonaje1();

    $ganador->setNivel($ganador->getNivel() + 1);
    $ganador->recuperarEnergia(5);
    $ganador->setDuelosGanados($ganador->getDuelosGanados() + 1);

    $perdedor->setDuelosPerdidos($perdedor->getDuelosPerdidos() + 1);
    
    $perdedor->recuperarEnergia(-5); 
    
    $danio = $p1PoderFinal - $p2PoderFinal;
    $perdedor->recibirDanio($danio);

    
    if ($perdedor->getPuntosVida() <= 0) {
        $perdedor->setEstado("Retirado"); 
    }

    $this->setGanador($ganador->getNombre());
    $this->setEstado("Realizado");
    }
    public function obtenerGanador(): string {
    return $this->getGanador();
    }
    
}