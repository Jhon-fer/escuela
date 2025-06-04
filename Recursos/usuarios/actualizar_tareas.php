<?php
session_start();
require '../../conexion.php'; // O el archivo correcto

if (isset($_POST['tareas']) && is_array($_POST['tareas']) && isset($_SESSION['usuario'])) {
    $usuario_id = $_SESSION['usuario'];

    $stmt = $conn->prepare("INSERT IGNORE INTO tareas_terminadas (id_usuario, id_tarea) VALUES (?, ?)");
    foreach ($_POST['tareas'] as $id_tarea) {
        $stmt->bind_param("ii", $usuario_id, $id_tarea);
        $stmt->execute();
    }
    $stmt->close();
}

header("Location: index.php"); // Redirige al panel nuevamente
exit;
