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
    /**
     * Obtiene un objeto Conductor desde la base de datos, a partir de su ID.
     *
     * @param int $codigo El ID del conductor a buscar en la base de datos.
     * @return Conductor|null Retorna una instancia de la clase Conductor si se encuentra, o null si no.
     */
    function obtenerConductor($codigo)
    {
        // Se inicializa la variable que almacenará el resultado.
        $resultado = null;
        try {
             // Se prepara la consulta SQL para buscar un conductor con el ID especificado.
            $consulta = $this->conexion->prepare('select * from Conductores where id = ?');
            // Se define el parámetro para la consulta (el ID del conductor).
            $params = array($codigo);
              // Se ejecuta la consulta con el parámetro.
            if ($consulta->execute($params)) {
                   // Si se encuentra una fila (es decir, el conductor existe):
                if ($fila = $consulta->fetch()) {
                    // Se crea un nuevo objeto Conductor con los datos obtenidos de la base de datos.
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
   // Función que obtiene la recaudación actual de un servicio no finalizado para un conductor y línea específicos
function obtenerRecaudado($c, $l)
{
    $resultado = 0;
    try {
        // Prepara la consulta para obtener la recaudación de un servicio no finalizado
        $consulta = $this->conexion->prepare(
            'SELECT recaudacion from Servicios where conductor = ? and linea = ? and finalizado = false'
        );
        $params = array($c->getId(), $l->getId()); // Parámetros: id del conductor y de la línea
        if ($consulta->execute($params)) {
            // Si hay resultados, obtiene el valor de recaudación
            if ($fila = $consulta->fetch()) {
                $resultado = $fila[0]; // Se asigna el valor de recaudación
            }
        }
    } catch (PDOException $e) {
        echo $e->getMessage(); // Muestra el mensaje de error en caso de excepción
    }
    return $resultado; // Devuelve la recaudación obtenida
}

// Función que devuelve todos los billetes vendidos por un conductor
function obtenerBilletes($c)
{
    $resultado = array(); // Array para almacenar los objetos Billete
    try {
        // Consulta que une las tablas Billetes y Lineas para obtener información completa
        $consulta = $this->conexion->prepare(
            'select * from Billetes inner join Lineas on linea = Lineas.id where conductor = ?'
        );
        $params = array($c->getId()); // Parámetro: id del conductor
        if ($consulta->execute($params)) {
            // Recorre todos los registros obtenidos
            while ($fila = $consulta->fetch()) {
                // Crea un objeto Billete y lo añade al resultado
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
        echo $e->getMessage(); // Muestra el mensaje de error si ocurre una excepción
    }
    return $resultado; // Devuelve el array de billetes
}

// Función que registra la venta de un billete y actualiza la recaudación del servicio activo
function venderBillete($c, $l, $tipo, $precio)
{
    $resultado = false;
    try {
        // Inicia una transacción para asegurar la integridad de la operación
        $this->conexion->beginTransaction();

        // Inserta un nuevo billete en la base de datos
        $consulta = $this->conexion->prepare(
            'INSERT into Billetes values (default,?,?,now(),?,?)'
        );
        $params = array($c->getId(), $l->getId(), $tipo, $precio); // Parámetros del billete
        if ($consulta->execute($params) and $consulta->rowCount() == 1) {
            // Si la inserción fue exitosa, actualiza la recaudación del servicio correspondiente
            $consulta = $this->conexion->prepare(
                'UPDATE Servicios set recaudacion=recaudacion + ? where conductor = ? and linea = ? and finalizado = false'
            );
            $params = array($precio, $c->getId(), $l->getId());
            if ($consulta->execute($params) and $consulta->rowCount() == 1) {
                // Si la actualización también fue exitosa, confirma la transacción
                $this->conexion->commit();
                $resultado = true;
            }
        }
    } catch (PDOException $e) {
        // En caso de error, revierte la transacción
        $this->conexion->rollback();
        echo $e->getMessage(); // Muestra el mensaje de error
    }
    return $resultado; // Devuelve true si todo fue exitoso, false si no
}

// Función que obtiene el precio actual de un tipo de billete utilizando una función almacenada
function obtenerPrecio($tipo)
{
    $resultado = 0;
    try {
        // Llama a una función SQL para obtener el precio actual de un tipo de billete
        $consulta = $this->conexion->prepare(
            'SELECT ObtenerPrecioActual(?)'
        );
        $params = array($tipo);
        if ($consulta->execute($params)) {
            // Si hay resultado, lo obtiene
            if ($fila = $consulta->fetch()) {
                $resultado = $fila[0];
            }
        }
    } catch (PDOException $e) {
        echo $e->getMessage(); // Muestra el mensaje de error
    }
    return $resultado; // Devuelve el precio obtenido
}

// Función que finaliza un servicio activo para un conductor y línea determinados
function finalizarServicio($c, $l)
{
    $resultado = false;
    try {
        // Actualiza el servicio marcándolo como finalizado
        $consulta = $this->conexion->prepare(
            'UPDATE Servicios set finalizado=true where conductor = ? and linea = ? and finalizado = false'
        );
        $params = array($c->getId(), $l->getId());
        if ($consulta->execute($params) and $consulta->rowCount() == 1) {
            $resultado = true; // Marca como exitoso si se actualizó una fila
        }
    } catch (PDOException $e) {
        echo $e->getMessage(); // Muestra el error en caso de fallo
    }
    return $resultado; // Devuelve true si se finalizó el servicio
}

}
