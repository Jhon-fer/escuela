<?php
require_once(__DIR__ . '/../../config/database.php');

// Mostrar errores (solo en desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Procesar envío
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_curso = trim($_POST['nombre_curso'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    if ($nombre_curso !== '' && $descripcion !== '') {
        try {
            $stmt = $pdo->prepare("INSERT INTO cursos (nombre_curso, descripcion) VALUES (?, ?)");
            $stmt->execute([$nombre_curso, $descripcion]);
            $mensaje = "<p style='color: green;'>✅ Curso agregado exitosamente.</p>";
        } catch (PDOException $e) {
            $mensaje = "<p style='color: red;'>❌ Error al agregar el curso: " . $e->getMessage() . "</p>";
        }
    } else {
        $mensaje = "<p style='color: red;'>⚠️ Todos los campos son obligatorios.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Curso</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to bottom, #720026, #a31621);
            margin: 0;
            padding: 0;
            color: #1f1f1f;
        }

        .section {
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #720026;
            text-align: center;
            margin-bottom: 20px;
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

        input[type="text"],
        textarea {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        button {
            padding: 10px;
            background-color: #720026;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #a31621;
        }
    </style>
</head>
<body>
    <div class="section">
        <h2>Agregar Curso</h2>
        <a href="index.php" class="btn-back">Atrás</a>

        <?= $mensaje ?>

        <form method="POST" action="agregar_cursos.php">
            <input type="text" name="nombre_curso" placeholder="Nombre del curso" required>
            <textarea name="descripcion" placeholder="Descripción del curso" required></textarea>
            <button type="submit">Guardar Curso</button>
        </form>
    </div>
</body>
</html>
