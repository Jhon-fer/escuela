<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';

$error = '';
$exito = '';
$nombre = '';
$usuario = '';
$correo = '';

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

                $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = :usuario OR correo = :correo");
                $stmt->execute(['usuario' => $usuario, 'correo' => $correo]);

                if ($stmt->fetch()) {
                    $error = 'El usuario o correo ya están registrados.';

                    // Sugerencias
                    $sugerencias = [];
                    $base = preg_replace('/[^a-z0-9]/i', '', strtolower(explode(' ', $nombre)[0]));
                    for ($i = 0; $i < 3; $i++) {
                        $sugerido = $base . rand(100, 999);
                        $check = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
                        $check->execute([$sugerido]);
                        if (!$check->fetch()) {
                            $sugerencias[] = $sugerido;
                        }
                    }

                    if (!empty($sugerencias)) {
                        $error .= '<br>Algunas sugerencias de nombre de usuario disponibles:<ul>';
                        foreach ($sugerencias as $sug) {
                            $error .= "<li><strong>$sug</strong></li>";
                        }
                        $error .= '</ul>';
                    }

                } else {
                    $token = bin2hex(random_bytes(32));

                    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, usuario, correo, estado, token, token_creado) VALUES (:nombre, :usuario, :correo, 'pendiente', :token, NOW())");
                    $stmt->execute([
                        'nombre' => $nombre,
                        'usuario' => $usuario,
                        'correo' => $correo,
                        'token' => $token
                    ]);

                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'verificacion467@gmail.com';
                        $mail->Password = 'gcym rwka jdle fsdw';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        // Remitente corregido
                        $mail->setFrom('verificacion467@gmail.com', 'Activación de cuenta');

                        // Destinatario
                        $mail->addAddress($correo, $nombre);

                        $mail->isHTML(true);
                        $mail->Subject = 'Activa tu cuenta';
                        $enlace = "http://localhost/cursos/Panel/app/views/activar.php?token=$token";
                        $mail->Body = "
                        <!DOCTYPE html>
                        <html lang='es'>
                        <head>
                        <meta charset='UTF-8'>

                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                background-color: #f6f8fa;
                                margin: 0;
                                padding: 0;
                            }
                            .container {
                                background-color: #ffffff;
                                border: 1px solid #d0d7de;
                                border-radius: 8px;
                                max-width: 600px;
                                margin: 40px auto;
                                padding: 30px 40px;
                                box-shadow: 0 2px 6px rgba(0,0,0,0.05);
                            }
                            .title-box {
                                background-color: #ddf4ff;
                                color: #0969da;
                                font-size: 22px;
                                font-weight: bold;
                                padding: 15px 20px;
                                border-radius: 6px;
                                margin-bottom: 25px;
                                text-align: center;
                            }
                            .message {
                                font-size: 16px;
                                color: #24292f;
                                margin-bottom: 20px;
                            }
                            .btn {
                                display: inline-block;
                                padding: 12px 24px;
                                background-color: #b4f0be;
                                color: #1a1a1a;
                                text-decoration: none;
                                border-radius: 8px;
                                font-weight: bold;
                                margin-top: 20px;
                            }
                            .link {
                                margin-top: 20px;
                                font-size: 14px;
                                word-break: break-word;
                                color: #57606a;
                            }
                            .footer {
                                margin-top: 30px;
                                font-size: 12px;
                                color: #6e7781;
                                text-align: center;
                            }
                        </style>

                        </head>
                        <body>
                        <div class='container'>
                            <div class='title-box'>Hola $nombre</div>
                            <div class='message'>
                                Gracias por registrarte. Para activar tu cuenta, haz clic en el siguiente botón:
                            </div>
                            <div style='text-align: center;'>
                                <a href='$enlace' class='btn'>Activar cuenta</a>
                            </div>
                            <div class='link'>
                                O si el botón no funciona, copia y pega este enlace en tu navegador:<br>
                                <a href='$enlace'>$enlace</a>
                            </div>
                            <div class='message' style='margin-top: 20px;'>
                                ⚠️ Este enlace es válido por <strong>2 minutos</strong>. Si no activas tu cuenta en ese tiempo, deberás registrarte nuevamente.
                            </div>
                            <div class='footer'>
                                Si no solicitaste esta cuenta, puedes ignorar este mensaje.
                            </div>
                        </div>
                        </body>
                        </html>
                        ";
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
                    <input type="text" name="nombre" required value="<?= htmlspecialchars($nombre) ?>">
                </label><br><br>

                <label>Usuario:
                    <input type="text" name="usuario" required value="<?= htmlspecialchars($usuario) ?>">
                </label><br><br>

                <label>Correo electrónico:
                    <input type="email" name="correo" required value="<?= htmlspecialchars($correo) ?>">
                </label><br><br>

                <p><a href="login.php">¿Ya tienes cuenta? Inicia sesión</a></p>

                <button type="submit">Registrarse</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
