<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Reserva</title>
</head>
<body>
<style>
        table {
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            text-align: left;
        }
    </style>
    <h1>Formulario de Reserva</h1>
    <form action="" method="post">
        <div>
            <label for="nombre">Nombre</label><br/>
            <input type="text" name="nombre" id="nombre" value="<?php echo (!empty($_POST['nombre']) ? $_POST['nombre']:'') ?>"/>
        </div>
        <div>
            <label for="fechaR">Fecha de la Reserva</label><br/>
            <input type="date" name="fechaR" id="fechaR" value="<?php echo (!empty($_POST['fechaR']) ? $_POST['fechaR'] : date('Y-m-d')) ?>"/>
        </div>
        <div>
            <label for="hora">Hora</label><br/>
            <input type="time" name="hora" id="hora" value="<?php echo (!empty($_POST['hora']) ? $_POST['hora'] : date('H:i')) ?>"/>
        </div>
        <div>
            <label for="numPersonas">Número de personas</label><br/>
            <input type="number" name="numPersonas" id="numPersonas" value="4"/>
        </div>
        <div>
            <label for="zonaP">Zona preferida</label><br/>
            <select name="zonaP" id="zonaP">
                <option <?php echo (isset($_POST['zonaP']) && $_POST['zonaP'] == 'Interior' ? 'selected="selected"' : '') ?>>Interior</option>
                <option <?php echo (isset($_POST['zonaP']) && $_POST['zonaP'] == 'Terraza' ? 'selected="selected"' : '') ?>>Terraza</option>
                <option <?php echo (isset($_POST['zonaP']) && $_POST['zonaP'] == 'Reservado' ? 'selected="selected"' : '') ?>>Reservado</option>
            </select>
        </div>
        <div>
            <label for="reserva">Reserva para</label><br/>
            <input type="radio" name="reserva" value="Menú" <?php echo (isset($_POST['reserva']) && $_POST['reserva'] == 'Menú' ? 'checked' : '') ?>/>
            <label for="Menú">Menú</label>
            <input type="radio" name="reserva" value="Carta" <?php echo (isset($_POST['reserva']) && $_POST['reserva'] == 'Carta' ? 'checked' : '') ?>/>
            <label for="Carta">Carta</label>
        </div>
        <div>
            <label for="prefeciaAlim">Preferencias alimentarias:</label><br/>
            <input type="checkbox" name="prefeciaAlim[]" value="Vegano" <?php echo (isset($_POST['prefeciaAlim']) && in_array('Vegano', $_POST['prefeciaAlim']) ? 'checked' : '') ?>/> Vegano
            <input type="checkbox" name="prefeciaAlim[]" value="Celíaco" <?php echo (isset($_POST['prefeciaAlim']) && in_array('Celíaco', $_POST['prefeciaAlim']) ? 'checked' : '') ?>/> Celíaco
            <input type="checkbox" name="prefeciaAlim[]" value="Sin lactosa" <?php echo (isset($_POST['prefeciaAlim']) && in_array('Sin lactosa', $_POST['prefeciaAlim']) ? 'checked' : '') ?>/> Sin lactosa
        </div>
        <br/>
        <div>
            <button type="submit" name="reservar" id="reservar">Reservar</button>
            <button type="submit" name="borrar" id="borrar">Borrar</button>
        </div>
    </form>

    <?php
    if (isset($_POST['reservar'])) {
        if (empty($_POST['nombre']) or empty($_POST['fechaR']) or empty($_POST['hora'])) {
            echo 'Campos Vacios,Debes Rellenar el nombre, fecha y la hora';
        }elseif ($_POST['numPersonas'] < 2) {
            echo 'No puedes reservar con un número menor a 2 personas';
        }elseif (empty($_POST['reserva'])) {
            echo 'Debe seleccionar un tipo de reserva';
        }elseif ($_POST['zonaP'] == 'Terraza' && $_POST['numPersonas'] > 8) {
            echo 'La terraza no se puede reservar para más de 8 personas';
        }elseif ($_POST['reserva'] == 'Menú' && in_array('Sin lactosa', $_POST['prefeciaAlim'])) {
            echo 'No puedes seleccionar Menú y Sin lactosa juntos';
        }elseif ($_POST['zonaP'] == 'Terraza' && $_POST['reserva'] == 'Menú') {
            echo 'No puedes seleccionar Terraza y Menú al mismo tiempo';
        }else {
            echo '<table border="1">';
            echo '<tr><th>Nombre</th><td>' . $_POST['nombre'] . '</td></tr>';
            echo '<tr><th>Fecha Reserva</th><td>' . $_POST['fechaR'] . '</td></tr>';
            echo '<tr><th>Hora</th><td>' . $_POST['hora'] . '</td></tr>';
            echo '<tr><th>Nº de personas</th><td>' . $_POST['numPersonas'] . '</td></tr>';
            echo '<tr><th>Zona</th><td>' . $_POST['zonaP'] . '</td></tr>';
            echo '<tr><th>Reserva para</th><td>' . $_POST['reserva'] . '</td></tr>';
            echo '<tr><th>Preferencias Alimentarias</th><td>' . (empty($_POST['prefeciaAlim']) ? 'Ninguna' : implode(', ', $_POST['prefeciaAlim'])) . '</td></tr>';

            echo '</table>';
        }
    }
    ?>
</body>
</html>
