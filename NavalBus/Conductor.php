<?php
class Conductor {
    private $id;
    private $nombreApe;
    private $telefono;
    private $fechaContrato;

    public function __construct($id = null, $nombreApe = null, $telefono = null, $fechaContrato = null) {
        $this->id = $id;
        $this->nombreApe = $nombreApe;
        $this->telefono = $telefono;
        $this->fechaContrato = $fechaContrato;
    }

    public function getId() {
        return $this->id;
    }

    public function getNombreApe() {
        return $this->nombreApe;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getFechaContrato() {
        return $this->fechaContrato;
    }

    public function setNombreApe($nombreApe) {
        $this->nombreApe = $nombreApe;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setFechaContrato($fechaContrato) {
        $this->fechaContrato = $fechaContrato;
    }
}
?>
