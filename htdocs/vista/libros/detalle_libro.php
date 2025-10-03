<?php
// Iniciamos la sesión
session_start();

// Conexión a la base de datos
include __DIR__ . '/../../config/connectiondb.php';

// Armamos la URL base según protocolo y puerto
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$port = ($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
$baseUrl = $protocol . $_SERVER['HTTP_HOST'] . $port;

// Validamos que se haya pasado un ID válido por GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Libro no encontrado.";
    exit;
}

$id_libro = $_GET['id'];
$id_usuario_sesion = $_SESSION['detsuid'];

// Buscamos el libro correspondiente al usuario actual
$sql_libro = "SELECT * FROM libros WHERE id_libro = ? AND id_usuario = ?";
$stmt_libro = mysqli_prepare($con, $sql_libro);
mysqli_stmt_bind_param($stmt_libro, 'ii', $id_libro, $id_usuario_sesion);
mysqli_stmt_execute($stmt_libro);
$result_libro = mysqli_stmt_get_result($stmt_libro);

if ($result_libro && mysqli_num_rows($result_libro) > 0) {
    $libro = mysqli_fetch_assoc($result_libro);

    // Obtenemos la reseña del usuario para ese libro (si existe)
    $sql_resenia = "SELECT comentario, calificacion FROM resenias WHERE id_usuario = ? AND id_libro = ? LIMIT 1";
    $stmt_resenia = mysqli_prepare($con, $sql_resenia);
    mysqli_stmt_bind_param($stmt_resenia, 'ii', $id_usuario_sesion, $id_libro);
    mysqli_stmt_execute($stmt_resenia);
    $result_resenia = mysqli_stmt_get_result($stmt_resenia);
    $resenia = mysqli_fetch_assoc($result_resenia);
} else {
    // Si el libro no existe o no pertenece al usuario, mostramos mensaje de error
    echo "Libro no encontrado o no tienes permiso para verlo.";
    exit;
}

