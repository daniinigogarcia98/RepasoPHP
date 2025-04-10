<?php 
$mensaje='';
class Modelo{
    private $conexion;

    function __construct()
    {
        try {
            $this->conexion = new PDO('mysql:host=localhost;port=3306;dbname=reservas','root','root');
        } catch (\Throwable $th) {
            //return $th->getMessage();
            global $mensaje;
            $mensaje = $th->getMessage();
            
        }
    }
    function obtenerUs($us, $ps)
    {
        $respuesta=null;
        try {
            //code...
        } catch (\Throwable $th) {
            global $mensaje;
            $mensaje = $th->getMessage();
        }
        return $respuesta;
    }
    /**
     * Get the value of conexion
     */ 
    public function getConexion()
    {
        return $this->conexion;
    }

    /**
     * Set the value of conexion
     *
     * @return  self
     */ 
    public function setConexion($conexion)
    {
        $this->conexion = $conexion;

        return $this;
    }
}
?>