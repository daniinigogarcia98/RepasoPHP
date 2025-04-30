<?php
require_once 'Modelo.php';

session_start();

$bd = new Modelo();

if ($bd->getConexion() == null) {
    $mensaje = 'No hay conexión con la BD';
    
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
}
elseif (isset($_POST['vender'])) {
    $precio=$bd->obtenerPrecio($_POST['tipo']);
    if($bd->venderBillete($_SESSION['conductor'],$_SESSION['linea'],$_POST['tipo'],$precio)){
        $mensaje='Billete vendido';
    }
    else{
        $mensaje='Error al vender el billete';
    }
}
elseif (isset($_POST['fin'])) {
    if($bd->finalizarServicio($_SESSION['conductor'],$_SESSION['linea'])){
        session_destroy();
        header('location:index.php');
    }
    else{
        $mensaje='Error al vender el billete';
    }
}