<?php
// Iniciamos la sesión 
session_start();

//Conexión a la base de datos
include __DIR__ . '/../../config/connectiondb.php';

// Construimos la URL base según protocolo y puerto (por si se necesita redireccionar)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$port = ($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
$baseUrl = $protocol . $_SERVER['HTTP_HOST'] . $port;

// Comprobamos si el usuario inició sesión, si no, lo mandamos al login
if (!isset($_SESSION['detsuid'])) {
    header("Location: " . $baseUrl . "/index.php");
    exit;
}

// Guardamos el ID del usuario actual desde la sesión
$id_usuario = $_SESSION['detsuid'];

// Consulta para obtener los 10 libros con mejor calificación del usuario
$consulta = "SELECT l.id_libro, l.titulo, l.imagen, r.calificacion, r.comentario
            FROM libros l
            INNER JOIN resenias r ON l.id_libro = r.id_libro
            WHERE l.id_usuario = '$id_usuario'
            ORDER BY r.calificacion DESC
            LIMIT 10";

// Ejecutamos la consulta
$resultado = mysqli_query($con, $consulta);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Libros Mejor Valorados</title>

    <!-- Ícono de la pestaña -->
    <link rel="icon" href="/favicon.png" type="image/png">

    <!-- Bootstrap para estilos generales -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- Font Awesome para íconos (estrellas, etc.) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        .numero-ranking {
            position: absolute;
            top: 10px;
            left: 10px;
            color: #fff;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1rem;
            z-index: 10;
        }

        /* Colores según posición en ranking */
        .numero-ranking.primero { background: #ffd700; } /* Oro */
        .numero-ranking.segundo { background: #c0c0c0; } /* Plata */
        .numero-ranking.tercero { background: #cd7f32; } /* Bronce */
        .numero-ranking.defecto { background: rgb(33, 120, 196); }

        .tarjeta-ranking {
            margin-bottom: 20px;
        }

        .imagen-libro {
            width: 100%;
            max-height: 200px;
            object-fit: contain;
            border-radius: 8px;
        }

        /* Título del libro */
        .card-body h5 {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .card-text {
            font-size: 1rem;
        }

        /* Estilo para estrellas */
        .valoracion-estrellas i {
            color: #FFA500;
        }

        /* Botón para ir al detalle del libro */
        .btn-detalle {
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

        .btn-detalle:hover {
            transform: translateY(-2px);
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <!-- Incluimos header del sistema -->
    <?php include_once __DIR__ . '/../layout/header.php'; ?>

    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Incluimos el sidebar -->
            <?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

            <!-- Contenido principal -->
            <div class="col-12 col-md-8 col-lg-9">
                <div class="container my-5">
                    <h3 class="text-center text-dark py-2">Ranking: Libros Mejor Valorados</h3>
                    <hr>

                    <?php 
                    // Contador para el ranking
                    $contador = 1;

                    // Recorremos todos los libros del resultado
                    while($libro = mysqli_fetch_assoc($resultado)) :

                        // Asignamos clase según posición
                        if($contador == 1) {
                            $clase_ranking = 'primero';
                        } elseif($contador == 2) {
                            $clase_ranking = 'segundo';
                        } elseif($contador == 3) {
                            $clase_ranking = 'tercero';
                        } else {
                            $clase_ranking = 'defecto';
                        }
                    ?>

                    <!-- Tarjeta de cada libro -->
                    <div class="card tarjeta-ranking">
                        <div class="row g-0">
                            <!-- Columna de la imagen -->
                            <div class="col-md-3 position-relative d-flex align-items-center justify-content-center">
                                <!-- Número del ranking -->
                                <div class="numero-ranking <?php echo $clase_ranking; ?>">
                                    <?php echo $contador; ?>
                                </div>

                                <?php 
                                // Verificamos si la imagen existe en el servidor
                                $ruta_imagen_fs = __DIR__ . '/../../' . $libro['imagen'];
                                if(file_exists($ruta_imagen_fs)) {
                                    echo '<img src="' . $baseUrl . '/' . $libro['imagen'] . '" class="img-fluid imagen-libro" alt="Portada del libro">';
                                } else {
                                    echo '<div class="text-center">Imagen no encontrada</div>';
                                }
                                ?>
                            </div>

                            <!-- Columna de detalles -->
                            <div class="col-md-9">
                                <div class="card-body">
                                    <!-- Título del libro -->
                                    <h5 class="card-title"><?php echo htmlspecialchars($libro['titulo']); ?></h5>

                                    <!-- Estrellas según calificación -->
                                    <div class="valoracion-estrellas mb-2">
                                        <?php 
                                        $calificacion = round($libro['calificacion']);
                                        for($i = 1; $i <= 5; $i++){
                                            if($i <= $calificacion) {
                                                echo '<i class="fas fa-star"></i>';
                                            } else {
                                                echo '<i class="far fa-star"></i>';
                                            }
                                        }
                                        ?>
                                    </div>

                                    <!-- Comentario o reseña -->
                                    <p class="card-text"><strong>Reseña:</strong> <?php echo nl2br(htmlspecialchars(stripcslashes($libro['comentario']))); ?></p>

                                    <!-- Botón para ir al detalle del libro -->
                                    <a href="<?php echo $baseUrl; ?>/vista/libros/detalle_libro.php?id=<?php echo $libro['id_libro']; ?>" class="btn-detalle">Ver Detalle</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php 
                        $contador++; // Aumentamos el contador
                    endwhile; 
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include_once __DIR__ . '/../layout/footer.php'; ?>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php 
// Cerramos la conexión con la base de datos
mysqli_close($con); 
?>
