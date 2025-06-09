<?php
// Conectar a la base de datos
$pdo = new PDO('mysql:host=localhost;dbname=login_a', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Obtener token
$token = $_GET['token'] ?? '';

if ($token) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE token_activacion = ?");
    $stmt->execute([$token]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && strtotime($usuario['token_expira']) > time()) {
        // Activar cuenta
        $stmt = $pdo->prepare("UPDATE usuarios SET estado = 'activo', token_activacion = NULL, token_expira = NULL WHERE id = ?");
        $stmt->execute([$usuario['id']]);
        echo "Cuenta reactivada correctamente. <a href='login.php'>Iniciar sesión</a>";
    } else {
        echo "El enlace es inválido o ha expirado.";
    }
} else {
    echo "Token no proporcionado.";
}
?>
