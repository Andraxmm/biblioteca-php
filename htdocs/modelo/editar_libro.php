<?php
session_start();
include __DIR__ . '/../config/connectiondb.php';

// Construimos la URL base (incluyendo protocolo, host y puerto si corresponde).
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$port = ($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
$baseUrl = $protocol . $_SERVER['HTTP_HOST'] . $port;

// Verifica que el formulario se haya enviado vía POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener y sanitizar los datos del formulario
    $id_libro       = $_POST['id_libro'];
    $titulo         = mysqli_real_escape_string($con, $_POST['titulo']);
    $autor          = mysqli_real_escape_string($con, $_POST['autor']);
    $genero         = mysqli_real_escape_string($con, $_POST['genero']);
    $fecha_lectura  = mysqli_real_escape_string($con, $_POST['fecha_lectura']);
    $comentario     = mysqli_real_escape_string($con, $_POST['comentario']);
    $calificacion   = $_POST['calificacion'];

    // Manejo de la imagen
    $imagen = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $uploadDir   = __DIR__ . '/../imagenes/';  
        $imagenName  = basename($_FILES['imagen']['name']);
        $uploadFile  = $uploadDir . $imagenName;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadFile)) {
            $imagen = 'imagenes/' . $imagenName;
        } else {
            echo "Error al subir la imagen.";
            exit;
        }
    }
    // Si no se sube una nueva imagen, conservar la actual
    if (!$imagen) {
        $sqlImg = "SELECT imagen FROM libros WHERE id_libro = ? AND id_usuario = ?";
        $stmtImg = mysqli_prepare($con, $sqlImg);
        mysqli_stmt_bind_param($stmtImg, 'ii', $id_libro, $_SESSION['detsuid']);
        mysqli_stmt_execute($stmtImg);
        $resultImg = mysqli_stmt_get_result($stmtImg);
        if ($row = mysqli_fetch_assoc($resultImg)) {
            $imagen = $row['imagen'];
        }
    }

    // Actualizar la tabla libros (incluyendo título, autor, género, fecha de lectura e imagen)
    $sqlUpdateLibro = "UPDATE libros SET titulo = ?, autor = ?, genero = ?, fecha_lectura = ?, imagen = ? WHERE id_libro = ? AND id_usuario = ?";
    $stmtUpdateLibro = mysqli_prepare($con, $sqlUpdateLibro);
    mysqli_stmt_bind_param($stmtUpdateLibro, 'sssssii', $titulo, $autor, $genero, $fecha_lectura, $imagen, $id_libro, $_SESSION['detsuid']);
    mysqli_stmt_execute($stmtUpdateLibro);

    // Actualizar la tabla resenias
    $sqlUpdateResenia = "UPDATE resenias SET comentario = ?, calificacion = ? WHERE id_libro = ?";
    $stmtUpdateResenia = mysqli_prepare($con, $sqlUpdateResenia);
    mysqli_stmt_bind_param($stmtUpdateResenia, 'sii', $comentario, $calificacion, $id_libro);
    mysqli_stmt_execute($stmtUpdateResenia);

    mysqli_close($con);

    // Redirigir a la página de detalle para ver los cambios
    header("Location: " . $baseUrl . "/vista/libros/detalle_libro.php?id=" . $id_libro);
    exit;
} else {
    echo "Acceso no permitido.";
    exit;
}
?>
