<?php
require_once 'Modelo.php';

session_start();

$bd = new Modelo();

if ($bd->getConexion() == null) {
    $mensaje = 'No hay conexión con la BD';
}else if($bd->getConexion()==true){
    $mensaje = '<p style="color: green;">Conexión Establecida</p>';
}

if (isset($_POST['iniciar'])) {
    if (!isset($_POST['linea']) || !isset($_POST['conductor']) || empty($_POST['conductor'])) {
        $mensaje = 'Datos incompletos para iniciar servicio';
    } else {
        $l = $bd->obtenerLinea($_POST['linea']);
        if ($l != null) {
            $c = $bd->obtenerConductor($_POST['conductor']);
            if ($c != null) {
                if ($bd->crearServicio($c, $l)) {
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
} elseif (isset($_POST['vender'])) {
    if (!isset($_SESSION['conductor']) || !isset($_SESSION['linea'])) {
        $mensaje = 'Sesión no iniciada para vender billete';
    } else {
        $tipo = $_POST['tipo'] ?? 'General'; // Valor por defecto
        $precio = $bd->obtenerPrecio($tipo);
        if ($bd->venderBillete($_SESSION['conductor'], $_SESSION['linea'], $tipo, $precio)) {
            $mensaje = 'Billete vendido';
        } else {
            $mensaje = 'Error al vender el billete';
        }
    }
} elseif (isset($_POST['fin'])) {
    if (!isset($_SESSION['conductor']) || !isset($_SESSION['linea'])) {
        $mensaje = 'Sesión no iniciada para finalizar servicio';
    } else {
        if ($bd->finalizarServicio($_SESSION['conductor'], $_SESSION['linea'])) {
            session_destroy();
            header('Location: index.php');
            exit();
        } else {
            $mensaje = 'Error al finalizar el servicio';
        }
    }
}
