<?php
//Iniciamos la sesión
session_start();

// Armamos la URL base dependiendo si es HTTP o HTTPS y el puerto
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$port = ($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
$baseUrl = $protocol . $_SERVER['HTTP_HOST'] . $port;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca - Registro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Icono de pestaña -->
    <link rel="icon" href="/favicon.png" type="image/png">

    <!-- Estilos de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body style="background-image: url('<?php echo $baseUrl; ?>/imagenes/fondo-inicio.png'); background-size: cover; background-position: center; height: 100%; margin: 0;">
    <div class="container py-5" style="min-height: 100vh;">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <!-- Título principal del formulario -->
                <h2 class="text-center mb-4 py-3" style="background-color: #6f4f37; color: white;">REGÍSTRATE EN LA BIBLIOTECA</h2>

                <!-- Mensaje de éxito al registrarse -->
                <?php if (isset($_SESSION['register_success'])): ?>
                    <div class="alert alert-success text-center" role="alert">
                        <strong>¡Bienvenido!</strong> <?php echo $_SESSION['register_success']; ?>
                    </div>
                    <?php unset($_SESSION['register_success']); ?>
                <?php endif; ?>

                <!-- Mensaje de error si algo falla al registrar -->
                <?php if (isset($_SESSION['register_error'])): ?>
                    <div class="alert alert-danger text-center" role="alert">
                        <?php echo $_SESSION['register_error']; ?>
                    </div>
                    <?php unset($_SESSION['register_error']); ?>
                <?php endif; ?>

                <!-- Contenedor del formulario -->
                <div class="card shadow-lg" style="background-color: #f5f1e8; border-radius: 10px;">
                    <div class="card-body">
                        <h3 class="card-title text-center">Crear cuenta</h3>
                        <!-- Formulario de registro -->
                        <form action="<?php echo $baseUrl; ?>/controlador/proceso_registro.php" method="post" autocomplete="off" novalidate>
                            <div class="mb-3"><input type="text" class="form-control" name="nombre" placeholder="Nombre" required></div>
                            <div class="mb-3"><input type="text" class="form-control" name="apellido" placeholder="Apellido" required></div>
                            <div class="mb-3"><input type="text" class="form-control" name="nombre_usuario" placeholder="Usuario" required></div>
                            <div class="mb-3"><input type="password" class="form-control" name="contrasenia" placeholder="Contraseña" required></div>
                            <div class="mb-3"><input type="password" class="form-control" name="confirmarContrasenia" placeholder="Confirmar contraseña" required></div>
                            <!-- Botón para enviar el formulario -->
                            <div class="d-flex justify-content-center mt-3">
                                <button type="submit" class="btn" style="background-color: #6f4f37; color: white; padding: 10px 30px;">Registrarse</button>
                            </div>
                        </form>

                        <!-- Enlace para usuarios que ya tienen cuenta -->
                        <p class="text-center mt-3">¿Ya tienes una cuenta?<br>
                            <a href="<?php echo $baseUrl; ?>/index.php" style="text-decoration: underline;">Iniciar sesión</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para manejar las alertas (éxito y error) automáticamente -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alertaError = document.querySelector('.alert-danger');
            if (alertaError) {
                // Oculta la alerta de error después de 5 segundos
                setTimeout(() => alertaError.style.display = 'none', 5000);
            }

            const alertaExito = document.querySelector('.alert-success');
            if (alertaExito) {
                // Redirige al login después de mostrar el mensaje de éxito
                setTimeout(() => window.location.href = "<?php echo $baseUrl; ?>/index.php", 5000);
            }
        });
    </script>
</body>
</html>