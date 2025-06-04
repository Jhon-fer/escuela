<?php
session_start();

// Verifica si existen las variables de sesión
$nombre = $_SESSION['nombre'] ?? null;
$usuario = $_SESSION['usuario'] ?? null;

// Conexión a la base de datos con PDO
try {
    $pdo = new PDO('mysql:host=localhost;dbname=login_a', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener correo y estado si se conoce el usuario
    $correo = null;
    if ($usuario) {
        $stmt = $pdo->prepare("SELECT correo, estado FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($datos) {
            $correo = $datos['correo'];
            $estado = $datos['estado'];
        }
    }
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuenta Bloqueada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('bloqueu.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .contenido {
            background-color: rgba(255, 255, 255, 0.7);
            color: #000;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            max-width: 600px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        a {
            color: #000;
            text-decoration: underline;
            display: inline-block;
            margin-top: 20px;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="contenido">
        <h1>Cuenta Bloqueada</h1>
        <p>Tu cuenta ha sido bloqueada debido a múltiples intentos fallidos de inicio de sesión.</p>
        <p>Contacta con el soporte de administración para desbloquear tu cuenta.</p>
        <br>
        <a href="login.php">Volver al login</a>
    </div>
</body>
</html>
