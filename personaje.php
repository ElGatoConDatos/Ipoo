<?php



abstract class Personaje
{
    // ATRIBUTOS
    private int $id;
    private string $nombre;
    private int $nivel;
    private float $puntosVida;
    private float $energia;
    private int $duelosGanados;
    private int $duelosPerdidos;
    private string $estado;
    private ?Arma $armaEquipada = null;


    // CONSTRUCT
    public function __construct(int $id, string $nombre, int $nivel, float $puntosVida, float $energia, int $duelosGanados, int $duelosPerdidos, string $estado)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->nivel = $nivel;
        $this->puntosVida = $puntosVida;
        $this->energia = $energia;
        $this->duelosGanados = $duelosGanados;
        $this->duelosPerdidos = $duelosPerdidos;
        $this->estado = $estado;
    }

    // SETTER
    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function setNombre(string $nombre)
    {
        $this->nombre = $nombre;
    }
    public function setNivel(int $nivel)
    {
        $this->nivel = $nivel;
    }
    public function setPuntosVida(float $puntosVida)
    {
        $this->puntosVida = $puntosVida;
    }
    public function setEnergia(float $energia)
    {
        $this->energia = $energia;
    }
    public function setDuelosGanados(int $duelosGanados)
    {
        $this->duelosGanados = $duelosGanados;
    }
    public function setDuelosPerdidos(int $duelosPerdidos)
    {
        $this->duelosPerdidos = $duelosPerdidos;
    }
    public function setEstado(string $estado)
    {
        $this->estado = $estado;
    }
    public function setArmaEquipada(Arma $armaEquipada)
    {
        $this->armaEquipada = $armaEquipada;
    }

    // GETTER
    public function getId()
    {
        return $this->id;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getNivel()
    {
        return $this->nivel;
    }
    public function getPuntosVida()
    {
        return $this->puntosVida;
    }
    public function getEnergia()
    {
        return $this->energia;
    }
    public function getDuelosGanados()
    {
        return $this->duelosGanados;
    }
    public function getDuelosPerdidos()
    {
        return $this->duelosPerdidos;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function getArmaEquipada(): ?Arma
    {
        return $this->armaEquipada;
    }

    // RESTO DE FUNCIONES
    public function recibirDanio(float $cantidad)
    {
        $danioAVida = $this->getPuntosVida() - $cantidad;
        if ($danioAVida <= 0) {
            $this->setPuntosVida(0);
            $this->setEstado("retirado");
        } elseif ($danioAVida > 0 && $danioAVida < 30) {
            $this->setPuntosVida($danioAVida);
            $this->setEstado("lesionado");
        } else {
            $this->setPuntosVida($danioAVida);
        }
    }

    public function recuperarVida(float $cantidad)
    {
        $this->setPuntosVida($this->getPuntosVida() + $cantidad);
    }

    public function recuperarEnergia(float $cantidad)
    {
        $this->setEnergia($this->getEnergia() + $cantidad);
    }

    public function puedeDuelar(): bool
    {
        return ($this->getEstado() === "disponible" && $this->getPuntosVida() > 0);
    }

    public function calcularPoderTotal(): float
    {
        $total = $this->calcularPoderBase() + $this->calcularPoderEspecial();

        if ($this->armaEquipada !== null) {
            $total += $this->armaEquipada->calcularDanio();
        }

        return $total;
    }

    // FUNCIONES ABSTRACTAS
    abstract public function calcularPoderBase();

    abstract public function calcularPoderEspecial();

    abstract public function datosPersonaje();

    abstract public function getClase();

}

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

