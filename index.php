<?php
session_start(); // Siempre al inicio para usar sesiones

// Determina qué formulario mostrar por defecto
$mostrar_registro = isset($_POST['accion']) && $_POST['accion'] === 'registro';

// Recupera y limpia el mensaje de alerta de la sesión
$mensaje = '';
$tipo_alerta = '';
if (isset($_SESSION['alerta'])) {
    $mensaje = $_SESSION['alerta'];
    $tipo_alerta = $_SESSION['tipo_alerta'] ?? 'exito';
    // Limpia las variables para que el mensaje no se muestre al recargar
    unset($_SESSION['alerta']);
    unset($_SESSION['tipo_alerta']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso y Registros</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cf1fb60fea.js" crossorigin="anonymous"></script>
    <style>
    body {
    font-family: 'Poppins', sans-serif;
    /* Fondo: Asegúrate que 'img/12.gif' exista */
    background: url('img/12.gif') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    padding: 0;
    color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    /* Esto asegura que el contenido ocupe toda la altura de la vista */
    min-height: 100vh;
}

/* Contenedor del Formulario */
.contenedor-formulario {
    /* Fondo de cristal (glassmorphism) */
    background: rgba(255, 255, 255, 0.12);
    /* Recomendación: Puedes probar con rgba(0, 0, 0, 0.4) para un fondo más oscuro */
    backdrop-filter: blur(15px);
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
    padding: 40px 50px;
    width: 90%;
    max-width: 400px;
    text-align: center;
    animation: fadeIn 0.8s ease-in-out;
    /* Margen superior/inferior para evitar superponerse con header/footer al hacer scroll */
    margin: 80px 0;

    width: 90%;
    max-width: 400px; /* Mantenemos este límite para pantallas grandes */
    text-align: center;
    /* CAMBIO CLAVE: Agregar márgenes automáticos para centrado horizontal */
    margin-left: auto;
    margin-right: auto;
    /* Agregar un margen vertical para asegurar que no se pegue al header/footer */
    margin-top: 80px; 
    margin-bottom: 80px;
}

/* Título de los formularios (Iniciar Sesión / Registrarse) */
h1 {
    font-size: 26px;
    margin-bottom: 25px;
    color: #fff;
}

/* Opciones superiores (Botones) */
.opciones-formulario {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.opciones-formulario button {
    background: transparent;
    border: none;
    color: #ccc;
    font-size: 16px;
    margin: 0 15px;
    padding: 10px;
    border-bottom: 2px solid transparent;
    transition: all 0.3s ease;
    cursor: pointer;
}

.opciones-formulario button:hover {
    color: #fff;
}

.opciones-formulario button.activo {
    color: #00bfff; /* Color azul neón */
    border-bottom: 2px solid #00bfff;
}

/* Inputs de texto, email y password */
input[type="text"],
input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 12px 15px;
    /* Ajuste: Eliminado el margen negativo para mejor responsividad */
    margin: 10px 0 20px 0; 
    border: none;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.15);
    color: #fff;
    font-size: 15px;
    outline: none;
    transition: all 0.3s ease;
    /* La etiqueta <label> no aparece en el HTML adjunto, pero es buena práctica tenerla */
}

input:focus {
    background: rgba(255, 255, 255, 0.25);
    box-shadow: 0 0 5px #00bfff;
}

/* Botón principal (Entrar / Crear Cuenta) */
button[type="submit"] {
    width: 100%;
    background: linear-gradient(90deg, #00bfff, #007bff);
    color: #fff;
    padding: 12px 0;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

button[type="submit"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 191, 255, 0.5);
}

/* Estilos de Alertas (PHP) */
.alerta-mensaje {
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-weight: 600;
    font-size: 14px;
}

.alerta-mensaje.exito {
    background-color: rgba(212, 237, 218, 0.8);
    color: #155724;
    border: 1px solid rgba(195, 230, 203, 0.7);
}

.alerta-mensaje.error {
    background-color: rgba(248, 215, 218, 0.8);
    color: #721c24;
    border: 1px solid rgba(245, 198, 203, 0.7);
}

/* Animación de entrada del formulario */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Header */
.main-header {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 40px;
    position: fixed;
    top: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(10px);
    z-index: 100;
    color: #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

header {
    border-bottom: solid 2px cornflowerblue;
}

.main-header .logo {
    display: flex;
    align-items: center;
    gap: 10px;
}

.main-header .logo h2 {
    font-size: 22px;
    color: #00bfff;
}

/* Footer */
.main-footer {
    width: 100%;
    text-align: center;
    padding: 15px 0;
    position: fixed;
    bottom: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(8px);
    color: #ccc;
    font-size: 14px;
    border-top: solid 2px cornflowerblue;
}

/* Estilo del ícono de GitHub */
i {
    margin-left: 10px;
    color: #ccc;
    cursor: pointer;
    font-size: 30px;
    transition: color 0.3s ease;
}

i:hover {
    color: #00bfff;
}

/* ==================================== */
/* RESPONSIVO (MEJORAS PARA CELULAR)    */
/* ==================================== */
@media (max-width: 480px) {
    /* Contenedor del formulario */
    .contenedor-formulario {
        padding: 30px 20px;
        width: 95%; /* Esto asegura que ocupe casi todo el ancho de la pantalla */
        /* Eliminamos el max-width (o lo aumentamos) para que pueda crecer */
        max-width: 90%; /* <--- CAMBIO CLAVE: Aumentar el límite o ponerlo más cercano al 100% */
        margin: 70px 0;
    }
    /* ... (el resto de tu código para móvil) ... */
    .main-header .logo h2 {
        font-size: 16px; 
    }
    
    .main-footer {
        padding: 10px 5px;
        font-size: 12px;
    }
    
    .main-footer i {
        font-size: 24px;
        margin-left: 5px; 
        display: block;
        margin-top: 5px;
    }
}
    </style>
</head>
<body>
    <header class="main-header">
        <div class="logo">
            <img src="img/IUJO (1).png" alt="Logo" height="40">
            <h2>Samuel Cubano & Keiver Blanco</h2>
        </div>
    </header>

    <div class="contenedor-formulario">
        
        <div class="opciones-formulario">
            <button onclick="mostrarFormulario('login')" id="btn-login">Iniciar Sesión</button>
            <button onclick="mostrarFormulario('registro')" id="btn-registro">Registrarse</button>
        </div>

        <?php if ($mensaje): ?>
            <div class="alerta-mensaje <?php echo $tipo_alerta; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <form action="procesar_login.php" method="POST" id="form-login" style="display: <?php echo $mostrar_registro ? 'none' : 'block'; ?>">
            <h1>Iniciar Sesión</h1>
            <label for="email_login">Correo Electrónico:</label>
            <input type="email" id="email_login" name="email" required>
            <br>
            <label for="contrasena_login">Contraseña:</label>
            <input type="password" id="contrasena_login" name="contrasena" required>
            <br>
            <button type="submit">Entrar</button>
        </form>

        <form action="procesar_registro.php" method="POST" id="form-registro" style="display: <?php echo $mostrar_registro ? 'block' : 'none'; ?>">
            <h1>Registrarse</h1>
            <label for="nombre_registro">Nombre Completo:</label>
            <input type="text" id="nombre_registro" name="nombre" required>

            <label for="email_registro">Correo Electrónico:</label>
            <input type="email" id="email_registro" name="email" required>

            <label for="contrasena_registro">Contraseña:</label>
            <input type="password" id="contrasena_registro" name="contrasena" required>

            <button type="submit">Crear Cuenta</button>
        </form>
    </div>

    <footer class="main-footer">
        <p>© <?php echo date("Y"); ?> Samuel Cubano CI: 32935820 & Keiver Blanco CI:</p>
        <a href="https://github.com/SamuelCubano/Tarea-de-Login-y-Registro" target="_blank"><i class="fa-brands fa-github"></i></a>
    </footer>

    <script>
        function mostrarFormulario(tipo) {
            const isLogin = tipo === 'login';
            document.getElementById('form-login').style.display = isLogin ? 'block' : 'none';
            document.getElementById('btn-login').classList.toggle('activo', isLogin);
            
            document.getElementById('form-registro').style.display = isLogin ? 'none' : 'block';
            document.getElementById('btn-registro').classList.toggle('activo', !isLogin);
        }
        // Inicializa el estado activo del botón al cargar
        document.addEventListener('DOMContentLoaded', function() {
            mostrarFormulario('<?php echo $mostrar_registro ? 'registro' : 'login'; ?>');
        });
    </script>
</body>
</html>