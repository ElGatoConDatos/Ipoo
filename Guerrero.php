<?php

require_once 'Personaje.php';

class Guerrero extends Personaje
{
    // ATRIBUTOS
    private float $fuerza;
    private float $armadura;

    // CONSTRUCT
    public function __construct(int $id, string $nombre, int $nivel, float $puntosVida, float $energia, int $duelosGanados, int $duelosPerdidos, string $estado, float $fuerza, float $armadura)
    {
        parent::__construct($id, $nombre, $nivel, $puntosVida, $energia, $duelosGanados, $duelosPerdidos, $estado);
        $this->fuerza = $fuerza;
        $this->armadura = $armadura;
    }
    // SETTER
    public function setFuerza(float $fuerza)
    {
        $this->fuerza = $fuerza;
    }
    public function setArmadura(float $armadura)
    {
        $this->armadura = $armadura;
    }
    // GETTER
    public function getFuerza()
    {
        return $this->fuerza;
    }
    public function getArmadura()
    {
        return $this->armadura;
    }

    // FUNCIONES
    public function calcularPoderBase()
    {
        return parent::getNivel() * 15;
    }

    public function calcularPoderEspecial()
    {
        return ($this->getFuerza() * 2) + $this->getArmadura();
    }

    public function getClase()
    {
        return "Guerrero";
    }

    public function datosPersonaje()
    {
        $cadena = "Nombre: " . parent::getNombre();
        $cadena .= " (ID: " . parent::getId() . ")\n";
        $cadena .= "Clase: Guerrero";
        $cadena .= " (LvL: " . parent::getNivel() . ")\n";
        $cadena .= "Fuerza: " . $this->getFuerza() . " Armadura: " . $this->getArmadura() . "\n";
        $cadena .= "Vida: " . parent::getPuntosVida();
        $cadena .= " (Estado: " . parent::getEstado() . ")\n";
        $cadena .= "Duelos Ganados / Perdidos: " . parent::getDuelosGanados();
        $cadena .= "/" . parent::getDuelosPerdidos() . "\n\n";
        $cadena .= "Datos del Arma: \n";
        if ($this->getArmaEquipada() !== null) {
            $cadena .= $this->getArmaEquipada()->datosArma() . "\n";
        } else {
            $cadena .= "Sin Arma\n";
        }
        return $cadena;
    }
}