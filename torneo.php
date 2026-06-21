<?php

class Torneo{
    // ATRIBUTOS
    private array $colPersonajes = [];
    private array $colArmas = [];
    private array $colArenas = [];
    private array $colDuelos = [];


    public function __construct(array $colPersonajes,array $colArmas,array $colArenas,array $colDuelos){
        $this->colPersonajes = $colPersonajes;
        $this->colArmas = $colArmas;
        $this->colArenas = $colArenas;
        $this->colDuelos = $colDuelos;
    }

    //SETTER
    public function setColPersonajes(array $colPersonajes):void{
        $this->colPersonajes = $colPersonajes;
    }
    public function setColArmas(array $colArmas):void{
        $this->colArmas = $colArmas;
    }
    public function setColArenas(array $colArenas):void{
        $this->colArenas = $colArenas;
    }
    public function setColDuelos(array $colDuelos):void{
        $this->colDuelos = $colDuelos;
    }

    //GETTER
    public function getColPersonajes(): array{
        return $this->colPersonajes;
    }
    public function getColArmas():array{
        return $this->colArmas;
    }
    public function getColArenas():array{
        return $this->colArenas;
    }
    public function getColDuelos():array{
        return $this->colDuelos;
    }

    // FUNCIONES
    public function agregarPersonaje (Personaje $personaje): void{
        $lista = $this->getColPersonajes();
        $lista[] = $personaje; 
        $this->setColPersonajes($lista);
    }

    public function agregarArma (Arma $arma):void   {
        $lista = $this->getColArmas();
        $lista[] = $arma;
        $this->setColArmas($lista);
    }

    public function agregarArena (Arena $arena){
        $lista = $this->getColArenas();
        $lista[] = $arena;
        $this->setColArenas($lista);
    }

    public function equiparArma(Personaje $personaje, Arma $arma): bool{
        $puedeEquiparse = false;
        if($arma->puedeSerEquipadaPor($personaje)){
            $personaje->setArmaEquipada($arma);
            $puedeEquiparse = true;
        }
        return $puedeEquiparse;
    }

    public function registrarDuelo(Duelo $duelo): void {
        $lista = $this->getColDuelos();
        $lista[] = $duelo;
        $this->setColDuelos($lista);
    }

    public function realizarDuelo(Duelo $duelo): string {
        $duelo->realizarDuelo();
        $this->registrarDuelo($duelo); 
        return $duelo->resumenDuelo();
    }
    
    public function listarPersonajes(): string {
        $lista = "=== LISTADO DE PERSONAJES ===\n";
        foreach($this->getColPersonajes() as $personaje){
            $lista .= "Nombre: " . $personaje->getNombre() . " | ";
            $lista .= "ID: " . $personaje->getID() . " | ";
            $lista .= "Nivel: " . $personaje->getNivel() . "\n";
        }
        return $lista;
    }

    public function listarArmas(): string {
        $lista = "";
        foreach($this->getColArmas() as $arma){
            $lista .= $arma->getTipo();
            $lista .= " " . $arma->getNombre();
            $lista .= " ID: " . $arma->getID(). "\n";
        }
        return $lista;
    }

    public function listarArenas(): string {
        $lista = "";
        foreach($this->getColArenas() as $arena){
            $lista .= "Nombre: ". $arena->getNombre();
            $lista .= " (ID: " . $arena->getID() . ")\n";
        }
        return $lista;
    }

    public function listarDuelos(): string {
        $lista = "---Historial de duelos----" ."\n";
        if(count($this->getColDuelos()) === 0){
            $lista = "No se registraron todavia duelos";
        }
        foreach($this->getColDuelos() as $duelo){
            $lista .= $duelo->resumenDuelo() ."\n";
        }
        return $lista;
    }

    public function rankingPersonajes() : string{
        $ranking = $this->getColPersonajes();

        usort($ranking, function($a, $b) {
        return $b->getDuelosGanados() <=> $a->getDuelosGanados();
        });

        $cadena = "==== Ranking del TOP 5 ====\n";

        $i = 1;
        foreach($ranking  as $personaje){
            if($i<=5){
                $cadena.= "TOP:".$i."\n";
                $cadena.= "Nombre: " . $personaje->getNombre();
                $cadena.= " (ID: " . $personaje->getId(). ")\n";
                $cadena.= "(Victorias / Perdidas): ".$personaje->getDuelosGanados()."\n";
                $cadena.= "Nivel: ". $personaje->getNivel()."\n";
                $i++;
            }
        }
        return $cadena;
    }
}
