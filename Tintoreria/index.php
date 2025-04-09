<?php
function selecionarSelect($prendas, $selecionado)
{
    if (isset($_POST['prendas'])) {
        if ($_POST['prendas'] == $prendas) {
            echo 'selected="selected"';
        }
    } elseif ($selecionado) {
        echo 'selected="selected"';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tintoreria</title>
</head>

<body>
    <style>
        fieldset {
            width: 300px;
            height: 250px;
        }
    </style>
    <div class="container">
        <h1>Tintorería La Morala</h1>
        <form action="" method="post">
            <fieldset>
                <legend>Registrar Trabajo</legend>
                <div>
                    <label for="fechaE">Fecha de entrada</label><br>
                    <input type="date" name="fechaE" id="fechaE" value="<?php echo (!empty($_POST['fechaE']) ? $_POST['fechaE'] : date('Y-m-d')) ?>" />
                </div>
                <div>
                    <label for="cliente">Cliente</label><br>
                    <input type="text" name="cliente" id="cliente" placeholder="Introduce nombre" value="<?php echo (isset($_POST['cliente'])?$_POST['cliente']:'')?>"/>
                </div>
                <div>
                    <label for="prendas">Tipo de Prenda</label><br>
                    <select name="prendas" id="prendas">
                        <option <?php echo (isset($_POST['prendas']) && $_POST['prendas'] == 'Fiesta' ? 'selected="selected"' : '')?> <?php selecionarSelect('Fiesta',false)?>>Fiesta</option>
                        <option <?php echo (isset($_POST['prendas']) && $_POST['prendas'] == 'Cuero' ? 'selected="selected"' : '')?> <?php selecionarSelect('Cuero',false)?>>Cuero</option>
                        <option <?php echo (isset($_POST['prendas']) && $_POST['prendas'] == 'Hogar' ? 'selected="selected"' : '')?> <?php selecionarSelect('Hogar',false)?>>Hogar</option>
                        <option <?php echo ((!isset($_POST['prendas'])) || (isset($_POST['prendas']) && $_POST['prendas'] == 'Textil') ? 'selected="selected"' : '')?> <?php selecionarSelect('Textil',true)?>>Textil</option>
                    </select>
                </div>
                <div>
                    <label>Servicios</label><br />
                    <input type="checkbox" id="limpieza" name="servicios[]" value="limpieza" />
                    <label for="limpieza">Limpieza</label>
                    <input type="checkbox" id="planchado" name="servicios[]" value="planchado" />
                    <label for="planchado">Planchado</label>
                    <input type="checkbox" id="desmanchado" name="servicios[]" value="desmanchado" />
                    <label for="desmanchado">Desmanchado</label>
                </div>
                <div>
                    <label for="importe">Importe</label><br>
                    <input type="number" name="importe" id="importe" value="1">
                </div>
                <br>
                <div>
                    <button type="submit" name="guardar" id="guardar">Guardar</button>
                </div>

            </fieldset>
        </form>
    </div>
    <?php

    if (isset($_POST['guardar'])) {
        if (empty($_POST['fechaE']) or empty($_POST['cliente']) or empty($_POST['prendas']) or empty($_POST['importe'])) {
            echo '<h2 style="color: red;">Los campos Fecha,Cliente,Tipo Prenda e importe no Pueden estar Vacios</h2>';
        } elseif (!isset($_POST['servicios'])) {
            echo '<h2 style="color: red;">Selecciona al menos un Servicio</h2>';
        } elseif (
            isset($_POST["prendas"]) && $_POST['prendas'] == "Cuero" && isset($_POST['servicios']) && in_array('planchado', $_POST['servicios'])
        ) {
            echo '<h2 style="color: red;">La Prenda de Cuero Y El Servicio de Planchado No se Pueden Marcar Juntos</h2>';
        } elseif (isset($_POST['prendas']) && $_POST['prendas'] == 'Fiesta' && isset($_POST['importe']) && $_POST['importe'] <= 50) {
            echo '<h2 style="color:red;">El importe Minimo para Prendas de Fiesta es Minimo es de 50</h2>';
        } else {
            echo '<h1 style="color:green;">Datos Guardados</h1>';
            echo '<h2>Cliente:' . $_POST['cliente'] . '</h2>';
            echo '<h2>Prenda:' . $_POST['prendas'] . '</h2>';
            echo '<h2>Servicios:' . implode('/', $_POST['servicios']) . '</h2>';
            echo '<h2>Importe:' . $_POST['importe'] . '€</h2>';
        }
    }
    ?>
</body>

</html>