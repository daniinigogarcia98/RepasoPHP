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
        <input type="date" name="fechaE" id="fechaE"   value="<?php echo (!empty($_POST['fechaE']) ? $_POST['fechaE'] : date('Y-m-d')) ?>" />
        <br><br>
        <label for="cliente">Cliente</label><br>
        <input type="text" name="cliente" id="cliente" value="<?php echo (!empty($_POST['cliente']) ? $_POST['cliente'] : '') ?>" />
        <br><br>
        <label for="prendas">Tipo de Prenda</label><br>
        <select name="prendas[]" id="prendas">
            <option <?php echo (isset($_POST['prendas']) && $_POST['prendas'] == 'Fiesta') ?>>Fiesta</option>
            <option <?php echo (isset($_POST['prendas']) && $_POST['prendas'] == 'Cuero') ?>>Cuero</option>
            <option <?php echo (isset($_POST['prendas']) && $_POST['prendas'] == 'Hogar') ?>>Hogar</option>
            <option <?php echo (isset($_POST['prendas']) && $_POST['prendas'] == 'Textil'? 'selected' : 'selected') ?>>Textil</option>
        </select>
        <br><br>
        <label for="servicio">Servicio</label><br>
        <input type="checkbox" name="limpieza" id="limpieza">
        <label for="limpieza">Limpieza</label>
        <input type="checkbox" name="planchado" id="planchado">
        <label for="planchado">Planchado</label>
        <input type="checkbox" name="desmanchado" id="desmanchado">
        <label for="desmanchado">Desmanchado</label>
        <br><br>
        <label for="importe">Importe</label><br>
        <input type="number" name="importe" id="importe">
        <br><br>
        <button type="submit" name="guardar" id="guardar">Guardar</button>
        </form>
    
    </fieldset> 
    <?php
    if (isset($_POST['guardar'])) {
        if (empty($_POST['cliente']) || !isset($_POST['fechaE']) || !isset($_POST['prendas']) || !isset($_POST['servicio']) || !isset($_POST['importe'])) {
            echo 'Campos Vacíos';
        } 
    }
    ?>
</body>
</html>