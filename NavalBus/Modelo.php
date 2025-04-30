<?php
require_once 'Billete.php';
require_once 'Conductor.php';
require_once 'Linea.php';
require_once 'Servicio.php';

class Modelo
{
    private $cnx = null;

    function __construct()
    {
        try {
            $this->cnx = new PDO('mysql:host=localhost;port=3306;dbname=navalBus', 'root', 'root');
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Get the value of cnx
     */
    public function getCnx()
    {
        return $this->cnx;
    }

    /**
     * Set the value of cnx
     *
     * @return  self
     */
    public function setCnx($cnx)
    {
        $this->cnx = $cnx;

        return $this;
    }

    function obtenerLineas()
    {
        $resultado = array();
        try {
            $consulta = $this->cnx->query(
                'SELECT * from lineas'
            );

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
            $consulta = $this->cnx->prepare(
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
            $consulta = $this->cnx->prepare(
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
            $consulta = $this->cnx->prepare(
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
            $consulta = $this->cnx->prepare(
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
            $consulta = $this->cnx->prepare(
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
            $this->cnx->beginTransaction();
            $consulta = $this->cnx->prepare(
                'INSERT into billetes values (default,?,?,now(),?,?)'
            );
            $params = array($c->getId(), $l->getId(), $tipo, $precio);
            if ($consulta->execute($params) and $consulta->rowCount() == 1) {
                $consulta = $this->cnx->prepare(
                    'UPDATE servicios set recaudacion=recaudacion + ? where conductor = ? and linea = ? and finalizado = false'
                );
                $params = array($precio, $c->getId(), $l->getId());
                if ($consulta->execute($params) and $consulta->rowCount() == 1) {
                    $this->cnx->commit();
                    $resultado = true;
                }
            }
        } catch (PDOException $e) {
            $this->cnx->rollback();
            echo $e->getMessage();
        }
        return $resultado;
    }
    function obtenerPrecio($tipo)
    {
        $resultado = 0;
        try {
            $consulta = $this->cnx->prepare(
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
            $consulta = $this->cnx->prepare(
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
