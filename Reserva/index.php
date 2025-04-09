<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Reserva</title>
</head>
<body>
    <h1>Formulario de Reserva</h1>
    <form action="" method="post">
        <div>
            <label for="nombre">Nombre</label><br/>
            <input type="text" name="nombre" id="nombre" value="<?php echo (!empty($_POST['nombre'])?$_POST['nombre']:'')?>"/>
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
            <option value="interior">Interior</option>
            <option value="terraza">Terraza</option>
            <option value="reservado">Reservado</option>
        </select>
        </div>
        <div>
            <label for="reserva">Reserva para</label><br/>
            <input type="radio" name="reserva" id="reserva">
            <label for="menu">Menú</label>
            <input type="radio" name="reserva" id="reserva">
            <label for="carta">Carta</label>
        </div>
        <div>
        <label for="prefeciaAlim">Prefencias alimentarias:</label><br/>
        <input type="checkbox" name="prefeciaAlim" id="prefeciaAlim">
        <label for="vegano">Vegano</label>
        <input type="checkbox" name="prefeciaAlim" id="prefeciaAlim">
        <label for="celiaco">Celíaco</label>
        <input type="checkbox" name="prefeciaAlim" id="prefeciaAlim">
        <label for="sin lactosa">Sin lactosa</label>
        </div>
        <br/>
        <div>
            <button type="submit" name="reservar" id="reservar">Reservar</button>
            <button type="submit" name="borrar" id="borrar">Borrar</button>
        </div>
    </form>
</body>
</html>