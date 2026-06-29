<?php

require_once 'Personaje.php';

class Mago extends Personaje
{
    // ATRIBUTOS
    private float $mana;
    private float $inteligencia;

    // CONSTRUCT
    public function __construct(int $id, string $nombre, int $nivel, float $puntosVida, float $energia, int $duelosGanados, int $duelosPerdidos, string $estado, float $mana, float $inteligencia)
    {
        parent::__construct($id, $nombre, $nivel, $puntosVida, $energia, $duelosGanados, $duelosPerdidos, $estado);
        $this->mana = $mana;
        $this->inteligencia = $inteligencia;
    }

    //SETTER
    public function setMana(float $mana): void
    {
        $this->mana = $mana;
    }
    public function setInteligencia(float $inteligencia): void
    {
        $this->inteligencia = $inteligencia;
    }

    //GETTER
    public function getMana(): float
    {
        return $this->mana;
    }
    public function getInteligencia(): float
    {
        return $this->inteligencia;
    }

    // FUNCIONES
    public function calcularPoderBase(): float
    {
        return (parent::getNivel() * 10) + $this->getMana();
    }

    public function calcularPoderEspecial(): float
    {
        return $this->getMana() + ($this->getInteligencia() * 3);
    }

    public function getClase()
    {
        return "Mago";
    }

    public function datosPersonaje()
    {
        $cadena = "Nombre: " . parent::getNombre();
        $cadena .= " (ID: " . parent::getId() . ")\n";
        $cadena .= "Clase: Mago";
        $cadena .= " (LvL: " . parent::getNivel() . ")\n";
        $cadena .= "Mana: " . $this->getMana() . " Inteligencia: " . $this->getInteligencia() . "\n";
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
