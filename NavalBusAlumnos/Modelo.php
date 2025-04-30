<?php
require_once 'Billete.php';
require_once 'Conductor.php';
require_once 'Linea.php';
require_once 'Servicio.php';

class Modelo
{
    private $conexion = null;

    function __construct()
    {
        try {
            $this->conexion = new PDO('mysql:host=localhost;port=3306;dbname=navalBus', 'root', 'root');
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
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

    function obtenerLineas()
    {
        $resultado = array();
        try {
            $consulta = $this->conexion->prepare('SELECT * from lineas');
            if ($consulta->execute()) {
                while ($fila = $consulta->fetch()) {
                    $resultado[] = new Linea(
                        $fila['id'],
                        $fila['nombre'],
                        $fila['origen'],
                        $fila['destino']
                    );
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $resultado;
    }
    function obtenerLinea($codigo)
    {
        $resultado = null;
        try {
            $consulta = $this->conexion->prepare(
                'select * from lineas where id = ?'
            );
            $params = array($codigo);
            if ($consulta->execute($params)) {
                if ($fila = $consulta->fetch()) {
                    $resultado = new Linea(
                        $fila['id'],
                        $fila['nombre'],
                        $fila['origen'],
                        $fila['destino']
                    );
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $resultado;
    }
    function obtenerConductor($codigo)
    {
        $resultado = null;
        try {
            $consulta = $this->conexion->prepare(
                'select * from conductores where id = ?'
            );
            $params = array($codigo);
            if ($consulta->execute($params)) {
                if ($fila = $consulta->fetch()) {
                    $resultado = new Conductor(
                        $fila['id'],
                        $fila['nombreApe'],
                        $fila['telefono'],
                        $fila['fechaContrato']
                    );
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $resultado;
    }
    function crearServicio($c, $l)
    {
        $resultado = false;
        try {
            $consulta = $this->conexion->prepare(
                'INSERT into servicios values (default,now(),?,?,0,false)'
            );
            $params = array($l->getId(), $c->getId());
            if ($consulta->execute($params) and $consulta->rowCount() == 1) {
                $resultado = true;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $resultado;
    }
    function obtenerRecaudado($c, $l)
    {
        $resultado = 0;
        try {
            $consulta = $this->conexion->prepare(
                'SELECT recaudacion from servicios where conductor = ? and linea = ? and finalizado = false'
            );
            $params = array($c->getId(), $l->getId());
            if ($consulta->execute($params)) {
                if ($fila = $consulta->fetch()) {
                    $resultado = $fila[0];
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $resultado;
    }
    function obtenerBilletes($c)
    {
        $resultado = array();
        try {
            $consulta = $this->conexion->prepare(
                'select * from billetes inner join lineas on linea = lineas.id where conductor = ?'
            );
            $params = array($c->getId());
            if ($consulta->execute($params)) {
                while ($fila = $consulta->fetch()) {
                    $resultado[] = new Billete(
                        $fila['id'],
                        $fila['conductor'],
                        new Linea($fila['linea'], $fila['nombre'], $fila['origen'], $fila['destino']),
                        $fila['fecha'],
                        $fila['tipo'],
                        $fila['precio']
                    );
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $resultado;
    }
    function venderBillete($c, $l, $tipo, $precio)
    {
        $resultado = false;
        try {
            $this->conexion->beginTransaction();
            $consulta = $this->conexion->prepare(
                'INSERT into billetes values (default,?,?,now(),?,?)'
            );
            $params = array($c->getId(), $l->getId(), $tipo, $precio);
            if ($consulta->execute($params) and $consulta->rowCount() == 1) {
                $consulta = $this->conexion->prepare(
                    'UPDATE servicios set recaudacion=recaudacion + ? where conductor = ? and linea = ? and finalizado = false'
                );
                $params = array($precio, $c->getId(), $l->getId());
                if ($consulta->execute($params) and $consulta->rowCount() == 1) {
                    $this->conexion->commit();
                    $resultado = true;
                }
            }
        } catch (PDOException $e) {
            $this->conexion->rollback();
            echo $e->getMessage();
        }
        return $resultado;
    }
    function obtenerPrecio($tipo)
    {
        $resultado = 0;
        try {
            $consulta = $this->conexion->prepare(
                'SELECT ObtenerPrecioActual(?)'
            );
            $params = array($tipo);
            if ($consulta->execute($params)) {
                if ($fila = $consulta->fetch()) {
                    $resultado = $fila[0];
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $resultado;
    }
    function finalizarServicio($c, $l)
    {
        $resultado = false;
        try {
            $consulta = $this->conexion->prepare(
                'UPDATE servicios set finalizado=true where conductor = ? and linea = ? and finalizado = false'
            );
            $params = array($c->getId(), $l->getId());
            if ($consulta->execute($params) and $consulta->rowCount() == 1) {
                $resultado = true;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $resultado;
    }
}