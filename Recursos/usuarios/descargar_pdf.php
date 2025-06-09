<?php
session_start();

// Verificar sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: ../app/views/login.php");
    exit();
}

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "login_a");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$usuario = $_SESSION['usuario'];

// Obtener ID del usuario
$stmt_id = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ?");
$stmt_id->bind_param("s", $usuario);
$stmt_id->execute();
$result_id = $stmt_id->get_result();
$usuario_data = $result_id->fetch_assoc();
$usuario_id = $usuario_data['id'];
$stmt_id->close();

// Obtener cursos del usuario
$sql_cursos = "SELECT id_curso FROM cursos_usuario WHERE usuario = ?";
$stmt_cursos = $conn->prepare($sql_cursos);
$stmt_cursos->bind_param("s", $usuario);
$stmt_cursos->execute();
$result_cursos = $stmt_cursos->get_result();

$cursos_ids = [];
while ($row = $result_cursos->fetch_assoc()) {
    $cursos_ids[] = $row['id_curso'];
}
$stmt_cursos->close();

// Obtener tareas relacionadas
$tareas = [];
if (!empty($cursos_ids)) {
    $placeholders = implode(',', array_fill(0, count($cursos_ids), '?'));
    $types = str_repeat('i', count($cursos_ids));

    $sql_tareas = "
        SELECT t.*, c.nombre_curso, 
               IF(tt.id IS NULL, 0, 1) AS terminada
        FROM tareas t
        JOIN cursos c ON t.id_curso = c.id_curso
        LEFT JOIN tareas_terminadas tt ON tt.id_tarea = t.id AND tt.usuario_id = ?
        WHERE t.id_curso IN ($placeholders)
        ORDER BY t.fecha_entrega ASC
    ";

    $stmt_tareas = $conn->prepare($sql_tareas);
    $params = array_merge([$usuario_id], $cursos_ids);
    $stmt_tareas->bind_param("i" . $types, ...$params);
    $stmt_tareas->execute();
    $result_tareas = $stmt_tareas->get_result();

    while ($row = $result_tareas->fetch_assoc()) {
        $tareas[] = $row;
    }

    $stmt_tareas->close();
}

$conn->close();

// Incluir la clase FPDF (ruta correcta basada en tu estructura)
require_once __DIR__ . '/fpdf/fpdf.php';

// Crear el PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Tareas de tus Cursos', 0, 1, 'C');
$pdf->Ln(10);

// Encabezados
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Curso', 1);
$pdf->Cell(50, 10, 'Titulo', 1);
$pdf->Cell(40, 10, 'Entrega', 1);
$pdf->Cell(40, 10, 'Estado', 1);
$pdf->Ln();

// Contenido de tareas
$pdf->SetFont('Arial', '', 10);
foreach ($tareas as $tarea) {
    $pdf->Cell(50, 10, utf8_decode($tarea['nombre_curso']), 1);
    $pdf->Cell(50, 10, utf8_decode($tarea['titulo']), 1);
    $pdf->Cell(40, 10, date("d/m/Y", strtotime($tarea['fecha_entrega'])), 1);
    $estado = $tarea['terminada'] ? 'Completada' : 'Pendiente';
    $pdf->Cell(40, 10, $estado, 1);
    $pdf->Ln();
}

// Salida del PDF: descarga directa
$nombre_archivo = 'tareas_' . date('Ymd_His') . '.pdf';
$pdf->Output('D', $nombre_archivo);
