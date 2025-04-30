<?php
class Billete {
    private $id;
    private $conductor;
    private $linea;
    private $fecha;
    private $tipo;
    private $precio;

    public function __construct($id = null, $conductor = null, $linea = null, $fecha = null, $tipo=null, $precio = null) {
        $this->id = $id;
        $this->conductor = $conductor;
        $this->linea = $linea;
        $this->fecha = $fecha;
        $this->tipo = $tipo;
        $this->precio = $precio;
    }

    public function getId() {
        return $this->id;
    }

    public function getConductor() {
        return $this->conductor;
    }

    public function getLinea() {
        return $this->linea;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function setConductor($conductor) {
        $this->conductor = $conductor;
    }

    public function setLinea($linea) {
        $this->linea = $linea;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    /**
     * Get the value of tipo
     */ 
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     *
     * @return  self
     */ 
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }
}

?>