// Cerramos conexión con la BD
mysqli_close($con);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Reseña</title>
    <link rel="icon" href="<?php echo $baseUrl; ?>/favicon.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Flecha retroceder */
        .back-arrow {
            position: absolute;
            top: 10px;
            left: 10px;
            color: #007bff; 
            font-size: 1.5rem;
            background: rgba(255,255,255,0.7);
            padding: 5px;
            border-radius: 50%;
            text-decoration: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .back-arrow:hover {
            color: #0056b3;
            background: rgba(255,255,255,0.9);
        }
        .d-none { display: none; }
        /* Clases para las estrellas */
        .calificacion-libro i.filled-star {
            color: #FFA500; 
        }
        .calificacion-libro i.empty-star {
            color: gray;
        }
        .titulo-libro {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }
        .autor-libro {
            font-size: 1.2rem;
            color: #555;
            margin-bottom: 15px;
        }
        .calificacion-libro {
            margin-top: 20px;
        }
        .contenedor-detalles-libro {
            padding-top: 80px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            min-height: calc(100vh - 80px);
        }
        .columna-detalles-libro {
            min-height: 100vh;
        }
        .columna-imagen-libro {
            margin-left: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .imagen-libro {
            max-width: 100%;
            height: auto;
            max-height: 500px;
            object-fit: contain;
        }
        .botones-libro {
            margin-top: 20px;
            display: flex;
            justify-content: flex-start;
            gap: 10px;
        }
        .botones-libro .btn {
            padding: 8px 16px;
            font-size: 0.9rem;
            font-weight: bold;
            border-radius: 20px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.1);
            text-decoration: none;
        }
        .botones-libro .btn-warning {
            background: linear-gradient(135deg, #ffcc00, #ff9900);
            color: white;
            border: none;
        }
        .botones-libro .btn-danger {
            background: linear-gradient(135deg, #ff4d4d, #cc0000);
            color: white;
            border: none;
        }
        .botones-libro .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
        }
        .botones-libro .btn i {
            font-size: 1rem;
        }
    </style>
</head>
<body>

<?php include_once __DIR__ . '/../layout/header.php'; ?>

<div class="container-fluid">
    <div class="row min-vh-100">
        <?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

        <div class="col-12 col-md-8 col-lg-9 columna-detalles-libro">
            <div class="container my-5 contenedor-detalles-libro">
                <div id="display-mode">
                    <div class="row">
                        <div class="col-md-5 text-center position-relative">
                            <a href="<?php echo $baseUrl; ?>/vista/libros/mis_libros.php" class="back-arrow">
                                <i class="fa fa-arrow-left"></i>
                            </a>
                            <img src="<?php echo $baseUrl . '/' . $libro['imagen']; ?>" class="imagen-libro" alt="Portada del libro">
                        </div>
                        <div class="col-md-7">
                            <h2 class="titulo-libro"><?php echo htmlspecialchars($libro['titulo']); ?></h2>
                            <p class="autor-libro"><strong>Autor:</strong> <?php echo htmlspecialchars($libro['autor']); ?></p>
                            <p><strong>Género:</strong> <?php echo htmlspecialchars($libro['genero']); ?></p>
                            <p><strong>Fecha de lectura:</strong> <?php echo htmlspecialchars($libro['fecha_lectura']); ?></p>
                            <hr>
                            <?php if ($resenia) : ?>
                                <h5>Comentario:</h5>
                                <p><?php echo nl2br(htmlspecialchars(stripcslashes($resenia['comentario']))); ?></p>
                                <h5>Calificación:</h5>
                                <p class="calificacion-libro">
                                    <?php 
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo ($i <= $resenia['calificacion']) 
                                            ? '<i class="fa fa-star filled-star"></i>' 
                                            : '<i class="fa fa-star empty-star"></i>';
                                    }
                                    ?>
                                </p>
                            <?php else : ?>
                                <p>No hay reseña disponible para este libro.</p>
                            <?php endif; ?>
                            <div class="botones-libro">
                                <button type="button" class="btn btn-warning" onclick="toggleEdit()">Editar</button>
                                <a href="<?php echo $baseUrl; ?>/modelo/borrar_libro.php?id=<?php echo $libro['id_libro']; ?>" class="btn btn-danger"
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar este libro?');">
                                    <i class="fa fa-trash"></i> Borrar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modo edición -->
                <div id="edit-mode" class="d-none">
                    <h2>Editar Libro: <?php echo htmlspecialchars($libro['titulo']); ?></h2>
                    <form action="<?php echo $baseUrl; ?>/modelo/editar_libro.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_libro" value="<?php echo $libro['id_libro']; ?>">
                        <input type="hidden" name="id_usuario" value="<?php echo $libro['id_usuario']; ?>">

                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo htmlspecialchars($libro['titulo']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="autor" class="form-label">Autor</label>
                            <input type="text" class="form-control" id="autor" name="autor" value="<?php echo htmlspecialchars($libro['autor']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="genero" class="form-label">Género</label>
                            <input type="text" class="form-control" id="genero" name="genero" value="<?php echo htmlspecialchars($libro['genero']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="fecha_lectura" class="form-label">Fecha de lectura</label>
                            <input type="date" class="form-control" id="fecha_lectura" name="fecha_lectura" value="<?php echo htmlspecialchars($libro['fecha_lectura']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control" id="imagen" name="imagen">
                            <?php if (!empty($libro['imagen'])): ?>
                                <div class="mt-2">
                                    <img src="<?php echo $baseUrl . '/' . $libro['imagen']; ?>" width="100" alt="Imagen actual">
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="comentario" class="form-label">Reseña</label>
                            <textarea class="form-control" id="comentario" name="comentario" rows="7"><?php  
                                $comentario = stripcslashes($resenia['comentario']);
                                echo htmlspecialchars($comentario);
                            ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="calificacion" class="form-label">Calificación</label>
                            <div class="calificacion-libro">
                                <?php 
                                for ($i = 1; $i <= 5; $i++) {
                                    $starClass = ($resenia && $i <= $resenia['calificacion']) ? 'fas filled-star' : 'far empty-star';
                                    echo '<i class="star ' . $starClass . ' fa-star" data-value="' . $i . '" style="cursor: pointer;"></i>';
                                }
                                ?>
                            </div>
                            <input type="hidden" name="calificacion" id="calificacion" value="<?php echo $resenia ? $resenia['calificacion'] : 0; ?>">
                        </div>
                        <div class="botones-libro">
                            <button type="submit" class="btn btn-warning"><i class="fa fa-refresh"></i> Actualizar Libro</button>
                            <button type="button" class="btn btn-danger" onclick="toggleEdit()"><i class="fa fa-times"></i> Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>

<script>
    function toggleEdit() {
        const display = document.getElementById('display-mode');
        const edit = document.getElementById('edit-mode');
        display.classList.toggle('d-none');
        edit.classList.toggle('d-none');
    }

    document.querySelectorAll('.star').forEach(star => {
        star.addEventListener('click', function () {
            const rating = this.getAttribute('data-value');
            document.getElementById('calificacion').value = rating;
            document.querySelectorAll('.star').forEach(s => {
                const val = parseInt(s.getAttribute('data-value'));
                s.classList.toggle('fas', val <= rating);
                s.classList.toggle('far', val > rating);
                s.classList.toggle('filled-star', val <= rating);
                s.classList.toggle('empty-star', val > rating);
            });
        });
    });
</script>

</body>
</html>
