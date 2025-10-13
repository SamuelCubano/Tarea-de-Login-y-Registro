<?php
session_start();

// Si el usuario no está logueado, lo envía de vuelta al login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== TRUE) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido</title>
</head>
<body>
    <h1>¡Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?>!</h1>
    <p>Has iniciado sesión con éxito.</p>
    
    <p><a href="logout.php">Cerrar Sesión</a></p>
</body>
</html>