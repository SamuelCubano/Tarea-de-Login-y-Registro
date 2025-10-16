<?php
session_start();

// 1. Verificación de Administrador
if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] !== TRUE) {
    header("Location: index.php"); // Si no es admin, lo redirige
    exit;
}

// --- CONFIGURACIÓN DE CONEXIÓN ---
$servidor = "localhost";
$usuario_db = "root";
$contrasena_db = "";
$nombre_db = "registro_db"; 
// ---------------------------------

// --- ESTO FUE LO QUE NOS DIJO MOYA PARA USARLO EN EL ADMIN_PANEL.PHP ---
$conexion = new mysqli($servidor, $usuario_db, $contrasena_db, $nombre_db);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// 2. DE ACÁ OBTENEMOS LOS USUARIOS PARA MOSTRARLOS EN LA TABLA
$usuarios = [];
$consulta_usuarios = "SELECT id, nombre, email, fecha_registro FROM usuarios ORDER BY id DESC";
$resultado = $conexion->query($consulta_usuarios);

if ($resultado->num_rows > 0) {
    while($fila = $resultado->fetch_assoc()) {
        $usuarios[] = $fila;
    }
}

// ESTO ES PARA MOSTRAR MENSAJES DE ALERTA
$mensaje = '';
$tipo_alerta = '';
if (isset($_SESSION['alerta_admin'])) {
    $mensaje = $_SESSION['alerta_admin'];
    $tipo_alerta = $_SESSION['tipo_alerta_admin'] ?? 'exito';
    unset($_SESSION['alerta_admin']);
    unset($_SESSION['tipo_alerta_admin']);
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administrador</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url(img/12.gif);
            background-size: cover;
            margin: 0;
            color: #333;
        }
        .contenedor {
            max-width: 1200px;
            margin: 0 auto;
            margin-top: 25vh;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #6f00ffff;
            border-bottom: 2px solid #00bfff;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .alerta-mensaje {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .alerta-mensaje.exito {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alerta-mensaje.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #00a8e0ff;
            color: white;
            text-transform: uppercase;
            font-size: 14px;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .btn-editar {
            background-color: #6f00ffff;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-editar:hover {
            background-color: #44029bff;
        }
        .btn-logout {
            float: right;
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-logout:hover {
            background-color: #c82333;
        }

        /* Estilos del Modal */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 10; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px;
        }

        .modal-contenido {
            background-color: #fefefe;
            margin: 5% auto; /* 5% desde arriba y centrado */
            padding: 30px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            position: relative;
        }

        .cerrar {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .cerrar:hover,
        .cerrar:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-contenido input[type="text"], 
        .modal-contenido input[type="email"], 
        .modal-contenido button {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .modal-contenido button[type="submit"] {
            background-color: #00a8e0ff;
            color: white;
            border: none;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .modal-contenido button[type="submit"]:hover {
            background-color: #007bff;
        }

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

footer {
    border-top: solid 2px cornflowerblue;
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
    margin-top: 10vh;
    text-align: center;
    padding: 15px 0;
    background: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(8px);
    color: #ccc;
    font-size: 14px;
}

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
    </style>
</head>
<header class="main-header">
        <div class="logo">
            <img src="img/IUJO (1).png" alt="Logo" height="40">
            <h2>Samuel Cubano & Keiver Blanco</h2>
        </div>
    </header>
<body>
    <div class="contenedor">
        <a href="logout.php" class="btn-logout">Cerrar Sesión (Admin)</a>
        <h1>Panel de Administrador</h1>

        <?php if ($mensaje): ?>
            <div class="alerta-mensaje <?php echo $tipo_alerta; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <h2>Tabla de Usuarios Registrados</h2>
        
        <?php if (!empty($usuarios)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo Electrónico</th>
                    <th>Fecha de Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['fecha_registro']); ?></td>
                    <td>
                        <button class="btn-editar" 
                                onclick="abrirModal(
                                    <?php echo $usuario['id']; ?>, 
                                    '<?php echo htmlspecialchars(addslashes($usuario['nombre'])); ?>', 
                                    '<?php echo htmlspecialchars(addslashes($usuario['email'])); ?>'
                                )">
                            Editar
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>No hay usuarios registrados.</p>
        <?php endif; ?>
    </div>

    <div id="modal-edicion" class="modal">
        <div class="modal-contenido">
            <span class="cerrar" onclick="cerrarModal()">&times;</span>
            <h2>Editar Usuario</h2>
            <form action="procesar_edicion.php" method="POST">
                <input type="hidden" id="edit-id" name="id">
                
                <label for="edit-nombre">Nombre:</label>
                <input type="text" id="edit-nombre" name="nombre" required>

                <label for="edit-email">Correo Electrónico:</label>
                <input type="email" id="edit-email" name="email" required>
                
                <button type="submit" name="accion" value="editar">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <footer class="main-footer">
        <p>© <?php echo date("Y"); ?> Samuel Cubano CI: 32935820 & Keiver Blanco</p>
        <a href="https://github.com/SamuelCubano/Tarea-de-Login-y-Registro" target="_blank"><i class="fa-brands fa-github"></i></a>
    </footer>
    <script>
        // Funcionalidad del Modal
        const modal = document.getElementById('modal-edicion');
        const span = document.getElementsByClassName("cerrar")[0];

        function abrirModal(id, nombre, email) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nombre').value = nombre;
            document.getElementById('edit-email').value = email;
            modal.style.display = "block";
        }

        function cerrarModal() {
            modal.style.display = "none";
        }

        span.onclick = cerrarModal;

        window.onclick = function(event) {
            if (event.target == modal) {
                cerrarModal();
            }
        }
    </script>

</body>
</html>