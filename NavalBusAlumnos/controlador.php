<?php
require_once 'Modelo.php';

session_start();

$bd = new Modelo();

// Verifica si hay conexión con la base de datos.
if ($bd->getConexion() == null) {
    $mensaje = 'No hay conexión con la BD'; 
} else if ($bd->getConexion() == true) {
    $mensaje = '<p style="color: green;">Conexión Establecida</p>'; 
}

// Si se envió el formulario para iniciar servicio
if (isset($_POST['iniciar'])) {
    // Verifica si los campos necesarios están presentes y no vacíos
    if (!isset($_POST['linea']) || !isset($_POST['conductor']) || empty($_POST['conductor'])) {
        $mensaje = 'Datos incompletos para iniciar servicio';
    } else {
        // Intenta obtener la línea desde la base de datos
        $l = $bd->obtenerLinea($_POST['linea']);
        if ($l != null) {
            // Intenta obtener el conductor
            $c = $bd->obtenerConductor($_POST['conductor']);
            if ($c != null) {
                // Intenta crear un nuevo servicio con la línea y el conductor
                if ($bd->crearServicio($c, $l)) {
                    // Si tiene éxito, guarda los datos en la sesión y redirige al index
                    $_SESSION['conductor'] = $c;
                    $_SESSION['linea'] = $l;
                    header('location:index.php');
                } else {
                    $mensaje = 'Error, no se ha creado el servicio'; 
                }
            } else {
                $mensaje = 'El conductor no existe'; 
            }
        } else {
            $mensaje = 'La línea no existe'; 
        }
    }
}
// Si se envió el formulario para vender un billete
elseif (isset($_POST['vender'])) {
    // Verifica que exista una sesión activa con conductor y línea
    if (!isset($_SESSION['conductor']) || !isset($_SESSION['linea'])) {
        $mensaje = 'Sesión no iniciada para vender billete';
    } else {
        // Obtiene el tipo de billete (por defecto 'General')
        $tipo = $_POST['tipo'] ?? 'General';
        // Obtiene el precio correspondiente
        $precio = $bd->obtenerPrecio($tipo);
        // Intenta registrar la venta del billete
        if ($bd->venderBillete($_SESSION['conductor'], $_SESSION['linea'], $tipo, $precio)) {
            $mensaje = 'Billete vendido'; // Venta exitosa
        } else {
            $mensaje = 'Error al vender el billete'; // Falló la venta
        }
    }
}
// Si se envió el formulario para finalizar el servicio
elseif (isset($_POST['fin'])) {
    // Verifica que exista una sesión activa
    if (!isset($_SESSION['conductor']) || !isset($_SESSION['linea'])) {
        $mensaje = 'Sesión no iniciada para finalizar servicio';
    } else {
        // Intenta finalizar el servicio
        if ($bd->finalizarServicio($_SESSION['conductor'], $_SESSION['linea'])) {
            // Si tiene éxito, destruye la sesión y redirige al index
            session_destroy();
            header('Location: index.php');
            exit();
        } else {
            $mensaje = 'Error al finalizar el servicio'; // Falló al finalizar
        }
    }
}
