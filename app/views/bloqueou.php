<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$error = '';
$exito = '';

// Validar que la sesión tenga el usuario activo
$usuario = $_SESSION['usuario'] ?? null;
$nombre = $_SESSION['nombre'] ?? 'Usuario';

if (!$usuario) {
    die("⚠️ Error: No hay sesión activa. Vuelve a iniciar sesión.");
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=login_a', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener datos del usuario, especialmente correo, estado y tokens
    $stmt = $pdo->prepare("SELECT correo, estado, token_activacion, token_expira, nombre FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $datos = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$datos) {
        die("Error: Usuario no encontrado en la base de datos.");
    }

    $correo = $datos['correo'];
    $estado = $datos['estado'];
    $tokenExistente = $datos['token_activacion'];
    $nombre = $datos['nombre'] ?: $nombre; // Priorizar nombre de BD si existe

    // Solo permitir envío de correo si la cuenta está bloqueada
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_correo'])) {
        if (trim(strtolower($estado)) === 'bloqueado') {
        // Verificar si existe token y si sigue vigente
        if (empty($tokenExistente) || strtotime($datos['token_expira']) < time()) {
        // Generar nuevo token y fecha de expiración (2 minuto)
        $token = bin2hex(random_bytes(32));
        $expira = date('Y-m-d H:i:s', strtotime('+120 seconds'));

        // Función para generar contraseña temporal con requisitos
        function generarPasswordTemporal($length = 10) {
            $nums = '0123456789';
            $mayus = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $especiales = '!@#$%^&*()';
            $minus = 'abcdefghijklmnopqrstuvwxyz';

            // Agregar mínimo 2 números, 2 mayúsculas y 2 caracteres especiales
            $password = '';
            for ($i = 0; $i < 2; $i++) $password .= $nums[random_int(0, strlen($nums) - 1)];
            for ($i = 0; $i < 2; $i++) $password .= $mayus[random_int(0, strlen($mayus) - 1)];
            for ($i = 0; $i < 2; $i++) $password .= $especiales[random_int(0, strlen($especiales) - 1)];

            // Completar con caracteres al azar para llegar al largo deseado
            $resto = $length - 6;
            $todos = $nums . $mayus . $especiales . $minus;
            for ($i = 0; $i < $resto; $i++) {
                $password .= $todos[random_int(0, strlen($todos) - 1)];
            }

            // Mezclar para que no estén agrupados
            return str_shuffle($password);
        }

        $passTemporal = generarPasswordTemporal(10);

        // Guardar hash de la contraseña temporal en base de datos, junto con token y expiración
        $hashPassTemporal = password_hash($passTemporal, PASSWORD_DEFAULT);

        $updateStmt = $pdo->prepare("UPDATE usuarios SET token_activacion = ?, token_expira = ?, pass_temporal = ? WHERE usuario = ?");
        $updateStmt->execute([$token, $expira, $hashPassTemporal, $usuario]);
    } else {
        $token = $tokenExistente;
        $passTemporal = 'La contraseña temporal fue enviada previamente, revisa tu correo.';
    }
    
    // Preparar enlace de cambio de contraseña temporal
    $enlaceCambio = "http://localhost/cursos/Panel/app/views/cambio_pass_temporal.php?token=$token";

    // Configurar PHPMailer para enviar correo
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'verificacion467@gmail.com';
        $mail->Password = 'gcym rwka jdle fsdw'; // ⚠️ Usa variable de entorno en producción
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('verificacion467@gmail.com', 'Soporte Técnico');
        $mail->addAddress($correo, $nombre);

        // ✅ Añadir codificación adecuada
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->isHTML(true);

        $mail->Subject = 'Cuenta Bloqueada - Contraseña Temporal y Activación';
        $mail->Body = "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; background-color: #f4f4f7; margin: 0; padding: 0; }
            .container { background-color: #ffffff; border: 1px solid #ccc; border-radius: 8px; max-width: 600px; margin: 40px auto; padding: 30px 40px; box-shadow: 0 3px 8px rgba(0,0,0,0.1); }
            .title-box { background-color: #e2e8f0; color: #2d3748; font-size: 24px; font-weight: bold; padding: 20px; border-radius: 6px; margin-bottom: 25px; text-align: center; }
            .message { font-size: 16px; color: #4a5568; margin-bottom: 20px; }
            .btn { display: inline-block; padding: 12px 28px; background-color: #3182ce; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600; margin-top: 20px; transition: background-color 0.3s ease; }
            .btn:hover { background-color: #2b6cb0; }
            .link { margin-top: 20px; font-size: 14px; word-break: break-word; color: #718096; }
            .footer { margin-top: 30px; font-size: 12px; color: #a0aec0; text-align: center; }
            .pass-temp { background-color: #edf2f7; border-radius: 6px; padding: 10px; font-weight: bold; font-family: monospace; }
            .warning { background-color: #fed7d7; color: #c53030; border-radius: 6px; padding: 10px; font-size: 14px; margin-top: 20px; font-weight: bold; }
        </style>
        </head>
        <body>
        <div class='container'>
            <div class='title-box'>Hola $nombre</div>
            <div class='message'>
                Tu cuenta ha sido bloqueada por múltiples intentos fallidos.<br><br>
                Se ha generado una contraseña temporal para que puedas reactivar tu cuenta:<br>
                <div class='pass-temp'>$passTemporal</div><br>
                Por favor, usa este enlace para cambiar tu contraseña temporal:
                <div class='warning'>
                    ⚠️ Este enlace y la contraseña temporal caducan en 2 minutos.<br>
                    Si no realizas el cambio a tiempo, deberás solicitar un nuevo código.
                </div>
            </div>
            <div style='text-align: center;'>
                <a href='$enlaceCambio' class='btn'>Cambiar Contraseña Temporal</a>
            </div>
            <div class='link'>
                O si el botón no funciona, copia y pega este enlace en tu navegador:<br>
                <a href='$enlaceCambio'>$enlaceCambio</a>
            </div>
            <div class='footer'>
                Si no solicitaste esta acción, puedes ignorar este mensaje.
            </div>
        </div>
        </body>
        </html>
        ";

        $mail->send();
        $exito = "Correo de activación enviado correctamente a <strong>$correo</strong>.";
    } catch (Exception $e) {
        $error = "Error enviando correo: {$mail->ErrorInfo}";
    }
} else {
    $error = "Tu cuenta no está bloqueada (estado actual: $estado).";
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
            margin: 0; padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .contenido {
            background-color: rgba(255, 255, 255, 0.85);
            color: #000;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            max-width: 600px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
        }
        h1 { font-size: 3rem; margin-bottom: 20px; }
        p { font-size: 1.2rem; margin-bottom: 15px; }
        a {
            color: #000;
            text-decoration: underline;
            display: inline-block;
            margin-top: 20px;
            font-size: 1rem;
        }
        button {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 12px 24px;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .mensaje-exito {
            color: green;
            margin-top: 15px;
        }
        .mensaje-error {
            color: red;
            margin-top: 15px;
        }
        .correo-info {
            margin-top: 10px;
            font-size: 1rem;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="contenido">
        <h1>Cuenta Bloqueada</h1>
        <p>Tu cuenta ha sido bloqueada debido a múltiples intentos fallidos de inicio de sesión.</p>
        <p>Contacta con el soporte de administración para desbloquear tu cuenta.</p>

        <p><strong>Estado detectado:</strong> <?php echo htmlspecialchars($estado ?? 'Desconocido'); ?></p>
        <p class="correo-info"><strong>Correo registrado:</strong> <?php echo htmlspecialchars($correo ?? 'No disponible'); ?></p>

        <?php if ($estado && trim(strtolower($estado)) === 'bloqueado'): ?>
            <form method="POST">
                <button type="submit" name="enviar_correo">Enviar correo para activar cuenta</button>
            </form>
        <?php endif; ?>

        <?php if ($exito): ?>
            <p class="mensaje-exito"><?php echo $exito; ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="mensaje-error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <br>
        <a href="login.php">Volver al login</a>
    </div>
</body>
</html>
