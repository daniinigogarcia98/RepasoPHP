<?php
require_once 'Modelo.php';
session_start();
$bd = new Modelo();

//ver si se a pulsado en acceder 
if (isset($_POST['acceder'])) {
    //Chequear si se ha rellenado usuario y contraseña
    if (empty($_POST['usuario']) || empty($_POST['ps'])) {
        $mensaje = 'Usuario y contraseña Obligatorios';
    } else {
        $us = $bd->obtenerUsuario($_POST['usuario'], $_POST['ps']);
        //Comprobar si el usuario existe y está activo
        if ($us != null and $us->getActivo() == true) {
            //guardar usuario en la sesion
            $_SESSION['usuario'] = $us;
            header('location:index.php');
        } else {
            $mensaje = 'Usuario y Contraseña incorrectos';
        }
    }
} elseif (isset($_POST['salir'])) {
    session_destroy();
    setcookie('color','',time()-1);
    header('location:index.php');
} elseif (isset($_POST['cambiarColor'])) {
    setcookie('color', $_POST['color']);
    header('location:index.php');
}elseif (isset($_POST['verR'])) {
    $reservas=$bd->obtenerReservas($_POST['recurso']);
    
}elseif (isset($_POST['reservar'])) {
  if (empty($_POST['fecha'])|| empty($_POST['recurso'])||empty($_POST['hora'])) {
    $mensaje='Debe estar rellenos los campos';
  }else {
    
    if ($bd->verificarDisponibilidad($_POST['fecha'],$_POST['recurso'],$_POST['hora'])) {
        //Crear Reserva
        //false->es lo mismo que 0
        $r=new Reservas(null,$_SESSION['usuario']->getIdRayuela(),$_POST['recurso'],$_POST['fecha'],$_POST['hora'],false);
        if ($bd->guardarReserva($r)) {
            $mensaje='Reservado Correctamente';
            //Actualizar usuario en la Sesión
            $_SESSION['usuario']=$bd->infoUsuario($_SESSION['usuario']->getIdRayuela());
        }else{
            $mensaje='Error no se ha podido Reservar';
        }
       
    }else{
        $mensaje='Error No se Puede Reservar';
    }
  }
}elseif(isset($_POST['anular'])){
    if (empty($_POST['fecha'])|| empty($_POST['recurso'])||empty($_POST['hora'])) {
        $mensaje='Debe estar rellenos los campos';
      }else {
        if ($bd->anularReserva($_SESSION['usuario']->getIdRayuela(),$_POST['recurso'],$_POST['fecha'],$_POST['hora'])) {
                $mensaje='Reservado Correctamente';
                //Actualizar usuario en la Sesión
                $_SESSION['usuario']=$bd->infoUsuario($_SESSION['usuario']->getIdRayuela());
            }else{
                $mensaje='Error no se ha podido Anular';
            }
        }
      
}