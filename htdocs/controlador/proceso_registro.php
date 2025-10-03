<?php
//Iniciamos la sesión
session_start();

// Archivo de conexión a la base de datos
include __DIR__ . '/../config/connectiondb.php';

// Comprobar conexión
if (!$con) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Construir URL base
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$port = ($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
$baseUrl = $protocol . $_SERVER['HTTP_HOST'] . $port;

// Comprobar si se envió el formulario
if (isset($_POST['nombre'], $_POST['apellido'], $_POST['nombre_usuario'], $_POST['contrasenia'], $_POST['confirmarContrasenia'])) {
    $errores = [];

    // Escapar datos para evitar inyección SQL
    $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($con, $_POST['apellido']);
    $nombre_usuario = mysqli_real_escape_string($con, $_POST['nombre_usuario']);
    $contrasenia = $_POST['contrasenia'];
    $confirmarContrasenia = $_POST['confirmarContrasenia'];

    // Validaciones
    if (empty($nombre) || empty($apellido) || empty($nombre_usuario) || empty($contrasenia) || empty($confirmarContrasenia)) {
        $errores[] = "Por favor, complete todos los campos.";
    }

    if (!preg_match("/^[a-zA-Z]+$/", $nombre)) {
        $errores[] = "El nombre solo puede contener letras.";
    }

    if (!preg_match("/^[a-zA-Z\s]+$/", $apellido)) {
        $errores[] = "El apellido solo puede contener letras.";
    }

    if (!preg_match("/^[a-zA-Z0-9_]+$/", $nombre_usuario)) {
        $errores[] = "El nombre de usuario solo puede contener letras, números y guiones bajos.";
    }

    if ($contrasenia !== $confirmarContrasenia) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    if (!preg_match("/^[a-zA-Z0-9!@#$%^&*(),.?\":{}|<>]+$/", $contrasenia)) {
        $errores[] = "La contraseña contiene caracteres inválidos.";
    }

    // Si hay errores, redirigir con errores
    if (!empty($errores)) {
        $_SESSION['register_error'] = implode('<br>', $errores);
        header('Location: ' . $baseUrl . '/vista/auth/registro.php');
        exit;
    }

    // Comprobar si el usuario ya existe
    $consulta_usuario = "SELECT * FROM usuarios WHERE nombre_usuario='$nombre_usuario'";
    $resultado_usuario = mysqli_query($con, $consulta_usuario);
    if (mysqli_num_rows($resultado_usuario) > 0) {
        $_SESSION['register_error'] = "El usuario ya existe.";
        header('Location: ' . $baseUrl . '/vista/auth/registro.php');
        exit;
    }

    // Hashear contraseña
    $contrasenia_hash = password_hash($contrasenia, PASSWORD_DEFAULT);

    // Insertar usuario
    $insertar = "INSERT INTO usuarios (nombre, apellido, nombre_usuario, contrasenia, role_id) VALUES ('$nombre', '$apellido', '$nombre_usuario', '$contrasenia_hash', 2)";
    if (mysqli_query($con, $insertar)) {
        $_SESSION['register_success'] = "Te has registrado exitosamente. Ahora puedes iniciar sesión.";
        header('Location: ' . $baseUrl . '/vista/auth/registro.php');
        exit;
    } else {
        $_SESSION['register_error'] = "Error al registrar usuario: " . mysqli_error($con);
        header('Location: ' . $baseUrl . '/vista/auth/registro.php');
        exit;
    }
}
?>
