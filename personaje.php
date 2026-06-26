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