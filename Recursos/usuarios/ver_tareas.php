<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../app/views/login.php");
    exit();
}

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "login_a");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$usuario = $_SESSION['usuario'];

// Obtener el ID real del usuario
$stmt_id = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ?");
$stmt_id->bind_param("s", $usuario);
$stmt_id->execute();
$result_id = $stmt_id->get_result();
$usuario_data = $result_id->fetch_assoc();
$usuario_id = $usuario_data['id'];
$stmt_id->close();


// Si se envió el formulario para marcar tarea como terminada
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_tarea'])) {
    $id_tarea = intval($_POST['id_tarea']);

    // Verificar si ya fue marcada como terminada
    $check = $conn->prepare("SELECT 1 FROM tareas_terminadas WHERE usuario_id = ? AND id_tarea = ?");
    $check->bind_param("ii", $usuario_id, $id_tarea);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO tareas_terminadas (usuario_id, id_tarea) VALUES (?, ?)");
        $stmt->bind_param("ii", $usuario_id, $id_tarea);
        $stmt->execute();
        $stmt->close();
    }
    $check->close();
}

// Obtener los cursos seleccionados por el usuario
$sql_cursos = "SELECT id_curso FROM cursos_usuario WHERE usuario = ?";
$stmt_cursos = $conn->prepare($sql_cursos);
$stmt_cursos->bind_param("s", $usuario);
$stmt_cursos->execute();
$result_cursos = $stmt_cursos->get_result();

$cursos_ids = [];
while ($row = $result_cursos->fetch_assoc()) {
    $cursos_ids[] = $row['id_curso'];
}
$stmt_cursos->close();

$tareas = [];

if (!empty($cursos_ids)) {
    $placeholders = implode(',', array_fill(0, count($cursos_ids), '?'));
    $types = str_repeat('i', count($cursos_ids));

    $sql_tareas = "
        SELECT t.*, c.nombre_curso, 
            IF(tt.id IS NULL, 0, 1) AS terminada
        FROM tareas t
        JOIN cursos c ON t.id_curso = c.id_curso
        LEFT JOIN tareas_terminadas tt ON tt.id_tarea = t.id AND tt.usuario_id = ?
        WHERE t.id_curso IN ($placeholders)
        ORDER BY t.fecha_entrega ASC
    ";

    $stmt_tareas = $conn->prepare($sql_tareas);
    $params = array_merge([$usuario_id], $cursos_ids);
    $stmt_tareas->bind_param("i" . $types, ...$params);
    $stmt_tareas->execute();
    $result_tareas = $stmt_tareas->get_result();

    while ($row = $result_tareas->fetch_assoc()) {
        $tareas[] = $row;
    }

    $stmt_tareas->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tareas Pendientes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #1a2a6c;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 15px;
            border-bottom: 1px solid #ccc;
            text-align: center;
        }
        th {
            background: #1a2a6c;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .no-tareas {
            text-align: center;
            margin-top: 40px;
            font-size: 18px;
            color: #555;
        }
        .volver {
            display: block;
            margin: 20px auto;
            text-align: center;
            text-decoration: none;
            background-color: #1a2a6c;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            width: fit-content;
        }
        button {
            background-color: #28a745;
            border: none;
            padding: 8px 12px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .completada {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Tareas de tus Cursos Seleccionados</h1>

    <?php if (count($tareas) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Curso</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Fecha de Entrega</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tareas as $tarea): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tarea['nombre_curso']); ?></td>
                        <td><?php echo htmlspecialchars($tarea['titulo']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($tarea['descripcion'])); ?></td>
                        <td><?php echo date("d/m/Y", strtotime($tarea['fecha_entrega'])); ?></td>
                        <td>
                            <?php if ($tarea['terminada']): ?>
                                <span class="completada">Completada</span>
                            <?php else: ?>
                                <form method="post" style="margin: 0;">
                                    <input type="hidden" name="id_tarea" value="<?= $tarea['id'] ?>">
                                    <button type="submit">Marcar como terminada</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-tareas">No hay tareas pendientes para tus cursos seleccionados.</p>
    <?php endif; ?>

    <a href="index.php" class="volver">← Volver al Panel</a>
</body>
</html>
