<?php

class Linea {
    private $id;
    private $nombre;
    private $origen;
    private $destino;

    public function __construct($id = null, $nombre = null, $origen = null, $destino = null) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->origen = $origen;
        $this->destino = $destino;
    }

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getOrigen() {
        return $this->origen;
    }

    public function getDestino() {
        return $this->destino;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setOrigen($origen) {
        $this->origen = $origen;
    }

    public function setDestino($destino) {
        $this->destino = $destino;
    }
}
?>
