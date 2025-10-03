<?php
// Iniciar sesión
session_start();

// Verificamos si el usuario está autenticado; si no, lo redirigimos al login
if (!isset($_SESSION['detsuid'])) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $port = ($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
    $baseUrl = $protocol . $_SERVER['HTTP_HOST'] . $port;
    header("Location: " . $baseUrl . "/index.php");
    exit;
}

// Conexión a la base de datos
include __DIR__ . '/../../config/connectiondb.php';

// Obtenemos el ID del usuario en sesión
$id_usuario = $_SESSION['detsuid'];

// Si el formulario fue enviado por método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtenemos los valores del formulario y limpiamos espacios
    $titulo         = trim($_POST['titulo']);
    $autor          = trim($_POST['autor']);
    $genero         = trim($_POST['genero']);
    $fecha_lectura  = trim($_POST['fecha_lectura']);
    $comentario     = trim($_POST['comentario']);
    $calificacion   = isset($_POST['calificacion']) ? intval($_POST['calificacion']) : 0;

    // Validación de campos obligatorios
    if (empty($titulo) || empty($autor) || empty($fecha_lectura)) {
        echo "Error: Todos los campos (título, autor y fecha de lectura) son obligatorios.";
        exit;
    }

    // Procesamiento de la imagen (si se subió una)
    $imagen = null;
    if (!empty($_FILES['imagen']['name'])) {
        $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        // Solo permitimos ciertos formatos
        if (in_array($extension, ['png', 'jpg', 'jpeg'])) {
            $imagen = 'imagenes/' . basename($_FILES['imagen']['name']);
            move_uploaded_file($_FILES['imagen']['tmp_name'], __DIR__ . '/../../' . $imagen);
        } else {
            echo "Error: Solo se permiten imágenes en formato PNG o JPG.";
            exit;
        }
    }

    // Insertamos el libro en la base de datos
    $sql_libro = "INSERT INTO libros (titulo, autor, fecha_lectura, imagen, genero, id_usuario) 
                    VALUES ('$titulo', '$autor', '$fecha_lectura', '$imagen', '$genero', '$id_usuario')";
    if (mysqli_query($con, $sql_libro)) {
        $id_libro = mysqli_insert_id($con);

        // Si se escribió una reseña, la guardamos también
        if (!empty($comentario)) {
            $sql_resenia = "INSERT INTO resenias (comentario, calificacion, id_libro, id_usuario) 
                            VALUES ('$comentario', '$calificacion', '$id_libro', '$id_usuario')";
            mysqli_query($con, $sql_resenia);
        }

        // Redirigimos al listado de libros
        header("Location: $baseUrl/vista/libros/mis_libros.php");
        exit;
    } else {
        echo "Error al insertar el libro: " . mysqli_error($con);
    }
}

// Cerramos la conexión
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Agregar Libro</title>

    <!-- Ícono para la pestaña -->
    <link rel="icon" href="/favicon.png" type="image/png">

    <!-- Bootstrap para los estilos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- Font Awesome para los íconos (estrellas, basura, etc.) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* Estilo para las estrellas de calificación */
        .star {
            font-size: 1.5em;
            cursor: pointer;
        }

        /* Estilo del botón personalizado para agregar libros */
        .btn-agregar {
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
            background: linear-gradient(135deg, #66b3ff, #3399ff);
            color: white !important;
            border: none;
        }

        .btn-agregar:hover {
            transform: translateY(-2px);
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>

<!-- Header del layout general -->
<?php include_once __DIR__ . '/../layout/header.php'; ?>

<div class="container-fluid">
    <div class="row min-vh-100">
        <!-- Sidebar de navegación -->
        <?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

        <div class="col-12 col-md-8 col-lg-9">
            <div class="container my-5">
                <h3 class="text-center text-dark py-2">Nuevo Libro</h3>

                <!-- Formulario para añadir nuevo libro -->
                <div class="card border-info">
                    <div class="card-body">
                        <form method="POST" action="gestionar_libros.php" enctype="multipart/form-data">
                            <!-- Campo: Título -->
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título del libro</label>
                                <input type="text" name="titulo" id="titulo" class="form-control" required>
                            </div>

                            <!-- Campo: Autor -->
                            <div class="mb-3">
                                <label for="autor" class="form-label">Autor</label>
                                <input type="text" name="autor" id="autor" class="form-control" required>
                            </div>

                            <!-- Campo: Género -->
                            <div class="mb-3">
                                <label for="genero" class="form-label">Género</label>
                                <input type="text" name="genero" id="genero" class="form-control">
                            </div>

                            <!-- Campo: Fecha de lectura -->
                            <div class="mb-3">
                                <label for="fecha_lectura" class="form-label">Fecha fin lectura</label>
                                <input type="date" name="fecha_lectura" id="fecha_lectura" class="form-control" required>
                            </div>

                            <!-- Campo: Imagen del libro -->
                            <div class="mb-3">
                                <label for="imagen" class="form-label">Imagen del libro</label>
                                <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*">
                            </div>

                            <!-- Campo: Reseña / Comentario -->
                            <div class="mb-3">
                                <label for="comentario" class="form-label">Reseña</label>
                                <textarea name="comentario" id="comentario" class="form-control" rows="5"></textarea>
                            </div>

                            <!-- Calificación: estrellas -->
                            <div class="mb-3">
                                <label class="form-label">Calificación</label><br>
                                <div id="stars">
                                    <!-- Mostramos 5 estrellas interactivas -->
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <i class="far fa-star star text-warning" data-value="<?php echo $i; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <!-- Input oculto para guardar la calificación seleccionada -->
                                <input type="hidden" name="calificacion" id="calificacion" value="0">
                            </div>

                            <!-- Botón para enviar el formulario -->
                            <div class="text-center">
                                <button type="submit" class="btn-agregar">Agregar Libro</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Footer del layout -->
<?php include_once __DIR__ . '/../layout/footer.php'; ?>

<!-- Scripts necesarios -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Script para controlar la interacción con las estrellas
    const stars = document.querySelectorAll('.star');
    const calificacionInput = document.getElementById('calificacion');

    stars.forEach(star => {
        star.addEventListener('click', function () {
            const value = this.dataset.value;
            calificacionInput.value = value;

            
            stars.forEach(s => {
                if (s.dataset.value <= value) {
                    s.classList.remove('far'); 
                    s.classList.add('fas');    
                } else {
                    s.classList.remove('fas');
                    s.classList.add('far');
                }
            });
        });
    });
</script>

</body>
</html>
