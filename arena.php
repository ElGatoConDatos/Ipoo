<?php
include_once 'personaje.php';
include_once 'arma.php';

class Arena{
    // ATRIBUTOS
    private int $id;
    private string $nombre;
    private string $dificultad;
    private int $capacidadPublico;
    private string $clima;
    
    // CONSTRUCT
    public function __construct(int $id, string $nombre, string $dificultad, int $capacidadPublico, string $clima){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->dificultad = $dificultad;
        $this->capacidadPublico = $capacidadPublico;
        $this->clima = $clima;
    }

    //SETTER
    public function setId(int $id): void{
        $this->id = $id;
    }
    public function setNombre(string $nombre): void{
        $this->nombre = $nombre;
    }
    public function setDificultad(string $dificultad): void{
        $this->dificultad = $dificultad;
    }
    public function setCapacidadPublico(int $capacidadPublico): void{
        $this->capacidadPublico = $capacidadPublico;
    }
    public function setClima(string $clima): void{
        $this->clima = $clima;
    }

    //GETTER
    public function getId(): int{
        return  $this->id;
    }
    public function getNombre(): string{
        return  $this->nombre;
    }
    public function getDificultad(): string{
        return  $this->dificultad;
    }
    public function getCapacidadPublico(): int{
        return  $this->capacidadPublico;
    }
    public function getClima(): string{
        return  $this->clima;
    }

    // FUNCIONES
    public function calcularModificadorArena(Personaje $personaje): int {
        $clima = $this->getClima();
        $clase = $personaje->getClase();
        $modificador = 0;
        if ($clima == "Lluvia") {
            if ($clase == "Mago"){
                $modificador = 5;
            }
            if ($clase == "Arquero"){
                $modificador = -10;
            } 
        } 
        elseif ($clima == "Tormenta") {
            if ($clase == "Mago"){
                $modificador = 15;
            }    
            if ($clase == "Arquero"){
                $modificador = -5;
            } 
            if ($clase == "Guerrero"){
                $modificador = -5;
            }
        } 
        elseif ($clima == "Niebla") {
            if ($clase == "Guerrero"){
                $modificador = 5;
            }
            if ($clase == "Arquero"){
                $modificador = -15;
            }
        }
        return $modificador;
    }

    public function datosArena(){
        $cadena = "Arena: " . $this->getNombre();
        $cadena .= " (ID: " . $this->getId() . ")\n";
        $cadena .= "Dificultad: " . $this->getDificultad() . "\n";
        $cadena .= "Clima: " . $this->getClima()."\n";
        return $cadena;
    }
}
