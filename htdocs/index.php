<?php
session_start(); // Iniciamos la sesión

// Definir $baseUrl para construir URLs absolutas
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$port = ($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
$baseUrl = $protocol . $_SERVER['HTTP_HOST'] . $port;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Inicio</title>
    <link rel="icon" href="/favicon.png" type="image/png">

    <!-- Se incluye la hoja de estilos de Bootstrap desde un CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body style="background-image: url('<?php echo $baseUrl; ?>/imagenes/fondo-inicio.png'); background-size: cover; background-position: center center; background-repeat: no-repeat; height: 100%; margin: 0;"> 
    <!-- Contenedor principal con imagen de fondo -->
    <div class="container py-5" style="min-height: 100vh;"> 
        <div class="row justify-content-center">

            <?php
            // Mensajes de sesión
            if (isset($_GET['logout_success']) && $_GET['logout_success'] === 'true') {
                echo '<div class="alert alert-info text-center" role="alert">Has cerrado sesión correctamente.</div>';
            }
            // Mensaje de acceso denegado
            if (isset($_SESSION['access_denied'])) { 
                echo '<div class="alert alert-warning text-center" role="alert">' . $_SESSION['access_denied'] . '</div>';
                unset($_SESSION['access_denied']);
            }
            // Mensaje de cuenta eliminada con éxito
            if (isset($_SESSION['delete_account_success'])) {
                echo '<div class="alert alert-success text-center" role="alert">' . $_SESSION['delete_account_success'] . '</div>';
                unset($_SESSION['delete_account_success']);
            }
            ?>

            <!-- Sección de inicio de sesión -->
            <div class="col-lg-6 col-md-8 col-sm-10">
                <h2 class="text-center mb-4 py-3" style="background-color: #6f4f37; color: white;">ACCEDE A TU BIBLIOTECA</h2>

                <!-- Mensaje de error de inicio de sesión, si existe -->
                <?php if (isset($_SESSION['login_error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['login_error']; ?>
                    </div>
                    <?php unset($_SESSION['login_error']); ?>
                <?php endif; ?>

                <!-- Tarjeta de Bootstrap con el formulario de inicio de sesión -->
                <div class="card shadow-lg" style="background-color: #f5f1e8; border-radius: 10px;">
                    <div class="card-body">
                        <h3 class="card-title text-center" style="font-family: 'Roboto', sans-serif; color: #000000;">Iniciar sesión</h3>
                        <br>
                        <!-- Formulario de inicio de sesión -->
                        <form id="formularioLogin" action="<?php echo $baseUrl; ?>/controlador/proceso_login.php" method="post" autocomplete="off" novalidate>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required autocomplete="nombre_usuario" placeholder="Usuario">
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" id="contrasenia" name="contrasenia" required autocomplete="new-password" placeholder="Contraseña">
                            </div>
                            <!-- Botón de envío centrado -->
                            <div class="d-flex justify-content-center mt-3">
                                <button type="submit" class="btn" style="background-color: #6f4f37; color: white; padding: 10px 30px;" name="login">Ingresar</button>
                            </div>
                        </form>
                        <!-- Enlace para el registro de nuevos usuarios -->
                        <p class="text-center mt-3" style="color: #000000;">
                            ¿No tienes una cuenta?<br>
                            <a href="<?php echo $baseUrl; ?>/vista/auth/registro.php" style="text-decoration: underline;">Registrarse</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Ocultar automáticamente las alertas de error e información después de 5 segundos
        document.addEventListener('DOMContentLoaded', function () {
            const alertaError = document.querySelector('.alert-danger');
            if (alertaError) {
                setTimeout(function () {
                    alertaError.style.display = 'none';
                }, 5000);
            }
            const alertaInfo = document.querySelector('.alert-info');
            if (alertaInfo) {
                setTimeout(function () {
                    alertaInfo.style.display = 'none';
                }, 5000);
            }
        });
    </script>
</body>
</html>
