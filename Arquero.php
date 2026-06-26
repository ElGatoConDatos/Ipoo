<?php

require_once 'Personaje.php';

class Arquero extends Personaje
{
    // ATRIBUTOS
    private float $precision;
    private float $velocidad;

    // CONSTRUCT
    public function __construct(int $id, string $nombre, int $nivel, float $puntosVida, float $energia, int $duelosGanados, int $duelosPerdidos, string $estado, float $precision, float $velocidad)
    {
        parent::__construct($id, $nombre, $nivel, $puntosVida, $energia, $duelosGanados, $duelosPerdidos, $estado);
        $this->precision = $precision;
        $this->velocidad = $velocidad;
    }

    //SETTER
    public function setPrecision(float $precision): void
    {
        $this->precision = $precision;
    }
    public function setVelocidad(float $velocidad): void
    {
        $this->velocidad = $velocidad;
    }

    //GETTER
    public function getPrecision(): float
    {
        return $this->precision;
    }
    public function getVelocidad(): float
    {
        return $this->velocidad;
    }

    // FUNCIONES
    public function calcularPoderBase()
    {
        return (parent::getNivel() * 12) + $this->getPrecision();
    }

    public function calcularPoderEspecial()
    {
        return ($this->getPrecision() * 2) + $this->getVelocidad();
    }

    public function getClase()
    {
        return "Arquero";
    }

    public function datosPersonaje()
    {
        $cadena = "Nombre: " . parent::getNombre();
        $cadena .= " (ID: " . parent::getId() . ")\n";
        $cadena .= "Clase: Arquero";
        $cadena .= " (LvL: " . parent::getNivel() . ")\n";
        $cadena .= "Precision: " . $this->getPrecision() . " Velocidad: " . $this->getVelocidad() . "\n";
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