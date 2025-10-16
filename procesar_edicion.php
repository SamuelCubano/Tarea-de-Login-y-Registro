<?php
session_start();

// 1. Verificación de Administrador
if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] !== TRUE) {
    header("Location: index.php"); 
    exit;
}

// --- CONFIGURACIÓN DE CONEXIÓN ---
$servidor = "localhost";
$usuario_db = "root";
$contrasena_db = "";
$nombre_db = "registro_db"; 
// ---------------------------------

$conexion = new mysqli($servidor, $usuario_db, $contrasena_db, $nombre_db);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] === 'editar') {

    // 2. Recibir y Sanitizar Datos
    $id = intval($_POST['id']); 
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $email = $conexion->real_escape_string($_POST['email']);
    
    // Validar el formato del email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['alerta_admin'] = "Error de edición: El formato del correo electrónico no es válido.";
        $_SESSION['tipo_alerta_admin'] = 'error';
        header("Location: admin_panel.php");
        exit();
    }

    // 3. Actualizar Datos (Usando Sentencias Preparadas)
    $stmt = $conexion->prepare("UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?");
    
    // 'ssi' indica string, string, integer
    $stmt->bind_param("ssi", $nombre, $email, $id);
    
    // 4. Ejecutar y Manejar el Resultado
    if ($stmt->execute()) {
        
        // ÉXITO
        $_SESSION['alerta_admin'] = "Usuario con ID $id actualizado con éxito: $nombre ($email).";
        $_SESSION['tipo_alerta_admin'] = 'exito';
        
    } else {
        
        // ERROR: Manejar duplicados (si el nuevo email ya existe)
        if ($conexion->errno == 1062) { 
            $_SESSION['alerta_admin'] = "Error de edición: El correo electrónico '$email' ya pertenece a otro usuario.";
        } else {
            // Otros errores
            $_SESSION['alerta_admin'] = "Error al actualizar el usuario: " . $stmt->error;
        }
        $_SESSION['tipo_alerta_admin'] = 'error';
    }

    $stmt->close();
}

$conexion->close();

// Redirigir al panel
header("Location: admin_panel.php");
exit;
?>