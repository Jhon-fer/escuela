<?php
require_once(__DIR__ . '/../../config/database.php');

// Mostrar errores (en desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargar cursos
$stmtCursos = $pdo->query("SELECT id_curso, nombre_curso FROM cursos");
$cursos = $stmtCursos->fetchAll();

// Procesar envío
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_curso = intval($_POST['id_curso'] ?? 0);
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $fecha_entrega = $_POST['fecha_entrega'] ?? '';

    if ($id_curso > 0 && $titulo !== '' && $descripcion !== '' && $fecha_entrega !== '') {
        try {
            $stmt = $pdo->prepare("INSERT INTO tareas (id_curso, titulo, descripcion, fecha_entrega) VALUES (?, ?, ?, ?)");
            $stmt->execute([$id_curso, $titulo, $descripcion, $fecha_entrega]);
            $mensaje = "<p style='color: green;'>✅ Tarea guardada exitosamente.</p>";
        } catch (PDOException $e) {
            $mensaje = "<p style='color: red;'>❌ Error al guardar la tarea: " . $e->getMessage() . "</p>";
        }
    } else {
        $mensaje = "<p style='color: red;'>⚠️ Todos los campos obligatorios deben estar completos.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Tarea</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to bottom, #720026, #a31621);
            margin: 0;
            padding: 0;
            color: #1f1f1f;
        }

        .section {
            max-width: 1000px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #720026;
            text-align: center;
            font-size: 28px;
            margin-bottom: 10px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        a.btn-back {
            background-color: #720026;
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
        }

        a.btn-back:hover {
            background-color: #a31621;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="date"],
        textarea,
        select {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px;
            background-color: #720026;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #a31621;
        }
    </style>
</head>
<body>
    <div class="section">
        <h2>Agregar Tarea a Curso</h2>
        <a href="index.php" class="btn-back">Atrás</a>

        <?= $mensaje ?>

        <form method="POST" action="agregar_tarea.php">
            <label for="id_curso">Curso:</label>
            <select name="id_curso" required>
                <option value="">-- Selecciona un curso --</option>
                <?php foreach ($cursos as $curso): ?>
                    <option value="<?= htmlspecialchars($curso['id_curso']) ?>">
                        <?= htmlspecialchars($curso['nombre_curso']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="text" name="titulo" placeholder="Título de la tarea" required>
            <textarea name="descripcion" placeholder="Descripción de la tarea" required></textarea>
            <input type="date" name="fecha_entrega" required>

            <button type="submit">Guardar Tarea</button>
        </form>
    </div>
</body>
</html>
