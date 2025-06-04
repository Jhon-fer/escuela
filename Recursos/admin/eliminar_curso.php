<?php
require_once(__DIR__ . '/../../config/database.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_curso']) && is_numeric($_POST['id_curso'])) {
    $id_curso = intval($_POST['id_curso']);

    try {
        // Verificar si existe el curso
        $stmtVerificar = $pdo->prepare("SELECT * FROM cursos WHERE id_curso = ?");
        $stmtVerificar->execute([$id_curso]);
        $curso = $stmtVerificar->fetch();

        if ($curso) {
            // Eliminar tareas asociadas
            $stmtTareas = $pdo->prepare("DELETE FROM tareas WHERE id_curso = ?");
            $stmtTareas->execute([$id_curso]);

            // Eliminar asociaciones usuario-curso
            $stmtRelaciones = $pdo->prepare("DELETE FROM cursos_usuario WHERE id_curso = ?");
            $stmtRelaciones->execute([$id_curso]);

            // Finalmente eliminar el curso
            $stmtEliminar = $pdo->prepare("DELETE FROM cursos WHERE id_curso = ?");
            $stmtEliminar->execute([$id_curso]);

            $mensaje = "<p style='color: green;'>✅ Curso y datos relacionados eliminados correctamente.</p>";
        } else {
            $mensaje = "<p style='color: red;'>❌ El curso no existe.</p>";
        }
    } catch (PDOException $e) {
        $mensaje = "<p style='color: red;'>❌ Error al eliminar el curso: " . $e->getMessage() . "</p>";
    }
} else {
    $mensaje = "<p style='color: red;'>⚠️ ID de curso inválido.</p>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Curso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
            padding: 50px;
        }
        .message {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border: 2px solid #ccc;
            border-radius: 10px;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            background-color: #720026;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
        }
        a:hover {
            background-color: #a31621;
        }
    </style>
</head>
<body>
    <div class="message">
        <?= $mensaje ?>
        <a href="index.php">Volver</a>
    </div>
</body>
</html>
