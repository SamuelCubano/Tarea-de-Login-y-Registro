<?php
session_start(); // Inicia la sesión para guardar el mensaje de alerta

// --- CONFIGURACIÓN DE CONEXIÓN (Asegúrate de que sean correctos) ---
$servidor = "localhost";
$usuario_db = "root";
$contrasena_db = "";
$nombre_db = "registro_db"; 
// -----------------------------------------------------------------

$conexion = new mysqli($servidor, $usuario_db, $contrasena_db, $nombre_db);

if ($conexion->connect_error) {
    // Error grave de conexión, termina la ejecución
    $_SESSION['alerta'] = "Error de conexión con la base de datos.";
    $_SESSION['tipo_alerta'] = 'error';
    header("Location: index.php?accion=registro"); // Redirige a la pestaña de registro
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. RECIBIR Y SANITIZAR DATOS
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $email = $conexion->real_escape_string($_POST['email']);
    $contrasena_plana = $_POST['contrasena'];
    
    // Validar el formato del email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['alerta'] = "Error: El formato del correo electrónico no es válido.";
        $_SESSION['tipo_alerta'] = 'error';
        header("Location: index.php?accion=registro");
        exit();
    }

    // 2. CIFRAR LA CONTRASEÑA
    // Se recomienda una longitud mínima de 8 caracteres para la contraseña
    if (strlen($contrasena_plana) < 8) {
         $_SESSION['alerta'] = "Error: La contraseña debe tener al menos 8 caracteres.";
         $_SESSION['tipo_alerta'] = 'error';
         header("Location: index.php?accion=registro");
         exit();
    }
    $contrasena_hash = password_hash($contrasena_plana, PASSWORD_DEFAULT);
    $fecha_registro = date('Y-m-d H:i:s'); 

    // 3. INSERTAR DATOS (Usando Sentencias Preparadas)
    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, email, contrasena, fecha_registro) 
                                VALUES (?, ?, ?, ?)");
    
    // 'ssss' indica que los cuatro parámetros son strings
    $stmt->bind_param("ssss", $nombre, $email, $contrasena_hash, $fecha_registro);
    
    // 4. EJECUTAR Y MANEJAR EL RESULTADO
    if ($stmt->execute()) {
        
        // REGISTRO EXITOSO: Establecer la alerta de éxito
        $_SESSION['alerta'] = "¡Cuenta creada con éxito! Ya puedes iniciar sesión.";
        $_SESSION['tipo_alerta'] = 'exito';
        
        // Redirigir a la pestaña de LOGIN (opuesta al registro)
        header("Location: index.php?accion=login"); 
        exit;
        
    } else {
        
        // ERROR: Manejar duplicados (por si el email ya existe)
        if ($conexion->errno == 1062) { // 1062 es el código de error para entrada duplicada en clave UNIQUE
            $_SESSION['alerta'] = "El correo electrónico ya está registrado. Intenta iniciar sesión.";
            $_SESSION['tipo_alerta'] = 'error';
            header("Location: index.php?accion=login"); // Redirige a la pestaña de LOGIN
            exit;
        } else {
            // Otros errores
            $_SESSION['alerta'] = "Error al registrar el usuario: " . $stmt->error;
            $_SESSION['tipo_alerta'] = 'error';
            header("Location: index.php?accion=registro");
            exit;
        }
    }

    $stmt->close();
}

$conexion->close();
?>