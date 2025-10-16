<?php
session_start();

// --- CONFIGURACIÓN DE CONEXIÓN (Asegúrate de que sean correctos) ---
$servidor = "localhost";
$usuario_db = "root";
$contrasena_db = "";
$nombre_db = "registro_db"; 
// -----------------------------------------------------------------

$conexion = new mysqli($servidor, $usuario_db, $contrasena_db, $nombre_db);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Recibir y Sanitizar
    $email = $conexion->real_escape_string($_POST['email']);
    $contrasena_ingresada = $_POST['contrasena'];

    // --- NUEVA LÓGICA DE ADMINISTRADOR ---
    if ($email === 'admin@admin.com' && $contrasena_ingresada === 'admin') {
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['user_nombre'] = 'Administrador';
        $_SESSION['es_admin'] = TRUE; // Marca la sesión como administrador
        
        header("Location: admin_panel.php"); // Redirige al nuevo panel de administrador
        exit;
    }
    // ------------------------------------

    // 2. Buscar el usuario (Usando Sentencias Preparadas para seguridad) - Lógica de usuario normal
    $stmt = $conexion->prepare("SELECT id, nombre, contrasena FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email); 
    $stmt->execute();
    $resultado = $stmt->get_result(); 
    
    if ($resultado->num_rows === 1) {
        // Usuario encontrado
        $usuario = $resultado->fetch_assoc();
        $hash_guardado = $usuario['contrasena'];
        
        // 3. Verificar Contraseña
        if (md5($contrasena_ingresada) === $hash_guardado) {            
            // ÉXITO: Crear la Sesión y Redirigir (Usuario normal)
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_nombre'] = $usuario['nombre'];
            $_SESSION['es_admin'] = FALSE; // Marca la sesión como NO administrador
            
            header("Location: dashboard.php");
            exit;
            
        } else {
            // FALLO: Contraseña incorrecta
            $_SESSION['alerta'] = "Contraseña incorrecta. Inténtalo de nuevo.";
            $_SESSION['tipo_alerta'] = 'error';
            header("Location: index.php?accion=login");
            exit;
        }
        
    } else {
        // FALLO CLAVE: Usuario NO encontrado
        $_SESSION['alerta'] = "¡Esta cuenta no existe! Por favor, verifica tu correo o regístrate en la pestaña de al lado.";
        $_SESSION['tipo_alerta'] = 'error';
        header("Location: index.php?accion=registro"); 
        exit;
    }

    $stmt->close();
}

$conexion->close();
?>