<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    public function login() {
        session_start();

        // Verificamos que el formulario haya sido enviado
        if (isset($_POST['usuario']) && isset($_POST['clave'])) {
            $usuario = $_POST['usuario'];
            $clave = $_POST['clave'];

            $userModel = new User();
            $resultado = $userModel->validar($usuario, $clave);

            if ($resultado) {
                // Guardamos en sesión los datos
                $_SESSION['usuario'] = $resultado['usuario'];
                $_SESSION['rol'] = $resultado['rol'];
                $_SESSION['nombre'] = $resultado['nombre'] ?? null;
                $_SESSION['id'] = $resultado['id'];

                // Redirigimos según el rol
                if ($resultado['rol'] === 'admin') {
                    header('Location: ../Recursos/admin/index.php');
                } else {
                    header('Location: ../Recursos/usuarios/index.php');
                }
                exit();
            } else {
                // Credenciales inválidas
                // Podrías guardar el error en sesión o mostrarlo directamente
                $_SESSION['error'] = 'Usuario o contraseña incorrectos.';
                header('Location: ../login.php'); // redirige al login para intentar otra vez
                exit();
            }
        } else {
            // No se enviaron datos, redirigir al login
            header('Location: ../login.php');
            exit();
        }
    }
}