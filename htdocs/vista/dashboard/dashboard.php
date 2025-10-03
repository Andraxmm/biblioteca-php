<?php
//Iniciamos la sesión
session_start();

//Archivo de conexión a la base de datos
include __DIR__ . '/../../config/connectiondb.php';

// Título para la pestaña de navegador
$tituloPagina = "Biblioteca - Panel de Control"; 

// Armamos la URL base dependiendo si es HTTP o HTTPS y el puerto actual
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$port = ($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
$baseUrl = $protocol . $_SERVER['HTTP_HOST'] . $port;

// Si no hay sesión iniciada, redirigimos al inicio
if (!isset($_SESSION['detsuid'])) {
    header("Location: " . $baseUrl . "/index.php");
    exit;
}

// Guardamos el ID del usuario actual desde la sesión
$id_usuario = $_SESSION['detsuid'];

// Consulta para contar cuántos libros tiene el usuario
$consulta_total = "SELECT COUNT(*) AS libros_totales FROM libros WHERE id_usuario = $id_usuario";
$resultado_total = mysqli_query($con, $consulta_total);
$libros_totales = ($resultado_total && mysqli_num_rows($resultado_total) > 0)
    ? mysqli_fetch_assoc($resultado_total)['libros_totales']
    : 0;

// Consulta para obtener los últimos 5 libros añadidos por el usuario, ordenados por fecha de lectura
$consulta_libros = "SELECT id_libro, titulo, imagen FROM libros WHERE id_usuario = $id_usuario ORDER BY fecha_lectura DESC LIMIT 5";
$resultado_libros = mysqli_query($con, $consulta_libros);

// Cerramos la conexión a la base de datos
mysqli_close($con);
?>

<?php include_once __DIR__ . '/../layout/header.php'; ?>

<style>
    /* Estilos para los enlaces a los libros */
    .enlace-libro {
        display: block;
        text-align: center;
        margin-bottom: 10px;
    }

    /* Contenedor con sombra para las portadas de libros */
    .contenedor-img-libro {
        width: 150px;
        height: 200px;
        margin: 0 auto;
        overflow: hidden;
        position: relative;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        border: 1px solid white;
        border-radius: 8px;
    }

    /* Imagen del libro ajustada al contenedor */
    .imagen-libro {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        border-radius: 8px;
    }

    .imagen-libro:hover {
        opacity: 0.8;
        cursor: pointer;
    }

    /* Título de cada libro */
    .titulo-libro {
        text-align: center;
        font-size: 1rem;
        color: #333;
        margin-top: 10px;
        text-transform: uppercase;
        font-family: 'Roboto', sans-serif;
        font-weight: 500;
    }

    /* Línea decorativa debajo del título */
    .contenedor-linea-libro {
        width: 50%;
        margin: 5px auto;
        border-bottom: 1px solid #ddd;
    }

    /* Estilo del encabezado de sección */
    .titulo-mis-libros {
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        font-family: 'Playfair Display', serif;
    }
</style>

<div class="container-fluid">
    <div class="row min-vh-100">
        <?php include_once __DIR__ . '/../layout/sidebar.php'; ?>
        <title><?php echo isset($tituloPagina) ? $tituloPagina : "Biblioteca"; ?></title>

        <div class="col-12 col-md-8 col-lg-9">
            <div class="container my-5">
                <div class="row d-flex justify-content-center">
                    <!-- Mensaje de bienvenida -->
                    <h3 class="text-center text-dark py-2">¡Bienvenido a tu biblioteca personal!</h3>

                    
                    <div class="row g-4">
                        <!-- Tarjeta: ver mis libros -->
                        <div class="col-md-4">
                            <div class="card border-primary text-center">
                                <div class="card-body">
                                    <i class="fa-solid fa-book fa-2x text-primary"></i>
                                    <h5 class="card-title mt-2">Mis Libros</h5>
                                    <p class="card-text">Total: <strong><?php echo $libros_totales; ?></strong></p>
                                    <a href="<?php echo $baseUrl; ?>/vista/libros/mis_libros.php" class="btn btn-primary">Ver detalles</a>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta: añadir nuevo libro -->
                        <div class="col-md-4">
                            <div class="card border-success text-center">
                                <div class="card-body">
                                    <i class="fa-solid fa-plus fa-2x text-success"></i>
                                    <h5 class="card-title mt-2">Añadir Libro</h5>
                                    <p class="card-text">Registra un nuevo libro en tu biblioteca.</p>
                                    <a href="<?php echo $baseUrl; ?>/vista/libros/gestionar_libros.php" class="btn btn-success">Añadir</a>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta: ver mejores libros -->
                        <div class="col-md-4">
                            <div class="card border-warning text-center">
                                <div class="card-body">
                                    <i class="fa-solid fa-star fa-2x text-warning"></i>
                                    <h5 class="card-title mt-2">Mis Libros Mejor Valorados</h5>
                                    <p class="card-text">Explora las mejores reseñas.</p>
                                    <a href="<?php echo $baseUrl; ?>/vista/libros/libros_mejor_valorados.php" class="btn btn-warning">Ver ranking</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección: últimos libros añadidos -->
                    <div class="mt-5">
                        <h4>Últimos Libros Añadidos</h4>
                        <hr><br>
                        <div class="row justify-content-center">
                            <?php while ($libro = mysqli_fetch_assoc($resultado_libros)) : ?>
                                <div class="col-md-2 columna-libro">
                                    <a href="<?php echo $baseUrl; ?>/vista/libros/detalle_libro.php?id=<?php echo $libro['id_libro']; ?>" class="enlace-libro">
                                        <div class="contenedor-img-libro">
                                            <?php
                                            // Comprobamos si la imagen existe en el sistema de archivos antes de mostrarla
                                            $ruta_imagen_fs = __DIR__ . '/../../' . $libro['imagen'];
                                            if (file_exists($ruta_imagen_fs)) {
                                                echo '<img src="' . $baseUrl . '/' . $libro['imagen'] . '" class="imagen-libro" alt="Portada del libro">';
                                            } else {
                                                echo 'Imagen no encontrada';
                                            }
                                            ?>
                                        </div>
                                    </a>
                                    <!-- Mostramos el título debajo de la imagen -->
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
    </div>
</div>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>