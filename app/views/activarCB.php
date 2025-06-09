<?php
require __DIR__ . '/../../vendor/autoload.php';

$error = '';
$exito = '';
$mostrar_form = false;
$token = $_POST['token'] ?? $_GET['token'] ?? '';

date_default_timezone_set('America/Lima');
$duracion_token_segundos = 120;

try {
    $pdo = new PDO('mysql:host=localhost;dbname=login_a;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $token) {
        $stmt = $pdo->prepare("SELECT id, token_creado FROM usuarios WHERE token = :token AND estado = 'bloqueado'");
        $stmt->execute(['token' => $token]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            if ($usuario['token_creado'] === '0000-00-00 00:00:00') {
                $error = "Token inválido. Contacta al administrador.";
            } else {
                $tokenCreado = new DateTime($usuario['token_creado']);
                $ahora = new DateTime();
                $segundos_diferencia = $ahora->getTimestamp() - $tokenCreado->getTimestamp();

                if ($segundos_diferencia <= $duracion_token_segundos) {
                    $mostrar_form = true;
                } else {
                    $error = "El enlace ha expirado.";
                }
            }
        } else {
            $error = "Token inválido o la cuenta no está bloqueada.";
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $pdo->prepare("SELECT id, token_creado FROM usuarios WHERE token = :token AND estado = 'bloqueado'");
        $stmt->execute(['token' => $token]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            $error = "Token inválido o cuenta no bloqueada.";
        } else {
            $tokenCreado = new DateTime($usuario['token_creado']);
            $ahora = new DateTime();
            $segundos_diferencia = $ahora->getTimestamp() - $tokenCreado->getTimestamp();

            if ($segundos_diferencia > $duracion_token_segundos) {
                $error = "El enlace ha expirado.";
            } else {
                $clave = $_POST['clave'] ?? '';
                $clave_confirm = $_POST['clave_confirm'] ?? '';
                $mostrar_form = true;

                if (empty($clave) || empty($clave_confirm)) {
                    $error = 'Debes ingresar y confirmar la contraseña.';
                } elseif ($clave !== $clave_confirm) {
                    $error = 'Las contraseñas no coinciden.';
                } elseif (strlen($clave) < 6) {
                    $error = 'La contraseña debe tener al menos 6 caracteres.';
                } elseif (
                    preg_match_all('/\d/', $clave) < 2 ||
                    preg_match_all('/[A-Z]/', $clave) < 2 ||
                    preg_match_all('/[\W_]/', $clave) < 2
                ) {
                    $error = 'La contraseña debe tener al menos 2 números, 2 mayúsculas y 2 caracteres especiales.';
                } else {
                    $hash = password_hash($clave, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE usuarios SET contraseña = :clave, estado = 'activo', token = NULL, token_creado = NULL WHERE id = :id");
                    $stmt->execute([
                        'clave' => $hash,
                        'id' => $usuario['id']
                    ]);

                    $exito = "Tu contraseña fue cambiada y la cuenta activada. Puedes iniciar sesión.";
                    $mostrar_form = false;
                }
            }
        }
    }
} catch (PDOException $e) {
    error_log("Error DB: " . $e->getMessage());
    $error = "Error del sistema. Intenta nuevamente.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Activar cuenta</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('fondoa.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
            color: #fff;
        }
        .container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-box {
            background: rgba(0, 0, 0, 0.5);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.6);
            max-width: 400px;
            width: 100%;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #00c9a7;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }
        .checkbox-container {
            margin: -10px 0 15px;
            display: flex;
            align-items: center;
        }
        .checkbox-container input {
            margin-right: 8px;
        }
        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #00c9a7;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #00b89c;
        }
        .message {
            text-align: center;
            margin-bottom: 15px;
        }
        #requisitos {
            background-color: rgba(255, 255, 255, 0.07);
            padding: 15px;
            margin-top: 20px;
            border-radius: 10px;
            color: #ddd;
            font-size: 14px;
            text-align: left;
        }
        #requisitos strong {
            display: block;
            margin-bottom: 8px;
            text-align: center;
            color: #eee;
        }
        #requisitos ul {
            padding-left: 20px;
            margin: 0;
            list-style: none;
        }
        #requisitos li {
            margin: 6px 0;
            transition: color 0.3s ease;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-box">
        <h2>Activar cuenta bloqueada</h2>

        <?php if ($error): ?>
            <p class="message" style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if ($exito): ?>
            <p class="message" style="color: lightgreen;"><?= htmlspecialchars($exito) ?></p>
            <p class="message"><a href="login.php" style="color: #00c9a7; text-decoration: underline;">Ir al inicio de sesión</a></p>
        <?php endif; ?>

        <?php if ($mostrar_form): ?>
            <form method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <label>Nueva contraseña:</label>
                <input type="password" name="clave" id="clave" required>

                <label>Confirmar contraseña:</label>
                <input type="password" name="clave_confirm" id="clave_confirm" required>

                <div class="checkbox-container">
                    <input type="checkbox" id="mostrar_contrasena" onclick="togglePassword()">
                    <label for="mostrar_contrasena">Mostrar contraseña</label>
                </div>

                <button type="submit">Cambiar contraseña</button>

                <div id="requisitos">
                    <strong>La contraseña debe contener:</strong>
                    <ul>
                        <li id="num">🔴 Al menos 2 números</li>
                        <li id="mayus">🔴 Al menos 2 letras mayúsculas</li>
                        <li id="esp">🔴 Al menos 2 caracteres especiales (!@#$...)</li>
                        <li id="long">🔴 Mínimo 6 caracteres</li>
                    </ul>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php if ($mostrar_form): ?>
<script>
    function togglePassword() {
        const campos = [document.getElementById('clave'), document.getElementById('clave_confirm')];
        campos.forEach(input => {
            input.type = document.getElementById('mostrar_contrasena').checked ? 'text' : 'password';
        });
    }

    const claveInput = document.getElementById('clave');
    claveInput.addEventListener('input', function validarClave() {
        const val = claveInput.value;
        const num = (val.match(/\d/g) || []).length;
        const mayus = (val.match(/[A-Z]/g) || []).length;
        const esp = (val.match(/[\W_]/g) || []).length;
        const long = val.length;

        document.getElementById('num').textContent = (num >= 2 ? '🟢' : '🔴') + " Al menos 2 números";
        document.getElementById('mayus').textContent = (mayus >= 2 ? '🟢' : '🔴') + " Al menos 2 letras mayúsculas";
        document.getElementById('esp').textContent = (esp >= 2 ? '🟢' : '🔴') + " Al menos 2 caracteres especiales (!@#$...)";
        document.getElementById('long').textContent = (long >= 6 ? '🟢' : '🔴') + " Mínimo 6 caracteres";
    });
</script>
<?php endif; ?>
</body>
</html>