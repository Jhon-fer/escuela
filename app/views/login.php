<?php
session_start();

if (!isset($_SESSION['intentos'])) {
    $_SESSION['intentos'] = 0;
}

if (!isset($_SESSION['ciclos'])) {
    $_SESSION['ciclos'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $clave = $_POST['clave'] ?? '';

    $conexion = new mysqli('localhost', 'root', '', 'login_a');
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Primero intentamos en admin
    $stmt = $conexion->prepare("SELECT id_admin, contraseña, nombre FROM admin WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $usuarioExiste = false;
    $idUsuarioBloquear = null;
    $tabla = null;

    if ($resultado && $resultado->num_rows === 1) {
        $admin = $resultado->fetch_assoc();
        $usuarioExiste = true;
        $idUsuarioBloquear = $admin['id_admin'];
        $tabla = 'admin';

        if (password_verify($clave, $admin['contraseña'])) {
            $_SESSION['usuario'] = $usuario;
            $_SESSION['nombre'] = $admin['nombre'];
            $_SESSION['rol'] = 'admin';
            $_SESSION['intentos'] = 0;
            $_SESSION['ciclos'] = 0;
            $stmt->close();
            $conexion->close();
            header('Location: /cursos/Panel/Recursos/admin/index.php');
            exit();
        }
    } else {
        $stmt->close();
        // Intentar en usuarios (con estado)
        $stmt = $conexion->prepare("SELECT id, contraseña, estado FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows === 1) {
            $usuarioData = $resultado->fetch_assoc();
            $usuarioExiste = true;
            $idUsuarioBloquear = $usuarioData['id'];
            $tabla = 'usuarios';

            // Verificar si está bloqueado antes de validar contraseña
            if ($usuarioData['estado'] === 'bloqueado') {
                // Cuenta bloqueada, redirigir a la página de bloqueo
                $stmt->close();
                $conexion->close();
                header('Location: bloqueou.php');
                exit();
            }

            if (password_verify($clave, $usuarioData['contraseña'])) {
                $_SESSION['usuario'] = $usuario;
                $_SESSION['rol'] = 'usuario';
                $_SESSION['intentos'] = 0;
                $_SESSION['ciclos'] = 0;
                $stmt->close();
                $conexion->close();
                header('Location: /cursos/Panel/Recursos/usuarios/index.php');
                exit();
            }
        }
    }

    // Si llegó aquí, password o usuario incorrecto
    $_SESSION['intentos']++;

    if ($_SESSION['intentos'] >= 3) {
        $_SESSION['ciclos']++;
        $_SESSION['intentos'] = 0;

        if ($_SESSION['ciclos'] >= 3) {
            // Bloquear cuenta en BD solo si el usuario existe
            if ($usuarioExiste && $tabla && $idUsuarioBloquear) {
                if ($tabla === 'admin') {
                    $upd = $conexion->prepare("UPDATE admin SET estado = 'bloqueado' WHERE id_admin = ?");
                } else {
                    $upd = $conexion->prepare("UPDATE usuarios SET estado = 'bloqueado' WHERE id = ?");
                }
                $upd->bind_param("i", $idUsuarioBloquear);
                $upd->execute();
                $upd->close();
            }

            $_SESSION['intentos'] = 0;
            $_SESSION['ciclos'] = 0;

            $stmt->close();
            $conexion->close();

            header('Location: bloqueou.php');
            exit();
        } else {
            // Mostrar pantallab.php después de 3 intentos fallidos
            $stmt->close();
            $conexion->close();
            header('Location: pantallab.php');
            exit();
        }
    } else {
        // Mostrar error genérico para menos de 3 intentos fallidos
        $error = $usuarioExiste ? 'Contraseña incorrecta.' : 'Usuario no encontrado.';
    }

    $stmt->close();
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="short icon" href="iconosenati.png">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('senati.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #b8fff9;
            padding: 30px;
            border-radius: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 350px;
            animation: rgb-border 5s linear infinite;
            border: 4px solid;
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: red;
        }
        form label {
            display: block;
            margin-bottom: 15px;
            font-size: 14px;
            color: #555;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            margin-top: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        p {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
        }
        @keyframes rgb-border {
            0%   { border-color: rgb(255, 0, 0); }
            25%  { border-color: rgb(0, 255, 0); }
            50%  { border-color: rgb(0, 0, 255); }
            75%  { border-color: rgb(255, 255, 0); }
            100% { border-color: rgb(255, 0, 0); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Iniciar sesión</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <?php
        if (!empty($error) && $_SESSION['intentos'] < 3) {
            $restantes = 3 - $_SESSION['intentos'];
            echo "<p class='error'>Te quedan $restantes intento(s).</p>";
        }
        ?>
        <form method="POST">
            <label>Usuario:
                <input type="text" name="usuario" required>
            </label>
            <label>Contraseña:
                <input type="password" name="clave" required>
            </label>
            <p>¿No tienes cuenta como usuario? <a href="register.php">Regístrate como Usuario aqui</a></p>
            <p>¿No tienes cuenta como admin? <a href="registera.php">Registrate como Admin aqui</a></p>
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
