<?php
session_start();
$error = $exito = '';
$mostrar_form = false;

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=login_a;charset=utf8mb4', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT id, estado FROM usuarios WHERE token = :token");
        $stmt->execute(['token' => $token]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            $error = 'Token inválido.';
        } elseif ($usuario['estado'] !== 'pendiente') {
            $error = 'Cuenta ya activada o no válida para activación.';
        } else {
            $mostrar_form = true;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $clave = $_POST['clave'] ?? '';
                $clave_confirm = $_POST['clave_confirm'] ?? '';

                if (empty($clave) || empty($clave_confirm)) {
                    $error = 'Por favor ingresa ambas contraseñas.';
                } elseif ($clave !== $clave_confirm) {
                    $error = 'Las contraseñas no coinciden.';
                } elseif (strlen($clave) < 6) {
                    $error = 'La contraseña debe tener al menos 6 caracteres.';
                } else {
                    $claveHash = password_hash($clave, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE usuarios SET contraseña = :clave, estado = 'activo', token = NULL WHERE id = :id");
                    $stmt->execute(['clave' => $claveHash, 'id' => $usuario['id']]);
                    $exito = 'Cuenta activada correctamente. Ya puedes <a href="login.php" style="color:rgb(255, 255, 255); text-decoration: underline;">iniciar sesión</a>.';
                    $mostrar_form = false;
                }
            }
        }
    } catch (PDOException $e) {
        $error = "Error en base de datos: " . $e->getMessage();
    }
} else {
    $error = 'Token no proporcionado.';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
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
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #00c9a7;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 15px;
            font-size: 14px;
            color: #ccc;
        }

        button:hover {
            background-color: #00b89c;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
        }

        .toggle-password {
            cursor: pointer;
            font-size: 14px;
            color: #ccc;
            margin-bottom: 10px;
            display: inline-block;
        }
    </style>


</head>
<body>
    <div class="container">
        <div class="form-box">
            <h2>Activar cuenta</h2>
            <?php if ($error) echo "<p class='message' style='color: red;'>$error</p>"; ?>
            <?php if ($exito) echo "<p class='message' style='color: lightgreen;'>$exito</p>"; ?>

            <?php if ($mostrar_form): ?>
                <form method="POST">
                    <label for="clave">Contraseña:</label>
                    <input type="password" id="clave" name="clave" required>

                    <label for="clave_confirm">Confirmar Contraseña:</label>
                    <input type="password" id="clave_confirm" name="clave_confirm" required>

                    <div class="checkbox-container">
                        <input type="checkbox" id="ver_password" onclick="togglePassword()">
                        <label for="ver_password">Mostrar contraseñas</label>
                    </div>

                    <button type="submit">Activar Cuenta</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function togglePassword() {
            const clave = document.getElementById('clave');
            const claveConfirm = document.getElementById('clave_confirm');
            const mostrar = document.getElementById('ver_password').checked;

            clave.type = mostrar ? 'text' : 'password';
            claveConfirm.type = mostrar ? 'text' : 'password';
        }
    </script>

</body>
</html>
