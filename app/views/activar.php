<?php
require __DIR__ . '/../../vendor/autoload.php';

$error = '';
$exito = '';
$mostrar_form = false;
$token = $_GET['token'] ?? '';

// Zona horaria
date_default_timezone_set('America/Lima');

// Duración del token en segundos (ej. 2 minutos)
$duracion_token_segundos = 120;

try {
    $pdo = new PDO('mysql:host=localhost;dbname=login_a;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // --- VALIDACIÓN POR MÉTODO GET (al abrir el enlace) ---
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $token) {
        $stmt = $pdo->prepare("SELECT id, token_creado FROM usuarios WHERE token = :token AND estado = 'pendiente'");
        $stmt->execute(['token' => $token]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            if (empty($usuario['token_creado'])) {
                $error = "El enlace no tiene una fecha válida.";
            } else {
                $tokenCreado = new DateTime($usuario['token_creado']);
                $ahora = new DateTime();
                $segundos_diferencia = $ahora->getTimestamp() - $tokenCreado->getTimestamp();

                if ($segundos_diferencia <= $duracion_token_segundos) {
                    $mostrar_form = true;
                } else {
                    // ✅ Eliminar usuario si el enlace expiró
                    $del = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
                    $del->execute(['id' => $usuario['id']]);
                    $error = "El enlace de activación ha expirado. Tu registro ha sido eliminado.";
                }
            }
        } else {
            $error = "Token no válido o cuenta ya activada.";
        }
    }

    // --- VALIDACIÓN POR MÉTODO POST (al enviar la contraseña) ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $pdo->prepare("SELECT id, token_creado FROM usuarios WHERE token = :token AND estado = 'pendiente'");
        $stmt->execute(['token' => $token]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            $error = "Token inválido o cuenta ya activada.";
        } else {
            $tokenCreado = new DateTime($usuario['token_creado']);
            $ahora = new DateTime();
            $segundos_diferencia = $ahora->getTimestamp() - $tokenCreado->getTimestamp();

            if ($segundos_diferencia > $duracion_token_segundos) {
                // ✅ Eliminar usuario si el enlace expiró
                $del = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
                $del->execute(['id' => $usuario['id']]);
                $error = "El enlace de activación ha expirado. Tu registro ha sido eliminado.";
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
                    $error = 'La contraseña debe contener al menos 2 números, 2 mayúsculas y 2 caracteres especiales.';
                } else {
                    $hash = password_hash($clave, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE usuarios SET contraseña = :clave, estado = 'activo', token = NULL, token_creado = NULL WHERE id = :id");
                    $stmt->execute([
                        'clave' => $hash,
                        'id' => $usuario['id']
                    ]);
                    $exito = "Cuenta activada con éxito. Ya puedes iniciar sesión.";
                    $mostrar_form = false;
                }
            }
        }
    }

} catch (PDOException $e) {
    $error = "Error en la base de datos: " . $e->getMessage();
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
        }

        .container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .form-box {
            background: rgba(0, 0, 0, 0.65);
            padding: 35px 30px;
            border-radius: 20px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.7);
            max-width: 420px;
            width: 100%;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #00c9a7;
            font-weight: bold;
        }

        label {
            display: block;
            margin: 12px 0 5px;
            font-weight: 500;
            color: #e0e0e0;
        }

        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            margin-bottom: 10px;
            background-color: #f2f2f2;
            color: #333;
            font-size: 15px;
            outline: none;
        }

        input[type="password"]:focus {
            background-color: #ffffff;
            box-shadow: 0 0 5px #00c9a7;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #00c9a7;
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #00b89c;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
            font-size: 14px;
            color: #ccc;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            font-size: 15px;
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
            <h2>Activar cuenta</h2>
            <?php if ($error) echo "<p class='message' style='color: red;'>$error</p>"; ?>
            <?php if ($exito): ?>
                <p class="message" style="color: lightgreen;"><?= htmlspecialchars($exito) ?></p>
                <p class="message"><a href="login.php" style="color: #00c9a7; text-decoration: underline;">Ir al inicio de sesión</a></p>
            <?php endif; ?>
            <?php if ($mostrar_form): ?>
                <form method="POST" novalidate>
                    <label for="clave">Contraseña:</label>
                    <input type="password" id="clave" name="clave" required>

                    <label for="clave_confirm">Confirmar Contraseña:</label>
                    <input type="password" id="clave_confirm" name="clave_confirm" required>
                    <p id="mensaje-coincidencia" class="message"></p>

                    <div class="checkbox-container">
                        <input type="checkbox" id="ver_password" onclick="togglePassword()">
                        <label for="ver_password">Mostrar contraseñas</label>
                    </div>

                    <button type="submit">Activar Cuenta</button>
                </form>

                <!-- Mover los requisitos aquí -->
                <div id="requisitos">
                    <strong>La contraseña debe contener:</strong>
                    <ul>
                        <li id="num" data-text="Al menos 2 números">🔴 Al menos 2 números</li>
                        <li id="mayus" data-text="Al menos 2 letras mayúsculas">🔴 Al menos 2 letras mayúsculas</li>
                        <li id="esp" data-text="Al menos 2 caracteres especiales (!@#$...)">🔴 Al menos 2 caracteres especiales (!@#$...)</li>
                        <li id="long" data-text="Mínimo 6 caracteres">🔴 Mínimo 6 caracteres</li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const claveInput = document.getElementById('clave');
        const claveConfirmInput = document.getElementById('clave_confirm');
        const activarBtn = document.querySelector('button[type="submit"]');

        claveInput.addEventListener('input', validarTodo);
        claveConfirmInput.addEventListener('input', validarTodo);

        function validarTodo() {
            validarClave();
            verificarCoincidencia();
            validarBoton();
        }

        function validarClave() {
            const val = claveInput.value;
            const num = (val.match(/\d/g) || []).length;
            const mayus = (val.match(/[A-Z]/g) || []).length;
            const esp = (val.match(/[\W_]/g) || []).length;
            const long = val.length;

            actualizarRequisito('num', num >= 2);
            actualizarRequisito('mayus', mayus >= 2);
            actualizarRequisito('esp', esp >= 2);
            actualizarRequisito('long', long >= 6);
        }

        function actualizarRequisito(id, cumple) {
            const item = document.getElementById(id);
            item.innerHTML = (cumple ? '🟢' : '🔴') + " " + item.dataset.text;
            item.style.color = cumple ? '#00ff88' : '#ff4d4d';
        }

        function verificarCoincidencia() {
            const mensaje = document.getElementById('mensaje-coincidencia');
            if (!claveConfirmInput.value) {
                mensaje.textContent = '';
                return;
            }

            if (claveInput.value === claveConfirmInput.value) {
                mensaje.textContent = '✅ Las contraseñas coinciden';
                mensaje.style.color = '#00ff88';
            } else {
                mensaje.textContent = '❌ Las contraseñas no coinciden';
                mensaje.style.color = '#ff4d4d';
            }
        }

        function validarBoton() {
            const val = claveInput.value;
            const num = (val.match(/\d/g) || []).length >= 2;
            const mayus = (val.match(/[A-Z]/g) || []).length >= 2;
            const esp = (val.match(/[\W_]/g) || []).length >= 2;
            const long = val.length >= 6;
            const coinciden = claveInput.value === claveConfirmInput.value;

            activarBtn.disabled = !(num && mayus && esp && long && coinciden);
            activarBtn.style.opacity = activarBtn.disabled ? '0.5' : '1';
            activarBtn.style.cursor = activarBtn.disabled ? 'not-allowed' : 'pointer';
        }

        function togglePassword() {
            const tipo = claveInput.type === "password" ? "text" : "password";
            claveInput.type = tipo;
            claveConfirmInput.type = tipo;
        }

        // Desactivar botón inicialmente
        window.addEventListener('DOMContentLoaded', () => {
            validarTodo();
        });
    </script>
</body>
</html>
