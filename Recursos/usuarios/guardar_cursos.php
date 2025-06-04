<?php
session_start();

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: ../app/views/login.php');
    exit();
}

$usuario = $_SESSION['usuario'];

// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'login_a');
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Iniciar transacción para asegurar integridad
$conn->begin_transaction();

try {
    // Eliminar cursos previos del usuario
    $stmt = $conn->prepare("DELETE FROM cursos_usuario WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->close();

    // Insertar nuevos cursos seleccionados si existen
    if (!empty($_POST['cursos_seleccionados']) && is_array($_POST['cursos_seleccionados'])) {
        $stmt = $conn->prepare("INSERT INTO cursos_usuario (usuario, id_curso) VALUES (?, ?)");

        foreach ($_POST['cursos_seleccionados'] as $id_curso) {
            // Validar que id_curso sea numérico
            if (is_numeric($id_curso)) {
                $stmt->bind_param("si", $usuario, $id_curso);
                $stmt->execute();
            }
        }

        $stmt->close();
    }

    // Confirmar los cambios
    $conn->commit();

} catch (Exception $e) {
    // En caso de error, revertir todo
    $conn->rollback();
    error_log("Error al guardar cursos: " . $e->getMessage());
    header("Location: error.php");
    exit();
}

$conn->close();

// Redirigir al index después de guardar con indicador de éxito
header("Location: index.php?seleccion_guardada=1");
exit();
?>
