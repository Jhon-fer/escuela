<?php
$idUsuario = null;
$mensaje = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $idUsuario = intval($_POST['id']);
    $conexion = new mysqli("localhost", "root", "", "login_a");

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];

        if ($accion === 'fija') {
            $nuevaPass = password_hash("123456", PASSWORD_DEFAULT);
        } elseif ($accion === 'personalizada') {
            $nuevaIngresada = trim($_POST['nueva_pass'] ?? '');
            if (!empty($nuevaIngresada)) {
                $nuevaPass = password_hash($nuevaIngresada, PASSWORD_DEFAULT);
            } else {
                $error = "La nueva contraseña no puede estar vacía.";
            }
        }

        if (!isset($error) && isset($nuevaPass)) {
            $stmt = $conexion->prepare("UPDATE usuarios SET contraseña = ? WHERE id = ?");
            $stmt->bind_param("si", $nuevaPass, $idUsuario);

            if ($stmt->execute()) {
                $mensaje = "Contraseña actualizada correctamente.";
            } else {
                $error = "Error al actualizar la contraseña.";
            }

            $stmt->close();
        }

        $conexion->close();
    }
} elseif (!isset($_POST['id'])) {
    // Redirección si se intenta acceder sin ID
    header('Location: ../panel_admin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #720026, #a31621);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            background: #fff;
            color: #1f1f1f;
            border-radius: 16px;
            padding: 30px;
            width: 400px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }
        h2 {
            margin-top: 0;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        input[type="password"], button {
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ccc;
        }
        button {
            background: #d4e4f7;
            color: #1f1f1f;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #bcd6f0;
        }
        .mensaje {
            text-align: center;
            font-weight: bold;
            margin-top: 10px;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #0077cc;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="card">
    <h2>Restablecer Contraseña</h2>

    <?php if ($mensaje): ?>
        <div class="mensaje" style="color: green;"><?php echo htmlspecialchars($mensaje); ?></div>
        <a href="/cursos/Panel/Recursos/admin/index.php">Volver al menú</a>
    <?php elseif ($error): ?>
        <div class="mensaje" style="color: red;"><?php echo htmlspecialchars($error); ?></div>
        <a href="javascript:history.back()">Volver</a>
    <?php else: ?>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($idUsuario); ?>">

            <button type="submit" name="accion" value="fija">Restablecer a "123456"</button>

            <hr>

            <label for="nueva_pass">Nueva contraseña personalizada:</label>
            <input type="password" id="nueva_pass" name="nueva_pass" placeholder="Escribe una nueva contraseña">
            <button type="submit" name="accion" value="personalizada">Guardar nueva contraseña</button>

            <a href="/cursos/Panel/Recursos/admin/index.php">Cancelar</a>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
