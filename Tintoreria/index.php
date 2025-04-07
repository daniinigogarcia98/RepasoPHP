<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Tintoreria</title>
</head>
<body>
    <h1>Tintorería La Morala</h1>
    <fieldset>
        <legend>Registrar Trabajo</legend>
        <br>
        <form action="" method="post">
        <label for="fechaE">Fecha de entrada</label><br>
        <input type="date" name="fechaE" id="fechaE" value="<?php echo (!empty($_POST['fechaE']) ? $_POST['fechaE'] : date('Y-m-d')) ?>" />
        <br><br>
        <label for="cliente">Cliente</label><br>
        <input type="text" name="cliente" id="cliente"/>
        <br><br>
        <label for="prendas">Tipo de Prenda</label><br>
        <select name="prendas" id="prendas">
            <option>Fiesta</option>
            <option>Cuero</option>
            <option>Hogar</option>
            <option selected="selected">Textil</option>
        </select>
        <br><br>
        <label>Servicios</label><br />
                <input type="checkbox" id="limpieza" name="servicios[]" value="limpieza" />
                <label for="limpieza">Limpieza</label>
                <input type="checkbox" id="planchado" name="servicios[]" value="planchado" />
                <label for="planchado">Planchado</label>
                <input type="checkbox" id="desmanchado" name="servicios[]" value="desmanchado" />
                <label for="desmanchado">Desmanchado</label>
        <br><br>
        <label for="importe">Importe</label><br>
        <input type="number" name="importe" id="importe" value="1">
        <br><br>
        <button type="submit" name="guardar" id="guardar">Guardar</button>
        </form>
    
    </fieldset> 
    <?php
    if (isset($_POST['guardar'])) {
    if (empty($_POST['fechaE']) or empty($_POST['cliente']) or empty($_POST['prendas']) or empty($_POST['importe']) ||!isset($_POST['servicios']) or sizeof($_POST['servicios'])<1) {
        echo 'Debes rellenar la fecha ,cliente ,tipo de prenda,importe y el servicio';
        $resultado=true;
    }

    if (!isset($resultado)) {
        echo'<h1>Datos Guardados</h1>';
        echo '<h2>Cliente:'.$_POST['cliente'].'</h2>';
        echo '<h2>Prenda:'.$_POST['prendas'].'</h2>';
        echo '<h2>Servicios:'.implode('/',$_POST['servicios']).'</h2>';
    }
    }
    ?>
</body>
</html>