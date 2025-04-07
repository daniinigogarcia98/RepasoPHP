<?php
require_once "Entrada.php";
require_once "Modelo.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venta de Entradas</title>
    <!-- Bootstrap 5.3.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="container mt-4">
    <form action="" method="post" class="card p-4">
        <fieldset>
            <legend class="text-center fw-bold">Venta de Entradas</legend>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre Completo:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" 
                    value="<?php echo (!empty($_POST['nombre']) ? $_POST['nombre'] : '') ?>" />
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo de Entrada:</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipoEntrada" id="tipoGeneral" value="General" checked />
                    <label class="form-check-label" for="tipoGeneral">General</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipoEntrada" id="tipoMayor" value="Mayor60" 
                        <?php echo (isset($_POST['tipoEntrada']) && $_POST['tipoEntrada'] == 'Mayor60' ? 'checked' : '') ?> />
                    <label class="form-check-label" for="tipoMayor">Mayor de 60</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipoEntrada" id="tipoMenor" value="Menor6" 
                        <?php echo (isset($_POST['tipoEntrada']) && $_POST['tipoEntrada'] == 'Menor6' ? 'checked' : '') ?> />
                    <label class="form-check-label" for="tipoMenor">Menor de 6</label>
                </div>
            </div>

            <div class="mb-3">
                <label for="espectaculo" class="form-label">Espectáculo:</label>
                <select name="espectaculo" id="espectaculo" class="form-select">
                    <option <?php echo (isset($_POST['espectaculo']) && $_POST['espectaculo'] == 'Concierto' ? 'selected' : '') ?>>Concierto</option>
                    <option <?php echo (isset($_POST['espectaculo']) && $_POST['espectaculo'] == 'Magia' ? 'selected' : '') ?>>Magia</option>
                    <option <?php echo (isset($_POST['espectaculo']) && $_POST['espectaculo'] == 'Teatro' ? 'selected' : '') ?>>Teatro</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="fechaEvento" class="form-label">Fecha del Evento:</label>
                <input type="date" name="fechaEvento" id="fechaEvento" class="form-control"
                    value="<?php echo (!empty($_POST['fechaEvento']) ? $_POST['fechaEvento'] : date('Y-m-d')) ?>" />
            </div>

            <div class="mb-3">
                <label for="numEntradas" class="form-label">Número de Entradas:</label>
                <input type="number" name="numEntradas" id="numEntradas" class="form-control"
                    value="<?php echo (!empty($_POST['numEntradas']) ? $_POST['numEntradas'] : '1') ?>">
            </div>

            <div class="mb-3">
                <label for="descuento" class="form-label">Descuento:</label>
                <select name="descuento[]" id="descuento" multiple class="form-select">
                    <option <?php echo (isset($_POST['descuento']) && in_array('Familia Numerosa', $_POST['descuento']) ? 'selected' : '') ?>>Familia Numerosa</option>
                    <option <?php echo (isset($_POST['descuento']) && in_array('Abonado', $_POST['descuento']) ? 'selected' : '') ?>>Abonado</option>
                    <option <?php echo (isset($_POST['descuento']) && in_array('Día del Espectador', $_POST['descuento']) ? 'selected' : '') ?>>Día del Espectador</option>
                </select>
            </div>

            <button type="submit" name="comprar" id="comprar" class="btn btn-primary">Comprar</button>
        </fieldset>
    </form>

    <!-- Ticket -->
    <fieldset class="card p-4 mt-4">
        <legend class="text-center fw-bold">Ticket</legend>
        <?php
        if (isset($_POST['comprar'])) {
            if (empty($_POST['nombre']) || !isset($_POST['tipoEntrada']) || !isset($_POST['espectaculo']) || !isset($_POST['fechaEvento']) || !isset($_POST['numEntradas'])) {
                echo '<div class="alert alert-danger">Campos Vacíos</div>';
            } else {
                if ($_POST['numEntradas'] < 1 || ($_POST['numEntradas'] > 4)) {
                    echo '<div class="alert alert-danger">Debe seleccionar entre 1 y 4 entradas</div>';
                } else {
                    if ($_POST['tipoEntrada'] == 'Mayor60' && isset($_POST['descuento']) && in_array('Familia Numerosa', $_POST['descuento'])) {
                        echo '<div class="alert alert-danger">Mayor de 60 no es compatible con el descuento de Familia Numerosa</div>';
                    } else {
                        $total = 0;
                        if ($_POST['tipoEntrada'] == 'General') {
                            $total = 10 * ($_POST['numEntradas']);
                        } elseif ($_POST['tipoEntrada'] == 'Mayor60') {
                            $total = 8 * ($_POST['numEntradas']);
                        } else {
                            $total = 5 * ($_POST['numEntradas']);
                        }
                        if (isset($_POST['descuento'])) {
                            if (in_array('Familia Numerosa', $_POST['descuento'])) $total *= 0.95;
                            if (in_array('Abonado', $_POST['descuento'])) $total *= 0.90;
                            if (in_array('Día del Espectador', $_POST['descuento'])) $total *= 0.98;
                        }
                        ?>

                        <table class="table table-bordered mt-3">
                            <thead class="table-dark">
                                <tr>
                                    <th colspan="2">Ticket de Compra</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Nombre</td><td><?php echo $_POST['nombre'] ?></td></tr>
                                <tr><td>Tipo Entrada</td><td><?php echo $_POST['tipoEntrada'] ?></td></tr>
                                <tr><td>Nº de Entrada</td><td><?php echo $_POST['numEntradas'] ?></td></tr>
                                <tr><td>Fecha Evento</td><td><?php echo $_POST['fechaEvento'] ?></td></tr>
                                <tr><td>Descuento</td><td><?php echo (isset($_POST['descuento']) ? implode('/', $_POST['descuento']) : 'Ninguno') ?></td></tr>
                                <tr><td>Total a Pagar</td><td><?php echo $total ?>€</td></tr>
                            </tbody>
                        </table>

                        <?php
                        $e = new Entrada($_POST['nombre'], $_POST['tipoEntrada'], $_POST['fechaEvento'], $_POST['numEntradas'], (isset($_POST['descuento']) ? implode('/', $_POST['descuento']) : ''), $total);
                        $f = new Modelo();
                        if ($f->insertar($e)) echo '<div class="alert alert-success">Entrada guardada</div>';
                    }
                }
            }
        }
        ?>
    </fieldset>
</body>

</html>
