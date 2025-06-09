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
        $stmt = $conexion->prepare("SELECT id, contraseña, estado, nombre FROM usuarios WHERE usuario = ?");
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
                $_SESSION['usuario'] = $usuario;
                $_SESSION['nombre'] = $usuarioData['nombre'];
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
            
            // NO destruir la sesión para que bloqueou.php tenga acceso
            // // Solo resetea intentos y ciclos para el siguiente login
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
  <meta charset="UTF-8" />
  <title>Login</title>
  <link rel="short icon" href="iconosenati.png" />
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: url('senati.png') no-repeat center center fixed;
      background-size: cover;
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-container {
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(15px);
      border-radius: 20px;
      padding: 40px 30px;
      width: 100%;
      max-width: 400px;
      border: 4px solid;
      animation: rgb-border 5s linear infinite;
      color: white;
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    }

    h2 {
      text-align: center;
      color: #fff;
      margin-bottom: 30px;
    }

    form label {
      display: block;
      font-size: 14px;
      margin-bottom: 6px;
      color: #ddd;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.1);
      color: white;
      transition: 0.3s;
    }

    input[type="text"]:focus,
    input[type="password"]:focus {
      border-color: #00f0ff;
      box-shadow: 0 0 6px 2px rgba(0, 240, 255, 0.6);
      outline: none;
    }

    .show-password {
      display: flex;
      align-items: center;
      font-size: 13px;
      color: #ccc;
      margin-bottom: 15px;
    }

    button {
      width: 100%;
      padding: 12px;
      background: #00f0ff;
      border: none;
      color: #000;
      font-weight: bold;
      border-radius: 25px;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background: #00b4cc;
    }

    p {
      text-align: center;
      font-size: 13px;
      color: #ccc;
      margin-top: 15px;
    }

    a {
      color: #00f0ff;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    .error {
      color: #ff6b6b;
      text-align: center;
      margin-bottom: 10px;
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
      <label for="usuario">Usuario</label>
      <input type="text" name="usuario" id="usuario" required />

      <label for="clave">Contraseña</label>
      <input type="password" name="clave" id="clave" required />

      <div class="show-password">
        <input type="checkbox" id="toggleClave" onclick="togglePassword()" />
        <label for="toggleClave">Mostrar contraseña</label>
      </div>

      <button type="submit">Ingresar</button>

      <p>¿No tienes cuenta como usuario? <a href="register.php">Regístrate</a></p>
      <p>¿Eres admin? <a href="registera.php">Regístrate como Admin</a></p>
    </form>
  </div>

  <script>
    function togglePassword() {
      const input = document.getElementById("clave");
      input.type = input.type === "password" ? "text" : "password";
    }
  </script>
  
</body>
</html>
