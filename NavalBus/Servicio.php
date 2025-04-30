<?php
class Servicio {
    private $id;
    private $linea;
    private $conductor;
    private $recaudacion;
    private $finalizado;
    private $fechaInicio;

    public function __construct($id = null, $linea = null, $conductor = null, $recaudacion = null, $finalizado = null, $fechaInicio = null) {
        $this->id = $id;
        $this->linea = $linea;
        $this->conductor = $conductor;
        $this->recaudacion = $recaudacion;
        $this->finalizado = $finalizado;
        $this->fechaInicio = $fechaInicio;
    }

    public function getId() {
        return $this->id;
    }

    public function getLinea() {
        return $this->linea;
    }

    public function getConductor() {
        return $this->conductor;
    }

    public function getRecaudacion() {
        return $this->recaudacion;
    }

    public function getFinalizado() {
        return $this->finalizado;
    }

    public function getFechaInicio() {
        return $this->fechaInicio;
    }

    public function setLinea($linea) {
        $this->linea = $linea;
    }

    public function setConductor($conductor) {
        $this->conductor = $conductor;
    }

    public function setRecaudacion($recaudacion) {
        $this->recaudacion = $recaudacion;
    }

    public function setFinalizado($finalizado) {
        $this->finalizado = $finalizado;
    }

    public function setFechaInicio($fechaInicio) {
        $this->fechaInicio = $fechaInicio;
    }
}
?>