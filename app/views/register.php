<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';

$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $correo = $_POST['correo'] ?? '';

    if (!empty($nombre) && !empty($usuario) && !empty($correo)) {
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $error = 'Correo electrónico no válido.';
        } else {
            try {
                $pdo = new PDO('mysql:host=localhost;dbname=login_a;charset=utf8', 'root', '');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Verificar si el usuario o correo ya existen
                $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = :usuario OR correo = :correo");
                $stmt->execute(['usuario' => $usuario, 'correo' => $correo]);
                if ($stmt->fetch()) {
                    $error = 'El usuario o correo ya están registrados.';
                } else {
                    // Generar token único
                    $token = bin2hex(random_bytes(32));

                    // Insertar usuario sin contraseña y estado pendiente
                    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, usuario, correo, estado, token) VALUES (:nombre, :usuario, :correo, 'pendiente', :token)");
                    $stmt->execute([
                        'nombre' => $nombre,
                        'usuario' => $usuario,
                        'correo' => $correo,
                        'token' => $token
                    ]);

                    // Preparar y enviar correo con PHPMailer
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'verificacion467@gmail.com';
                        $mail->Password = 'gcym rwka jdle fsdw';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        $mail->setFrom('jhonfernandogomezquispe@gmail.com', 'Tu App');
                        $mail->addAddress($correo, $nombre);

                        $mail->isHTML(true);
                        $mail->Subject = 'Activa tu cuenta';
                        $enlace = "http://localhost/cursos/Panel/app/views/activar.php?token=$token";
                        $mail->Body = "Hola $nombre,<br><br>Para activar tu cuenta haz clic en el siguiente enlace:<br><a href='$enlace'>$enlace</a><br><br>Gracias.";

                        $mail->send();
                        $exito = 'Registro exitoso. Revisa tu correo para activar la cuenta.';
                    } catch (Exception $e) {
                        $error = "Error enviando correo: {$mail->ErrorInfo}";
                    }
                }
            } catch (PDOException $e) {
                $error = 'Error de base de datos: ' . $e->getMessage();
            }
        }
    } else {
        $error = 'Por favor completa todos los campos.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrarse</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('paisajer.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        form p {
            margin-top: 15px;
            margin-bottom: 10px;
            font-size: 14px;
            text-align: center;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Registrarse</h2>

        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <?php if (!empty($exito)) : ?>
            <p class="success"><?= $exito ?></p>
            <p style="text-align: center;">Ya puedes cerrar esta pestaña.</p>
        <?php endif; ?>

        <?php if (empty($exito)) : ?>
            <form method="POST">
                <label>Nombre:
                    <input type="text" name="nombre" required>
                </label><br><br>

                <label>Usuario:
                    <input type="text" name="usuario" required>
                </label><br><br>

                <label>Correo electrónico:
                    <input type="email" name="correo" required>
                </label><br><br>

                <p><a href="login.php">¿Ya tienes cuenta? Inicia sesión</a></p>

                <button type="submit">Registrarse</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
