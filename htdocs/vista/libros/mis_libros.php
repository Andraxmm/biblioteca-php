<?php
//Iniciamos la sesion
session_start();
//Conexion a la base de datos
include __DIR__ . '/../../config/connectiondb.php';

// $baseUrl para construir URLs absolutas
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$port = ($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
$baseUrl = $protocol . $_SERVER['HTTP_HOST'] . $port;

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION['detsuid'])) {
    header("Location: " . $baseUrl . "/index.php");
    exit;
}

$id_usuario = $_SESSION['detsuid'];

// 1. Extraer todos los géneros disponibles para este usuario
    $sqlGeneros = "SELECT DISTINCT genero 
                FROM libros 
                WHERE id_usuario = $id_usuario 
                    AND genero <> '' 
                ORDER BY genero";
$resultGeneros = mysqli_query($con, $sqlGeneros);

// 2. Comprobar si se ha seleccionado un género
$generoSeleccionado = isset($_GET['genero']) ? mysqli_real_escape_string($con, $_GET['genero']) : '';

// 3. Construir la consulta para filtrar por género
if (!empty($generoSeleccionado)) {
    $consulta = "SELECT id_libro, titulo, imagen
                    FROM libros
                    WHERE id_usuario = $id_usuario
                    AND genero = '$generoSeleccionado'";
} else {
    // Si no se seleccionó ningún género, mostrar todos
    $consulta = "SELECT id_libro, titulo, imagen
                    FROM libros
                    WHERE id_usuario = $id_usuario";
    }

$resultado = mysqli_query($con, $consulta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Mis Libros</title>
    <link rel="icon" href="/favicon.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .enlace-libro {
            display: block;
            text-align: center;
            margin-bottom: 10px;
        }
        .contenedor-imagen-libro {
            width: 150px;
            height: 200px;
            margin: 0 auto;
            overflow: hidden;
            position: relative;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .contenedor-imagen-libro:hover {
            transform: scale(1.05);
        }
        .imagen-libro {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 8px;
            transition: opacity 0.3s ease;
        }
        .imagen-libro:hover {
            opacity: 0.8;
            cursor: pointer;
        }
        .titulo-libro {
            text-align: center;
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 15px;
            text-transform: uppercase;
            font-family: 'Roboto', sans-serif;
            font-weight: 500;
        }
        .contenedor-linea-libro {
            width: 50%;
            margin: 5px auto;
            border-bottom: 1px solid #ddd;
        }
        .columna-libro {
            padding: 0 2px;
        }
        .fila-libros {
            display: flex;
            flex-wrap: wrap;
        }
        /* Estilo personalizado para el botón (igual al de Agregar Libro) */
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

<!-- Incluimos el header --> 
<?php include_once __DIR__ . '/../layout/header.php'; ?>
<div class="container-fluid">
    <div class="row min-vh-100">
        <!-- Incluimos el sidebar --> 
    <?php include_once __DIR__ . '/../layout/sidebar.php'; ?>
    <div class="col-12 col-md-8 col-lg-9">
            <div class="container my-5">

                <!-- Título centrado -->
                <h3 class="text-dark py-2 text-center">Mis Libros</h3>

                <!-- Filtro alineado a la derecha en una línea separada -->
                <div class="d-flex justify-content-end mb-3" style="padding-right: 50px;">
                    <form method="GET" action="mis_libros.php" class="d-inline-block">
                        <label for="genero" class="me-2">Filtrar por género:</label>
                        <select name="genero" id="genero" class="form-select d-inline-block w-auto me-2">
                            <option value="">-- Todos --</option>
                            <?php 
                            // Reiniciamos el puntero del resultado de géneros
                            mysqli_data_seek($resultGeneros, 0);
                            while ($filaGenero = mysqli_fetch_assoc($resultGeneros)) : 
                                $g = $filaGenero['genero'];
                                if (!empty($g)) {
                                    $selected = ($generoSeleccionado === $g) ? 'selected' : '';
                                    echo "<option value='$g' $selected>$g</option>";
                                }
                            endwhile; 
                            ?>
                        </select>
                        <button type="submit" class="btn-agregar">Filtrar</button>
                    </form>
                </div>

                <hr>

                <!-- Mostrar los libros -->
                <div class="fila-libros">
                    <?php while ($libro = mysqli_fetch_assoc($resultado)) : ?>
                        <div class="col-md-2 columna-libro">
                        <a href="<?php echo $baseUrl; ?>/vista/libros/detalle_libro.php?id=<?php echo $libro['id_libro']; ?>" class="enlace-libro">
                        <div class="contenedor-imagen-libro">
                        <?php
                                    // Verificar existencia de la imagen en el sistema de archivos
                                    $ruta_imagen_fs = __DIR__ . '/../../' . $libro['imagen'];
                                    if (file_exists($ruta_imagen_fs)) {
                                        echo '<img src="' . $baseUrl . '/' . $libro['imagen'] . '" class="imagen-libro" alt="Portada del libro">';
                                    } else {
                                        echo 'Imagen no encontrada';
                                    }
                                    ?>
                                </div>
                            </a>
                            <div class="titulo-libro">
                                <?php echo htmlspecialchars($libro['titulo']); ?>
                                <div class="contenedor-linea-libro"></div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<?php mysqli_close($con); ?>

<!-- Incluimos el footer --> 
<?php include_once __DIR__ . '/../layout/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
