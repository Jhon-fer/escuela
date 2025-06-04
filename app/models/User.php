<?php
require_once __DIR__ . '/../../config/database.php';

class User {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    /**
     * Valida usuario y contraseña.
     * Retorna un array con ['rol' => 'admin'|'usuario', 'nombre' => ..., 'usuario' => ...] si es correcto,
     * o false si no se encontró o la contraseña es incorrecta.
     */
    public function validar(string $usuario, string $clave) {
        // Buscar en admin
        $sql = "SELECT id_admin, usuario, contraseña, nombre FROM admin WHERE usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$usuario]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($clave, $admin['contraseña'])) {
            return [
                'rol' => 'admin',
                'nombre' => $admin['nombre'],
                'usuario' => $admin['usuario'],
                'id' => $admin['id_admin'],
            ];
        }

        // Buscar en usuarios
        $sql = "SELECT id, usuario, contraseña FROM usuarios WHERE usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$usuario]);
        $user = $stmt->fetch();

        if ($user && password_verify($clave, $user['contraseña'])) {
            return [
                'rol' => 'usuario',
                'usuario' => $user['usuario'],
                'id' => $user['id'],
                // Puedes agregar más campos si los necesitas
            ];
        }

        // Si no encontró usuario o contraseña incorrecta
        return false;
    }
}