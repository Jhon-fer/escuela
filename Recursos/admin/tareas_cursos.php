<?php
require_once(__DIR__ . '/../../config/database.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargar cursos
$stmtCursos = $pdo->query("SELECT id_curso, nombre_curso FROM cursos");
$cursos = $stmtCursos->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Tareas por Curso</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to bottom, #720026, #a31621);
            margin: 0;
            padding: 0;
            color: #1f1f1f;
        }

        .section {
            max-width: 1000px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #720026;
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
        }

        select {
            padding: 10px;
            margin-bottom: 20px;
            width: 100%;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .tarea {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
        }

        .tarea h4 {
            margin: 0 0 5px 0;
        }

        .usuarios {
            margin-top: 10px;
            padding-left: 20px;
        }

        .usuarios li {
            list-style-type: disc;
        }

        a.btn-back {
            background-color: #720026;
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
        }

        a.btn-back:hover {
            background-color: #a31621;
        }
    </style>
</head>
<body>
    <div class="section">
        <h2>Ver tareas de los cursos</h2>
        <a href="index.php" class="btn-back">Atrás</a>

        <label for="id_curso">Selecciona un curso:</label>
        <select id="cursoSelect">
            <option value="">-- Selecciona un curso --</option>
            <?php foreach ($cursos as $curso): ?>
                <option value="<?= htmlspecialchars($curso['id_curso']) ?>">
                    <?= htmlspecialchars($curso['nombre_curso']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div id="tareasContainer"></div>
    </div>

    <script>
    document.getElementById('cursoSelect').addEventListener('change', function () {
        const cursoId = this.value;
        const container = document.getElementById('tareasContainer');
        container.innerHTML = '';

        if (cursoId) {
            fetch('obtener_tareas.php?id_curso=' + cursoId)
                .then(response => response.json())
                .then(data => {
                    if (!data.length) {
                        container.innerHTML = '<p>No hay tareas para este curso.</p>';
                        return;
                    }

                    let html = '';
                    data.forEach(tarea => {
                        html += `
                            <div class="tarea">
                                <h4>${tarea.titulo}</h4>
                                <p><strong>Fecha de entrega:</strong> ${tarea.fecha_entrega}</p>
                                <p>${tarea.descripcion}</p>
                                <p><strong>Completado por:</strong></p>
                                <ul class="usuarios">
                                    ${
                                        tarea.usuarios.length > 0
                                            ? tarea.usuarios.map(u => `<li>${u.nombre}</li>`).join('')
                                            : '<li>Nadie ha completado esta tarea.</li>'
                                    }
                                </ul>
                            </div>
                        `;
                    });

                    container.innerHTML = html;
                })
                .catch(err => {
                    container.innerHTML = '<p>Error al cargar las tareas.</p>';
                });
        }
    });
    </script>
</body>
</html>
