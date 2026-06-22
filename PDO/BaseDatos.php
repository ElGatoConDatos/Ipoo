<?php
class BaseDatos
{
    private PDO $conexion;
    public function __construct()
    {
        $this->conexion = new PDO('mysql:host=localhost;dbname=torneo_duelos', 'root', '');
    }
    public function getConexion(): PDO
    {
        return $this->conexion;
    }
}