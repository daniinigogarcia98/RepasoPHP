<?php

require_once 'controlador.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>NavalBus</title>
</head>

<body>
    <h1>NavalBus</h1>

    <!-- Sección de Mensajes -->
    <section>
        <h3 style="color:red">Mensaje</h3>
        <h3 style="color:red"><?php echo (isset($mensaje) ? $mensaje : '') ?></h3>
    </section>

    <!-- Sección de Inicio de Servicio -->
    <?php
    if (!isset($_SESSION['conductor'])) {
    ?>

        <h2>Iniciar Servicio</h2>
        <form action="" method="POST">
            <label for="linea">Línea</label>
            <select name="linea">
			<?php
                    $lineas = $bd->obtenerLineas();
                    foreach($lineas as $l){
                        echo '<option value="'.$l->getId().'">'.$l->getNombre().'</option>';
                    }
                ?>
            </select>
            <label for="conductor">Conductor</label>
            <input type="text" name="conductor" placeholder="Id Conductor" />
            <button type="submit" name="iniciar">Iniciar Servicio</button>
        </form>

    <?php
    } else {
    ?>
        <h2>Conductor:<?php echo $_SESSION['conductor']->getNombreApe().' - '.$_SESSION['linea']->getNombre().'('.$_SESSION['linea']->getOrigen().'-'.$_SESSION['linea']->getDestino().')'?></h2>

        <form method="post">
            <h4 style="color:blue">Tipo de Billete</h3>
                <select name="tipo">
                    <option selected="selected">General</option>
                    <option>Reducido</option>
                </select>
                <button type="submit" name="vender">Vender</button>
                <button type="submit" name="fin">Finalizar Servicio</button>
                <h3 style="color:blue">Recaudado:<?php echo $bd->obtenerRecaudado($_SESSION['conductor'],$_SESSION['linea'])?></h3>
                <table width="100%">
                    <tr>
                        <td>
                            <h3 style="color:blue">Fecha</h3>
                        </td>
                        <td>
                            <h3 style="color:blue">Línea</h3>
                        </td>
                        <td>
                            <h3 style="color:blue">Tipo Billete</h3>
                        </td>
                        <td>
                            <h3 style="color:blue">Precio</h3>
                        </td>
                    </tr>
                    <?php 
                    $billetes = $bd->obtenerBilletes($_SESSION['conductor']);
                    foreach($billetes as $b){
                        echo '<tr>';
                        echo '<td>'.$b->getFecha().'</td>';
                        echo '<td>'.$b->getLinea()->getNombre().'</td>';
                        echo '<td>'.$b->getTipo().'</td>';
                        echo '<td>'.$b->getPrecio().'</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
        </form>
    <?php
    }
    ?>
</body>

</html>