<?php 
require_once 'Modelo.php';
session_start();
$bd = new Modelo();

//ver si se a pulsado en acceder
if (isset($_POST['acceder'])) {
    //Chequear si se ha rellenado us y ps
    if(empty($_POST['usuario']) ||empty($_POST['ps'])){
        $mensaje='El usuario y la Contraseña Son Obligatorias';
    }else {
        $us=$bd->obtenerUs($_POST['usuario'],$_POST['ps']);
        //comprobar si el usuario existe
        if ($us !=null) {
            //guardar el usuario en la sesión
            $_SESSION['usuario'] = $us;
            header('location:index.php');
        }
    }
}
?>