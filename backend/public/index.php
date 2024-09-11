<?php
session_start();

if (!isset($_SESSION['token'])) {
    header('Location: login.php');
    exit;
} else {
    header('Location: panel_control.php');
    exit;
}

// Mostrar mensaje de error si existe
if (isset($_SESSION['error'])) {
    echo "<script type='text/javascript'>alert('" . $_SESSION['error'] . "');</script>";
    unset($_SESSION['error']); // Limpiar el mensaje de error después de mostrarlo
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loader</title>
</head>

<body>
</body>

</html>