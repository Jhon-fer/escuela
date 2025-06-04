<?php
$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $clave = $_POST['clave'] ?? '';

    if (!empty($nombre) && !empty($usuario) && !empty($clave)) {
        $conexion = new mysqli('localhost', 'root', '', 'login_a');

        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        // Verificar si el usuario ya existe
        $stmt = $conexion->prepare("SELECT id_admin FROM admin WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = 'El usuario ya existe.';
        } else {
            $stmt->close();

            // Hashear la contraseña antes de guardar
            $claveHash = password_hash($clave, PASSWORD_DEFAULT);

            // Insertar nuevo usuario en la tabla admin
            $stmt = $conexion->prepare("INSERT INTO admin (nombre, usuario, contraseña) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nombre, $usuario, $claveHash);

            if ($stmt->execute()) {
                $exito = 'Administrador creado correctamente. <a href="login.php">Iniciar sesión</a>';
            } else {
                $error = 'Error al registrar el administrador.';
            }
        }

        $stmt->close();
        $conexion->close();
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
            background: url('paisajer1.jpg') no-repeat center center fixed;
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
        input[type="password"] {
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
        <?php if (!empty($exito)) echo "<p class='success'>$exito</p>"; ?>

        <form method="POST">
            <label>Nombre:
                <input type="text" name="nombre" required>
            </label>

            <label>Usuario:
                <input type="text" name="usuario" required>
            </label>

            <label>Contraseña:
                <input type="password" name="clave" required>
            </label>

            <p><a href="login.php">¿Ya tienes cuenta? Inicia sesión</a></p>

            <button type="submit">Registrarse</button>
        </form>
    </div>
</body>
</html>