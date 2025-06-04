<?php
require_once(__DIR__ . '/../../config/database.php');

header('Content-Type: application/json');

$id_curso = intval($_GET['id_curso'] ?? 0);

if ($id_curso <= 0) {
    echo json_encode([]);
    exit;
}

try {
    // Obtener tareas del curso
    $stmt = $pdo->prepare("SELECT id AS id_tarea, titulo, descripcion, fecha_entrega FROM tareas WHERE id_curso = ?");
    $stmt->execute([$id_curso]);
    $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$tareas) {
        echo json_encode([]);
        exit;
    }

    // Obtener usuarios que completaron tareas en este curso
    $idsTareas = array_column($tareas, 'id_tarea');
    $placeholders = implode(',', array_fill(0, count($idsTareas), '?'));

    $stmtUsuarios = $pdo->prepare("
        SELECT tt.id_tarea, u.nombre
        FROM tareas_terminadas tt
        JOIN usuarios u ON tt.usuario_id = u.id
        WHERE tt.id_tarea IN ($placeholders)
    ");
    $stmtUsuarios->execute($idsTareas);
    $usuariosPorTarea = [];

    foreach ($stmtUsuarios->fetchAll(PDO::FETCH_ASSOC) as $fila) {
        $usuariosPorTarea[$fila['id_tarea']][] = ['nombre' => $fila['nombre']];
    }

    // Asignar usuarios a cada tarea
    foreach ($tareas as &$tarea) {
        $tarea['usuarios'] = $usuariosPorTarea[$tarea['id_tarea']] ?? [];
    }

    echo json_encode($tareas);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
