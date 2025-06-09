<?php
session_start();
$token = $_GET['token'] ?? null;
if (!$token) die("Token no válido.");

try {
    $pdo = new PDO('mysql:host=localhost;dbname=login_a', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Buscar usuario con token válido y no expirado
    $stmt = $pdo->prepare("SELECT usuario, pass_temporal, token_expira FROM usuarios WHERE token_activacion = ?");
    $stmt->execute([$token]);
    $usuarioData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuarioData || strtotime($usuarioData['token_expira']) < time()) {
        die("Token inválido o expirado.");
    }

    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $claveIngresada = $_POST['clave_temporal'] ?? '';

        if (isset($usuarioData['pass_temporal']) && password_verify($claveIngresada, $usuarioData['pass_temporal'])) {
            // ✅ Copiar token_activacion a token, establecer token_creado
            $stmt = $pdo->prepare("UPDATE usuarios SET token = token_activacion, token_creado = NOW() WHERE usuario = ?");
            $stmt->execute([$usuarioData['usuario']]);

            // ✅ Redirigir automáticamente a activarCB con el token
            header("Location: activarCB.php?token=" . urlencode($token));
            exit();
        } else {
            $error = "Contraseña temporal incorrecta.";
        }
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ingresar Contraseña Temporal</title>
    <style>
        /* Fondo con imagen */
        body {
            background: url('https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=1350&q=80') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
        }
        .container {
            background: rgba(0,0,0,0.7);
            max-width: 400px;
            margin: 80px auto;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 0 10px #000;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        input[type=password],
        input[type=text] {
            width: 100%;
            padding: 12px;
            margin: 10px 0 15px;
            border-radius: 6px;
            border: none;
            font-size: 16px;
        }
        button {
            background-color: #3182ce;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
            width: 100%;
        }
        button:hover {
            background-color: #2b6cb0;
        }
        #requisitos {
            color: #ccc;
            font-size: 14px;
            margin-top: 10px;
            text-align: left;
        }
        #requisitos ul {
            padding-left: 20px;
        }
        #requisitos li {
            margin-bottom: 6px;
        }
        .error {
            color: #ff6b6b;
            font-weight: bold;
            margin-bottom: 15px;
        }
        label {
            float: left;
            font-weight: bold;
            margin-bottom: 6px;
        }
        .checkbox-container {
            text-align: left;
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Ingresa tu contraseña temporal</h2>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post" action="">
            <label for="clave_temporal">Contraseña temporal:</label>
            <input type="password" id="clave_temporal" name="clave_temporal" placeholder="Contraseña temporal" required>

            <div class="checkbox-container">
                <label>
                    <input type="checkbox" onclick="togglePassword()"> Mostrar contraseña
                </label>
            </div>

            <button type="submit">Confirmar</button>
        </form>

        <div id="requisitos">
            <strong>La contraseña debe contener:</strong>
            <ul>
                <li id="num">🔴 Al menos 2 números</li>
                <li id="mayus">🔴 Al menos 2 letras mayúsculas</li>
                <li id="esp">🔴 Al menos 2 caracteres especiales (!@#$...)</li>
                <li id="long">🔴 Mínimo 6 caracteres</li>
            </ul>
        </div>
    </div>

    <script>
        const claveInput = document.getElementById('clave_temporal');

        claveInput.addEventListener('input', validarClave);

        function validarClave() {
            const val = claveInput.value;
            const num = (val.match(/\d/g) || []).length;
            const mayus = (val.match(/[A-Z]/g) || []).length;
            const esp = (val.match(/[\W_]/g) || []).length;
            const long = val.length;

            document.getElementById('num').style.color = num >= 2 ? 'lightgreen' : 'red';
            document.getElementById('mayus').style.color = mayus >= 2 ? 'lightgreen' : 'red';
            document.getElementById('esp').style.color = esp >= 2 ? 'lightgreen' : 'red';
            document.getElementById('long').style.color = long >= 6 ? 'lightgreen' : 'red';
        }

        function togglePassword() {
            const input = document.getElementById("clave_temporal");
            input.type = input.type === "password" ? "text" : "password";
        }
    </script>
</body>
</html>