<?php
include_once 'Arena.php';


class Duelo
{
    // ATRIBUTOS
    private int $id;
    private Personaje $personaje1;
    private Personaje $personaje2;
    private Arena $arena;
    private string $fecha;
    private string $estado;
    private ?Personaje $ganador = null;
    private float $poderPersonaje1 = 0;
    private float $poderPersonaje2 = 0;
    private float $danioAplicado = 0;

    // CONSTRUCT
    public function __construct(int $id, Personaje $personaje1, Personaje $personaje2, Arena $arena, string $fecha, string $estado)
    {
        $this->id = $id;
        $this->personaje1 = $personaje1;
        $this->personaje2 = $personaje2;
        $this->arena = $arena;
        $this->fecha = $fecha;
        $this->estado = $estado;
    }

    // SETTER
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setPersonaje1(Personaje $personaje1): void
    {
        $this->personaje1 = $personaje1;
    }
    public function setPersonaje2(Personaje $personaje2): void
    {
        $this->personaje2 = $personaje2;
    }
    public function setArena(Arena $arena): void
    {
        $this->arena = $arena;
    }
    public function setFecha(string $fecha): void
    {
        $this->fecha = $fecha;
    }
    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }
    public function setGanador(Personaje $ganador): void
    {
        $this->ganador = $ganador;
    }
    public function setPoderPersonaje1(float $poder): void
    {
        $this->poderPersonaje1 = $poder;
    }

    public function setPoderPersonaje2(float $poder): void
    {
        $this->poderPersonaje2 = $poder;
    }

    public function setDanioAplicado(float $danio): void
    {
        $this->danioAplicado = $danio;
    }

    // GETTER
    public function getId(): int
    {
        return $this->id;
    }
    public function getPersonaje1(): Personaje
    {
        return $this->personaje1;
    }
    public function getPersonaje2(): Personaje
    {
        return $this->personaje2;
    }
    public function getArena(): Arena
    {
        return $this->arena;
    }
    public function getFecha(): string
    {
        return $this->fecha;
    }
    public function getEstado(): string
    {
        return $this->estado;
    }
    public function getGanador(): ?Personaje
    {
        return $this->ganador;
    }

    public function getPoderPersonaje1(): float
    {
        return $this->poderPersonaje1;
    }

    public function getPoderPersonaje2(): float
    {
        return $this->poderPersonaje2;
    }

    public function getDanioAplicado(): float
    {
        return $this->danioAplicado;
    }

    // FUNCIONES
    public function puedeRealizarse(): bool
    {
        $respuesta = false;
        if ($this->getPersonaje1()->getId() !== $this->getPersonaje2()->getId()) {
            if ($this->getPersonaje1()->puedeDuelar() && $this->getPersonaje2()->puedeDuelar()) {
                $respuesta = true;
            }
        }
        return $respuesta;
    }

    public function realizarDuelo(): void
    {
        if ($this->puedeRealizarse()) {
            $p1PoderFinal = $this->getPersonaje1()->calcularPoderTotal() + $this->getArena()->calcularModificadorArena($this->getPersonaje1());
            $p2PoderFinal = $this->getPersonaje2()->calcularPoderTotal() + $this->getArena()->calcularModificadorArena($this->getPersonaje2());
            $this->setPoderPersonaje1($p1PoderFinal);
            $this->setPoderPersonaje2($p2PoderFinal);
            $ganador = ($p1PoderFinal > $p2PoderFinal) ? $this->getPersonaje1() : $this->getPersonaje2();
            $perdedor = ($p1PoderFinal > $p2PoderFinal) ? $this->getPersonaje2() : $this->getPersonaje1();

            $ganador->setNivel($ganador->getNivel() + 1);
            $ganador->recuperarEnergia(5);
            $ganador->setDuelosGanados($ganador->getDuelosGanados() + 1);

            $perdedor->setDuelosPerdidos($perdedor->getDuelosPerdidos() + 1);
            $perdedor->recuperarEnergia(-5);

            $danio = abs($p1PoderFinal - $p2PoderFinal);
            $this->setDanioAplicado($danio); //Se usa el valor absoluto para que el daño sea positivo, independientemente de quién gane. Ya que la función recibirDanio espera un valor positivo para reducir la vida del perdedor.
            $perdedor->recibirDanio($danio);

            $this->setGanador($ganador);

            $this->setEstado("realizado");
        } else {
            $this->setEstado("cancelado");
        }
    }

    public function obtenerGanador(): Personaje
    {
        return $this->getGanador();
    }

    public function resumenDuelo(): string
    {
        if ($this->getGanador() === $this->getPersonaje1()) {
            $ganador = $this->getGanador();
            $perdedor = $this->getPersonaje2();
        } else {
            $ganador = $this->getGanador();
            $perdedor = $this->getPersonaje1();
        }
        $cadena = "........................................................................\n";
        $cadena .= "Duelo: " . $this->getId() . "\n";
        $cadena .= $this->getPersonaje1()->getNombre() . " (ID: " . $this->getPersonaje1()->getId() . ") VS ";
        $cadena .= $this->getPersonaje2()->getNombre() . " (ID: " . $this->getPersonaje2()->getId() . ")\n";
        $cadena .= $this->getArena()->datosArena();
        $cadena .= "Estado del Duelo: " . $this->getEstado() . "\n\n";
        if ($this->getEstado() === "realizado") {
            $cadena .= "........................................................................\n";
            $cadena .= "Resultado del Duelo: \n\n";
            $cadena .= "Datos Ganador:\n";
            $cadena .= $ganador->datosPersonaje();
            $cadena .= "........................................................................\n";
            $cadena .= "Datos Perdedor:\n";
            $cadena .= $perdedor->datosPersonaje();
        } else {
            $cadena .= "El duelo no se pudo realizar.\n";
        }
        $cadena .= "........................................................................\n";
        return $cadena;

    }
}