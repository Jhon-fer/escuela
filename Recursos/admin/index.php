<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../app/views/login.php');
    exit();
}

$conexion = new mysqli("localhost", "root", "", "login_a");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Guardar curso si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_curso']) && isset($_POST['descripcion'])) {
    $nombre = $_POST['nombre_curso'];
    $descripcion = $_POST['descripcion'];
    $stmt = $conexion->prepare("INSERT INTO cursos (nombre_curso, descripcion, usuarios) VALUES (?, ?, NULL)");
    $stmt->bind_param("ss", $nombre, $descripcion);
    $stmt->execute();
    $stmt->close();
}

// Obtener cursos
$cursos = [];
$result = $conexion->query("SELECT * FROM cursos");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cursos[] = $row;
    }
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
// Solo para producción
ini_set('display_errors', 0);
error_reporting(0);

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel Admin</title>
  
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
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(to bottom, #720026, #a31621);
        margin: 0;
        padding: 0;
        color: #1f1f1f;
        }

        .panel {
        max-width: 1000px;
        margin: 30px auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        padding: 20px 30px;
        }

        .header {
        font-size: 22px;
        font-weight: bold;
        color: #1f1f1f;
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
        }

        .topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        }

        .topbar input {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        width: 200px;
        }

        .topbar button {
        background: #e0ecff;
        color: #1f1f1f;
        border: none;
        padding: 10px 18px;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.3s ease;
        }
        .topbar button:hover {
        background: #b6d4ff;
        }

        .main {
        display: flex;
        justify-content: space-between;
        gap: 30px;
        flex-wrap: wrap;
        }

        .section {
        flex: 1;
        min-width: 300px;
        background: #fafafa;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #ddd;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .section h4 {
        margin-top: 0;
        font-style: italic;
        font-size: 16px;
        margin-bottom: 10px;
        }

        .section input, 
        .section textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        font-style: italic;
        border: 1px solid #ccc;
        border-radius: 10px;
        }

        .section button {
        background: #d4e4f7;
        color: #1f1f1f;
        border: none;
        padding: 10px 18px;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.3s ease;
        }
        .section button:hover {
        background: #bcd6f0;
        }

        .danger {
        background: #f8d7da;
        color: #721c24;
        border: none;
        padding: 10px 18px;
        border-radius: 10px;
        margin-bottom: 10px;
        cursor: pointer;
        display: block;
        transition: background 0.3s ease;
        }
        .danger:hover {
        background: #f1b0b7;
        }

        ul {
        list-style: none;
        padding-left: 15px;
        margin: 0;
        }

        ul li {
        padding: 6px 0;
        border-bottom: 1px solid #eee;
        }
  </style>

</head>
<body>
  <div class="panel">
    <div class="header">Panel del Administrador</div>
    
    <div class="topbar">
        <span style="font-weight: bold; font-size: 16px;">
            Bienvenido: <?php echo htmlspecialchars($_SESSION['usuario'] ?? 'Invitado'); ?>
        </span>
        <a href="/cursos/Panel/public/logout.php" class="logout-btn">Cerrar sesión</a>
    </div>

    <!-- Usuarios -->
    <div class="section">
        <h4>Gestión de Usuarios</h4>
        <strong>Usuarios Registrados</strong>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #eee;">
                    <th style="padding: 8px; border: 1px solid #ccc;">ID</th>
                    <th style="padding: 8px; border: 1px solid #ccc;">Nombre</th>
                    <th style="padding: 8px; border: 1px solid #ccc;">Usuario</th>
                    <th style="padding: 8px; border: 1px solid #ccc;">Contraseña</th>
                    <th style="padding: 8px; border: 1px solid #ccc;">Restablecer contraseña</th>
                    <th style="padding: 8px; border: 1px solid #ccc;">Activar usuarios</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $conexion = new mysqli("localhost", "root", "", "login_a");
                $consultaUsuarios = "SELECT * FROM usuarios";
                $resultadoUsuarios = $conexion->query($consultaUsuarios);

                if ($resultadoUsuarios->num_rows > 0) {
                    while ($fila = $resultadoUsuarios->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td style='padding: 8px; border: 1px solid #ccc;'>" . $fila['id'] . "</td>";
                        echo "<td style='padding: 8px; border: 1px solid #ccc;'>" . htmlspecialchars($fila['nombre']) . "</td>";
                        echo "<td style='padding: 8px; border: 1px solid #ccc;'>" . htmlspecialchars($fila['usuario']) . "</td>";
                        echo "<td style='padding: 8px; border: 1px solid #ccc; font-size: 12px;'>" . htmlspecialchars($fila['contraseña']) . "</td>";

                        // Botón para restablecer contraseña
                        echo "<td style='padding: 8px; border: 1px solid #ccc; text-align: center;'>
                                <form action='restablecer_pass.php' method='post'>
                                    <input type='hidden' name='id' value='" . $fila['id'] . "'>
                                    <button class='danger' type='submit'>Restablecer</button>
                                </form>
                            </td>";

                        // Botón para activar usuario bloqueado
                        echo "<td style='padding: 8px; border: 1px solid #ccc; text-align: center;'>";
                        if ($fila['estado'] === 'bloqueado') {
                            echo "<form action='activar_usuario.php' method='post'>
                                    <input type='hidden' name='id' value='" . $fila['id'] . "'>
                                    <button type='submit'>Activar</button>
                                </form>";
                        } else {
                            echo "Activo";
                        }
                        echo "</td>";

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align: center;'>No hay usuarios registrados.</td></tr>";
                }

                $conexion->close();
                ?>
            </tbody>
        </table>
    </div>
    
    <!-- Agregar Curso -->
    <div class="section">
        <h4>Agregar Curso</h4>
        <a href="agregar_cursos.php" class="btn">Agregar nueva tarea</a>
    </div>

    <!-- Cursos Registrados -->
    <div class="section">
        <h4>Cursos Registrados</h4>
        <?php foreach ($cursos as $curso): ?>
            <div style="margin-bottom: 10px;">
                <strong><?php echo htmlspecialchars($curso['nombre_curso'] ?? ''); ?></strong> -
                <?php echo htmlspecialchars($curso['descripcion'] ?? ''); ?>
                <form method="POST" action="eliminar_curso.php" style="display:inline;">
                    <input type="hidden" name="id_curso" value="<?php echo $curso['id_curso']; ?>">
                    <button type="submit">Eliminar</button>
                </form>
            </div>
        <?php endforeach; ?>
        <h4>Tareas de los cursos</h4>
        <a href="tareas_cursos.php" class="btn">Ver tareas de los cursos</a>
    </div>

    <!-- Agregar Tarea -->
    <div class="section">
        <h4>Agregar Tarea a Curso</h4>
        <a href="agregar_tarea.php" class="btn">Agregar nueva tarea</a>
    </div>
</body>
</html>