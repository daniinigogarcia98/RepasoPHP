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
        // Inicializa un array vacío para almacenar los objetos Linea
        $resultado = array();
        try {
              // Prepara la consulta SQL para obtener todas las filas de la tabla 'Lineas'
            $consulta = $this->conexion->query('SELECT * from Lineas');
          
                  // Recorre todas las filas de la consulta
                while ($fila = $consulta->fetch()) {
                      // Crea un nuevo objeto Linea con los valores obtenidos de la fila
                    $resultado[] = new Linea(
                        $fila['id'],
                        $fila['nombre'],
                        $fila['origen'],
                        $fila['destino']
                    );
                }
            
        } catch (PDOException $e) {
             // Si ocurre un error en la consulta o la conexión, se captura la excepción y se muestra el mensaje de error
            echo $e->getMessage();
        }
          // Devuelve el array con los objetos Linea creados
        return $resultado;
    }
    function obtenerLinea($codigo)
    {
        // Inicializamos la variable $resultado con null, que será el valor por defecto en caso de no encontrar resultados.
        $resultado = null;
        try {
             // Preparamos la consulta SQL para seleccionar todas las columnas de la tabla 'Lineas' donde el 'id' sea igual al parámetro proporcionado.
            $consulta = $this->conexion->prepare(
                'select * from Lineas where id = ?'
            );
             // Asignamos el valor del parámetro $codigo al array $params, que se usará para reemplazar el marcador de posición (?) en la consulta SQL.
            $params = array($codigo);
            // Ejecutamos la consulta SQL con los parámetros proporcionados.
            if ($consulta->execute($params)) {
                // Si la consulta se ejecuta correctamente, intentamos obtener una fila de resultados.
                if ($fila = $consulta->fetch()) {
                    // Si encontramos una fila, creamos un objeto de la clase 'Linea' utilizando los datos de la fila.
                // El objeto 'Linea' se crea con los valores de las columnas 'id', 'nombre', 'origen' y 'destino'.
                    $resultado = new Linea(
                        $fila['id'],
                        $fila['nombre'],
                        $fila['origen'],
                        $fila['destino']
                    );
                }
            }
        } catch (PDOException $e) {
              // Si ocurre un error en la ejecución de la consulta (por ejemplo, un error de conexión),
        // se captura la excepción y se muestra el mensaje de error.
            echo $e->getMessage();
        }
         // Retornamos el resultado. Si no se encontró ninguna línea o hubo un error, devolverá null.
        return $resultado;
    }
    function obtenerConductor($codigo)
    {
        $resultado = null;
        try {
            $consulta = $this->conexion->prepare('select * from Conductores where id = ?');
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
                'INSERT into Servicios values (default,now(),?,?,0,false)'
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
                'SELECT recaudacion from Servicios where conductor = ? and linea = ? and finalizado = false'
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
                'select * from Billetes inner join Lineas on linea = Lineas.id where conductor = ?'
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
                'INSERT into Billetes values (default,?,?,now(),?,?)'
            );
            $params = array($c->getId(), $l->getId(), $tipo, $precio);
            if ($consulta->execute($params) and $consulta->rowCount() == 1) {
                $consulta = $this->conexion->prepare(
                    'UPDATE Servicios set recaudacion=recaudacion + ? where conductor = ? and linea = ? and finalizado = false'
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
                'UPDATE Servicios set finalizado=true where conductor = ? and linea = ? and finalizado = false'
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