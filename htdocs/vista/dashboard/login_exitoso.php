<?php
    // Iniciamos la sesión.
    session_start();

    // Construimos la URL base (protocolo, host y, si corresponde, puerto).
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$port = ($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
$baseUrl = $protocol . $_SERVER['HTTP_HOST'] . $port;

    // Verificamos que el usuario exista; de lo contrario, redirige a index.php.
    if (!isset($_SESSION['detsuid'])) {
        header('location: ../index.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Ingreso exitoso</title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo $baseUrl; ?>/favicon.png" type="image/png">

    <!-- Hoja de estilos de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        /* Fondo base con el tono solicitado */
        body {
            background-color: #f5f1e8;
        }
        /* Tarjeta de éxito personalizada */
        .tarjeta-exito {
            background-color: #ffffff; /* Contraste con el fondo */
            border: 2px solid #f5f1e8; /* Usa el tono base para el borde */
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            padding: 2rem;
            animation: aparecer 1s ease-in-out;
        }
        /* Animación de aparición */
        @keyframes aparecer {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        /* Estilos para el título y el mensaje */
        .titulo-exito {
            font-size: 2rem;
            font-weight: bold;
            color: #4e342e; /* Tono oscuro en armonía con #f5f1e8 */
        }
        .mensaje-exito {
            font-size: 1.2rem;
            color: #4e342e;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-6">
                <div class="tarjeta-exito text-center">
                    <h2 class="titulo-exito">Ingreso exitoso</h2>
                    <p class="mensaje-exito">¡Has iniciado sesión correctamente!</p>
                    <img src="<?php echo $baseUrl; ?>/imagenes/book1.png" alt="Imagen de éxito" class="img-fluid mt-3" style="max-width: 200px;">
                </div>
            </div>
        </div>
    </div>

    <!-- Redirige al dashboard después de 3 segundos -->
    <script>
        setTimeout(function () {
            window.location.href = '<?php echo $baseUrl; ?>/vista/dashboard/dashboard.php';
        }, 3000);
    </script>
</body>
</html>
