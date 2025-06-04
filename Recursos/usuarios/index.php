<?php
session_start();

// 1. Validar sesión activa
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../app/views/login.php');
    exit();
}

// 2. Validar User-Agent e IP del cliente
$cliente_user_agent = $_SERVER['HTTP_USER_AGENT'];
$cliente_ip = $_SERVER['REMOTE_ADDR'];

if (!isset($_SESSION['agente']) || !isset($_SESSION['ip'])) {
    $_SESSION['agente'] = $cliente_user_agent;
    $_SESSION['ip'] = $cliente_ip;
}

// Si cambió el navegador o la IP, cerrar sesión
if ($_SESSION['agente'] !== $cliente_user_agent || $_SESSION['ip'] !== $cliente_ip) {
    session_unset();
    session_destroy();
    header('Location: ../../app/views/login.php');
    exit();
}
;

// 3. Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'login_a');
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// 4. Obtener datos del usuario
$usuario = $_SESSION['usuario'];
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();
$datos = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Bienvenida Al Sistema Educativo</title>

    <script>
        // Detectar si ya hay otra pestaña abierta
        const pestañaActiva = localStorage.getItem('pestana_activa');

        if (pestañaActiva && pestañaActiva !== window.name) {
            // Ya hay otra pestaña activa, redirigir al login o mostrar error
            alert("Ya tienes otra pestaña abierta con la sesión activa. Serás redirigido.");
            window.location.href = "../../app/views/login.php"; // Ajusta si es necesario
        } else {
            // Esta es la pestaña activa
            window.name = Date.now(); // Nombre único de la ventana
            localStorage.setItem('pestana_activa', window.name);
        }

        // Al cerrar la pestaña, liberamos el control
        window.addEventListener('beforeunload', function () {
            if (localStorage.getItem('pestana_activa') === window.name) {
                localStorage.removeItem('pestana_activa');
            }
        });
    </script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1a2a6c, #b21f1f, #1a2a6c);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.9);
            padding: 15px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }
        .mensaje-exito {
            background-color: #e6ffed;
            border: 1px solid #5cb85c;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            color: #3c763d;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo i {
            font-size: 28px;
            color: #1a2a6c;
            margin-right: 10px;
        }

        .logo h1 {
            font-size: 24px;
            color: #1a2a6c;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info span {
            margin-right: 20px;
            font-weight: 600;
            color: #1a2a6c;
        }

        .user-info button {
            background: #1a2a6c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-left: 10px;
        }

        .user-info button:hover {
            background: #0d1a4a;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-logout {
            background: #b21f1f !important;
        }

        .btn-logout:hover {
            background: #8a1919 !important;
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr 350px; /* contenido principal + sidebar */
            gap: 25px;
            align-items: start;
        }
        
        .sidebar {
            /* Elimina el position: fixed */
            position: static;
            width: 100%;
        }

        .sidebar .card {
            height: 100%; /* Para que la tarjeta ocupe toda la altura del contenedor sidebar */
            position: sticky; /* Que la sidebar quede fija cuando haces scroll */
            top: 20px; /* Espacio desde arriba al hacer scroll */
        }

        .card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .sidebar .card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .settings-section {
            margin-bottom: 25px;
        }

        .settings-section h2 {
            color: #1a2a6c;
            border-bottom: 2px solid #1a2a6c;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #1a2a6c;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            border-color: #1a2a6c;
            outline: none;
        }

        .btn {
            background: #1a2a6c;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn:hover {
            background: #0d1a4a;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-save {
            background: #28a745;
        }

        .btn-save:hover {
            background: #218838;
        }

        .course-list {
            margin-top: 30px;
        }

        .course-list h3 {
            color: #1a2a6c;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #1a2a6c;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .course-select {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .course-select input {
            width: 20px;
            height: 20px;
        }

        .welcome-message {
            text-align: center;
            margin-bottom: 30px;
        }

        .welcome-message h1 {
            font-size: 2.5rem;
            color: white;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .welcome-message p {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 700px;
            margin: 0 auto;
        }

        .info-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            margin-top: 25px;
        }

        .info-card h3 {
            color: #1a2a6c;
            margin-bottom: 15px;
        }

        .info-card p {
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .info-card ul {
            padding-left: 20px;
            margin-bottom: 15px;
        }

        .info-card li {
            margin-bottom: 8px;
        }

        .sidebar .card {
            border-left: 5px solid #1a2a6c;
            background: #ffffffdd;
            backdrop-filter: blur(4px);
            transition: all 0.3s ease-in-out;
        }

        .user-profile {
            text-align: center;
            padding: 20px 0;
        }

        .user-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-image: url('logo.png'); /* Ruta relativa a tu proyecto */
            background-size: cover;
            background-position: center;
            margin: 0 auto 15px;
        }

        .user-name {
            font-size: 24px;
            font-weight: 600;
            color: #1a2a6c;
            margin-bottom: 10px;
        }

        .user-role {
            color: #666;
            margin-bottom: 20px;
        }

        .stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
        }

        .stat-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            border-left: 4px solid #1a2a6c;
        }

        .stat-card h4 {
            color: #1a2a6c;
            margin-bottom: 5px;
        }

        .stat-card p {
            font-size: 24px;
            font-weight: 700;
            color: #333;
        }
        .lista-tareas {
            list-style: none;
            padding: 0;
        }
        .tarea-item {
            margin-bottom: 10px;
        }

        .selected-courses {
            background: linear-gradient(135deg, #e0f7fa, #ffffff);
            border-left: 6px solid #1a2a6c;
            padding: 20px 25px;
            border-radius: 12px;
            margin-top: 20px;
            margin-bottom: 30px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .selected-courses:hover {
            transform: scale(1.01);
        }

        .selected-courses h4 {
            font-size: 22px;
            color: #1a2a6c;
            margin-bottom: 15px;
        }

        .selected-course-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .selected-course-list li {
            background-color: #1a2a6c;
            color: white;
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 10px;
            font-weight: 500;
            font-size: 16px;
            position: relative;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: background 0.3s ease;
        }

        .selected-course-list li::before {
            content: "✔️";
            margin-right: 10px;
        }

        .selected-course-list li:hover {
            background-color: #0d1a4a;
        }

        footer {
            text-align: center;
            color: white;
            margin-top: 30px;
            padding: 20px;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            header {
                flex-direction: column;
                text-align: center;
            }
            
            .user-info {
                margin-top: 15px;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .user-info span {
                margin-right: 10px;
                margin-bottom: 10px;
            }
        }
    </style>

</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                <h1>Sistema Educativo</h1>
            </div>
            <div class="user-info">
                <?php if (isset($_SESSION['usuario'])): ?>
                    <span>Bienvenido: <?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
                    <a href="/cursos/Panel/public/logout.php" class="logout-btn">Cerrar sesión</a>
                <?php else: ?>
                    <a href="/cursos/Panel/public/login.php" class="login-btn">Iniciar sesión</a>
                <?php endif; ?>
            </div>
        </header>
        
        <div class="welcome-message">
            <h1>Bienvenido al Sistema Educativo</h1>
            <p>Gestiona tu perfil, selecciona tus cursos preferidos y personaliza tu experiencia de aprendizaje.</p>
        </div>
        
        <div class="main-content">
        <!-- Contenido principal a la izquierda -->
        <div class="content">
            <div class="card">
                <!-- Configuración de Contraseña -->
                <div class="settings-section">
                    <h2>Configuración de Contraseña</h2>
                    <form action="cambiar_contraseña.php" method="POST">
                        <div class="form-group">
                            <label for="current-pass">Contraseña Actual</label>
                            <input type="password" id="current-pass" name="current_pass" required>
                        </div>
                        <div class="form-group">
                            <label for="new-pass">Nueva Contraseña</label>
                            <input type="password" id="new-pass" name="new_pass" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm-pass">Confirmar Nueva Contraseña</label>
                            <input type="password" id="confirm-pass" name="confirm_pass" required>
                        </div>
                        <button class="btn btn-save" type="submit">Guardar Cambios</button>
                    </form>
                </div>

                <!-- Selección de Cursos -->
                <div class="course-list">
                    <h3>Selección de Cursos</h3>
                    <?php
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $usuario = $_SESSION['usuario'];

                    $conn = new mysqli('localhost', 'root', '', 'login_a');
                    if ($conn->connect_error) {
                        die("Error de conexión: " . $conn->connect_error);
                    }

                    $cursos_seleccionados = [];
                    $stmt = $conn->prepare("SELECT id_curso FROM cursos_usuario WHERE usuario = ?");
                    $stmt->bind_param("s", $usuario);
                    $stmt->execute();
                    $resultSeleccionados = $stmt->get_result();
                    while ($row = $resultSeleccionados->fetch_assoc()) {
                        $cursos_seleccionados[] = $row['id_curso'];
                    }
                    $stmt->close();

                    if (count($cursos_seleccionados) > 0) {
                        echo "<div class='selected-courses'>";
                        echo "<h4>🎓 Estos son los cursos que has seleccionado:</h4>";
                        echo "<ul class='selected-course-list'>";
                        $sql_mostrar = "SELECT nombre_curso FROM cursos WHERE id_curso IN (" . implode(',', array_map('intval', $cursos_seleccionados)) . ")";
                        $result_mostrar = $conn->query($sql_mostrar);
                        while ($row = $result_mostrar->fetch_assoc()) {
                            echo "<li>" . htmlspecialchars($row['nombre_curso']) . "</li>";
                        }
                        echo "</ul></div>";
                    } else {
                        echo '<p>Selecciona los cursos que deseas ver cada vez que inicies sesión:</p>';
                        $sql = "SELECT id_curso, nombre_curso, descripcion FROM cursos";
                        $result = $conn->query($sql);
                    ?>
                        <form method="POST" action="guardar_cursos.php">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Curso</th>
                                        <th>Seleccionar</th>
                                        <th>Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()) : ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['nombre_curso']); ?></td>
                                            <td class="course-select">
                                                <input type="checkbox" name="cursos_seleccionados[]" value="<?php echo $row['id_curso']; ?>" id="curso_<?php echo $row['id_curso']; ?>">
                                                <label for="curso_<?php echo $row['id_curso']; ?>">Mostrar al iniciar</label>
                                            </td>
                                            <td>
                                                <button type="button" onclick="toggleDescripcion(<?php echo $row['id_curso']; ?>)">Ver</button>
                                            </td>
                                        </tr>
                                        <tr id="descripcion_<?php echo $row['id_curso']; ?>" style="display: none;">
                                            <td colspan="3"><?php echo nl2br(htmlspecialchars($row['descripcion'])); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                            <br>
                            <button type="submit" class="btn btn-save">Guardar Selección</button>
                        </form>
                        <script>
                            function toggleDescripcion(id) {
                                const desc = document.getElementById("descripcion_" + id);
                                desc.style.display = desc.style.display === "none" ? "table-row" : "none";
                            }
                            document.querySelectorAll('input[type="checkbox"][name="cursos_seleccionados[]"]').forEach(cb => {
                                cb.addEventListener('change', function () {
                                    const seleccionados = document.querySelectorAll('input[type="checkbox"][name="cursos_seleccionados[]"]:checked');
                                    if (seleccionados.length > 3) {
                                        alert("Solo puedes seleccionar hasta 3 cursos.");
                                        this.checked = false;
                                    }
                                });
                            });
                        </script>
                    <?php } $conn->close(); ?>
                </div>

                <!-- Información importante -->
                <div class="info-card">
                    <h3>Información Importante</h3>
                    <p>Se debe seleccionar los cursos para mostrarse cada vez que inicie sesión.</p>
                    <ul>
                        <li>Selecciona tus cursos favoritos para acceso rápido</li>
                        <li>Actualiza tu contraseña regularmente para mayor seguridad</li>
                        <li>Tu progreso se guarda automáticamente al salir</li>
                        <li>Personaliza tu experiencia de aprendizaje</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Barra lateral (Sidebar) a la derecha -->
        <div class="sidebar">
            <div class="card">
                <div class="user-profile">
                    <div class="user-avatar" style="background-image: url('logo.png');"></div>
                    <div class="user-name">
                        <?php echo htmlspecialchars($_SESSION['usuario'] ?? 'Usuario'); ?>
                    </div>
                    <div class="user-role">Estudiante</div>
                    <?php date_default_timezone_set('America/Lima'); ?>
                    <p>Último acceso: Hoy, <?php echo date("h:i A"); ?></p>
                </div>

                <div class="stats">
                    <?php
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $usuario = $_SESSION['usuario'];
                    $conn = new mysqli('localhost', 'root', '', 'login_a');
                    if ($conn->connect_error) {
                        die("Error de conexión: " . $conn->connect_error);
                    }

                    // Cursos activos
                    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM cursos_usuario WHERE usuario = ?");
                    $stmt->bind_param("s", $usuario);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $total_cursos = $row['total'];
                    $stmt->close();

                    // Datos de usuario
                    $stmt = $conn->prepare("SELECT nombre, usuario FROM usuarios WHERE usuario = ?");
                    $stmt->bind_param("s", $usuario);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $datos = $result->fetch_assoc();
                    $stmt->close();

                    $conn->close();
                    ?>

                    <div class="stat-card">
                        <h4>Cursos Activos:</h4>
                        <p><?php echo $total_cursos; ?></p>
                    </div>
                    <div class="stat-card">
                        <h4>Nombre:</h4>
                        <p><?php echo htmlspecialchars($datos['nombre']); ?></p>
                    </div>
                    <div class="stat-card">
                        <h4>Usuario:</h4>
                        <p><?php echo htmlspecialchars($datos['usuario']); ?></p>
                    </div>
                    <div class="stat-card">
                        <h4>Tareas:</h4>
                        <a href="ver_tareas.php" class="enlace-tarjeta">
                            <p>Ver tareas pendientes</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
        <footer>
            <p>Sistema Educativo Senati &copy; 2025 - Todos los derechos reservados a jhon-fer</p>
        </footer>
    </div>

    <script>
        // Basic functionality for the page
        document.addEventListener('DOMContentLoaded', function() {
            // Password change button
            document.querySelector('.btn-newpass')?.addEventListener('click', function() {
                alert('Función de cambio de contraseña activada. Complete los campos para actualizar.');
            });
            
            // Save button
            document.querySelector('.btn-save')?.addEventListener('click', function() {
                alert('Configuración guardada exitosamente!');
            });
            
            // Logout button
            document.querySelector('.btn-logout')?.addEventListener('click', function() {
                if(confirm('¿Está seguro que desea salir del sistema?')) {
                    alert('Sesión finalizada. ¡Hasta pronto!');
                    // In a real app, this would redirect to login page
                }
            });
        });
    </script>
</body>
</html>
