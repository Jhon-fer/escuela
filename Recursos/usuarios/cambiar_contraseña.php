<?php
session_start();

// Verificar que el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header('Location: ../app/views/login.php');
    exit();
}

$usuario = $_SESSION['usuario'];
$current_pass = $_POST['current_pass'] ?? '';
$new_pass = $_POST['new_pass'] ?? '';
$confirm_pass = $_POST['confirm_pass'] ?? '';

// Validar que los campos no estén vacíos
if (empty($current_pass) || empty($new_pass) || empty($confirm_pass)) {
    die("Todos los campos son obligatorios.");
}

// Verificar que la nueva contraseña y su confirmación coincidan
if ($new_pass !== $confirm_pass) {
    die("La nueva contraseña y su confirmación no coinciden.");
}

// Conectar a la base de datos
$conn = new mysqli('localhost', 'root', '', 'login_a');
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener la contraseña actual desde la BD
$stmt = $conn->prepare("SELECT contraseña FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$stmt->bind_result($hash_actual);
$stmt->fetch();
$stmt->close();

// Verificar la contraseña actual
if (!password_verify($current_pass, $hash_actual)) {
    die("La contraseña actual es incorrecta.");
}

// Hashear la nueva contraseña
$nuevo_hash = password_hash($new_pass, PASSWORD_BCRYPT);

// Actualizar la contraseña en la BD
$stmt = $conn->prepare("UPDATE usuarios SET contraseña = ? WHERE usuario = ?");
$stmt->bind_param("ss", $nuevo_hash, $usuario);
$stmt->execute();
$stmt->close();
$conn->close();

echo "Contraseña actualizada correctamente.";
// Redirigir si deseas
header("Location: index.php");
exit();
?>
