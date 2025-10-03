<?php
// Iniciamos la sesión
session_start();

// Incluimos el archivo de conexión a la bbdd
include __DIR__ . '/../config/connectiondb.php';

// Construimos la URL base (protocolo, host y puerto si no es el predeterminado).
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$port = ($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
$baseUrl = $protocol . $_SERVER['HTTP_HOST'] . $port;

// Verificamos si se recibió el ID de libro
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_libro = $_GET['id'];
    
    // Obtenemos el ID del usuario actual desde la sesión
    $id_usuario_sesion = $_SESSION['detsuid'];

    // Primero verificamos que el libro existe y pertenece al usuario
    $sql_verificar_libro = "SELECT id_libro FROM libros WHERE id_libro = ? AND id_usuario = ?";
    $stmt_verificar = mysqli_prepare($con, $sql_verificar_libro);
    mysqli_stmt_bind_param($stmt_verificar, 'ii', $id_libro, $id_usuario_sesion);
    mysqli_stmt_execute($stmt_verificar);
    $resultado_libro = mysqli_stmt_get_result($stmt_verificar);

    if (mysqli_num_rows($resultado_libro) > 0) {
        // Si el libro existe, proceder a eliminarlo

        // Eliminar la reseña relacionada con el libro
        $sql_eliminar_resenia = "DELETE FROM resenias WHERE id_libro = ?";
        $stmt_eliminar_resenia = mysqli_prepare($con, $sql_eliminar_resenia);
        mysqli_stmt_bind_param($stmt_eliminar_resenia, 'i', $id_libro);
        mysqli_stmt_execute($stmt_eliminar_resenia);

        // Eliminar el libro de la tabla 'libros'
        $sql_eliminar_libro = "DELETE FROM libros WHERE id_libro = ?";
        $stmt_eliminar_libro = mysqli_prepare($con, $sql_eliminar_libro);
        mysqli_stmt_bind_param($stmt_eliminar_libro, 'i', $id_libro);
        mysqli_stmt_execute($stmt_eliminar_libro);

        // Verificamos si la eliminación fue exitosa
        if (mysqli_stmt_affected_rows($stmt_eliminar_libro) > 0) {
            // Redirigir a la lista de libros o a la página principal
            header("Location: " . $baseUrl . "/vista/libros/mis_libros.php");
            exit;
        } else {
            echo "Error al eliminar el libro.";
        }
    } else {
        echo "No se encontró el libro o no tienes permisos para eliminarlo.";
    }
} else {
    echo "ID de libro no válido.";
}

mysqli_close($con);
?>
