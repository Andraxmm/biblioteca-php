<?php
// Asegúrate de que la sesión esté iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$port = ($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
$baseUrl = $protocol . $_SERVER['HTTP_HOST'] . $port;
?>
<div class="col-md-3 col-lg-2 d-flex flex-column flex-shrink-0 p-3" style="background-color: #f5f1e8; min-height: 100vh;">
    <?php
    $idUsuario = $_SESSION['detsuid'];
    $consultaUsuario = mysqli_query($con, "SELECT nombre_usuario FROM usuarios WHERE id_usuario='$idUsuario'");
    $filaUsuario = mysqli_fetch_assoc($consultaUsuario);
    $nombreUsuario = $filaUsuario['nombre_usuario'];
    ?>
    
    <!-- Saludo al usuario -->
    <div class="text-center mb-3">
        <div class="small">Hola,</div>
        <h6 class="fw-bold"><?php echo htmlspecialchars($nombreUsuario); ?></h6>
    </div>

    <!-- Enlace para administradores -->
    <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="<?php echo $baseUrl; ?>/vista/admin/actualizar_role.php" class="nav-link link-primary text-black">
            <i class="fas fa-user-shield me-2"></i> Administrador
        </a>
    <?php endif; ?>

    <hr>

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
        <a href="<?php echo $baseUrl; ?>/vista/dashboard/dashboard.php" class="nav-link text-black">
        <i class="fa-solid fa-house me-2"></i> Inicio
            </a>
        </li>

        <!-- Sección Libros -->
        <li class="nav-item">
            <a href="#" class="nav-link text-black" onclick="mostrarOcultarSubenlaces(event, 'libros')">
                <i class="fa-solid fa-book-open me-2"></i> Libros
                <i class="fas fa-caret-down float-end"></i>
            </a>
            <ul class="nav flex-column sub-enlaces ps-4" id="libros-sublinks" style="display: none;">
                <li class="nav-item">
                <a href="<?php echo $baseUrl; ?>/vista/libros/gestionar_libros.php" class="nav-link text-black">
                <i class="fa-solid fa-circle-plus me-2"></i> Agregar libro
                    </a>
                </li>
                <li class="nav-item">
                <a href="<?php echo $baseUrl; ?>/vista/libros/mis_libros.php" class="nav-link text-black">
                <i class="fa-solid fa-book me-2"></i> Mis libros
                    </a>
                </li>
                <li class="nav-item">
                <a href="<?php echo $baseUrl; ?>/vista/libros/libros_mejor_valorados.php" class="nav-link text-black">
                <i class="fa-solid fa-star"></i> Mejor valorados
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
        <a href="<?php echo $baseUrl; ?>/vista/perfilUsuario/editar_perfil.php" class="nav-link text-black">
        <i class="fas fa-user me-2"></i> Editar Perfil
            </a>
        </li>
        <li class="nav-item">
        <a href="<?php echo $baseUrl; ?>/controlador/cerrar_sesion.php" class="nav-link text-black">
        <i class="fas fa-right-from-bracket me-2"></i> Cerrar sesión
            </a>
        </li>
        <li class="nav-item">
        <a href="<?php echo $baseUrl; ?>/controlador/eliminar_cuenta.php" class="nav-link text-danger">
        <i class="fa-solid fa-trash me-2"></i> Eliminar cuenta
            </a>
        </li>
    </ul>
</div>

<!-- Script para mostrar/ocultar subenlaces -->
<script>
    function mostrarOcultarSubenlaces(event, id) {
        event.preventDefault();
        const subEnlaces = document.getElementById(id + '-sublinks');
        if (subEnlaces.style.display === 'none' || subEnlaces.style.display === '') {
            subEnlaces.style.display = 'block';
        } else {
            subEnlaces.style.display = 'none';
        }
    }
</script>

<style>
    .nav-link:hover {
        background-color: #f1f1f1; 
        color: #007bff; 
        transition: background-color 0.3s ease; 
    }
    .nav-item:hover {
        box-shadow: inset 4px 0px 0px #007bff; 
    }
</style>
