<?php
session_start();

// Reiniciar los intentos
$_SESSION['intentos'] = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Demasiados intentos</title>
    <meta http-equiv="refresh" content="60;url=login.php?reset=1"> <!-- Redirección tras 60 segundos -->
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: Arial, sans-serif;
            color: white;
            text-shadow: 2px 2px 5px black;
        }

        #video-fondo {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        .contenido {
            position: relative;
            z-index: 1;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
        }

        h1 {
            font-size: 3rem;
        }

        audio {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Video de fondo -->
    <video id="video-fondo" autoplay muted loop>
        <source src="video/videol.mp4" type="video/mp4">
        Tu navegador no soporta videos HTML5.
    </video>

    <!-- Contenido encima del video -->
    <div class="contenido">
        <h1>Demasiados intentos fallidos</h1>
        <p>Relájate viendo este paisaje mientras lo intentas más tarde...</p>
        <audio controls autoplay loop>
            <source src="audio/musica.mp3" type="audio/mpeg">
            Tu navegador no soporta audio HTML5.
        </audio>
    </div>

    <p id="contador">Volverás al inicio en 60 segundos...</p>

    <script>
        let segundos = 60;
        const contador = document.getElementById('contador');

        setInterval(() => {
            segundos--;
            contador.textContent = `Volverás al inicio en ${segundos} segundos...`;
        }, 1000);
    </script>
</body>
</html>
