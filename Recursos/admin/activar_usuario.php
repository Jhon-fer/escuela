<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $conexion = new mysqli("localhost", "root", "", "login_a");

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    $sql = "UPDATE usuarios SET estado='activo' WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php"); // Cambia esto al nombre de tu panel si es diferente
        exit;
    } else {
        echo "Error al activar el usuario.";
    }

    $stmt->close();
    $conexion->close();
} else {
    echo "Solicitud inválida.";
}
