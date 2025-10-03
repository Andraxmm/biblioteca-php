<?php
//Iniciamos la sesión
session_start();
// Archivo de conexión a la base de datos
include __DIR__ . '/../../config/connectiondb.php';

// Título que se muestra en la pestaña del navegador
$tituloPagina = "Biblioteca - Administración"; 

// Construimos la URL base teniendo en cuenta protocolo, host y puerto
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$port = ($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
$baseUrl = $protocol . $_SERVER['HTTP_HOST'] . $port;

class GestorAdministracion
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;

        // Verificamos que el usuario esté logueado y que sea admin, si no, lo sacamos
        if (!isset($_SESSION['detsuid']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['acceso_denegado'] = "Acceso restringido. Debes iniciar sesión.";
            header("Location: " . $GLOBALS['baseUrl'] . "/index.php");
            exit;
        }
    }

    // Método para actualizar el rol de un usuario (admin o usuario)
    public function actualizarRol()
    {
        if (isset($_POST['id_usuario']) && isset($_POST['rol'])) {
            $idUsuario = $_POST['id_usuario'];
            $rolNuevo = $_POST['rol'];

            // Prevención: el admin no puede quitarse su propio rol
            if ($_SESSION['detsuid'] == $idUsuario && $rolNuevo === 'usuario') {
                header("Location: " . $GLOBALS['baseUrl'] . "/index.php");
                exit;
            }

            // Asignamos el valor numérico según el rol seleccionado
            $valorRol = ($rolNuevo === 'admin') ? 1 : 2;
            $consulta = $this->conexion->prepare("UPDATE usuarios SET role_id = ? WHERE id_usuario = ?");
            $consulta->bind_param("ii", $valorRol, $idUsuario);

            // Si algo falla, lo mostramos por pantalla
            if (!$consulta->execute()) {
                echo "Error al actualizar rol: " . $this->conexion->error;
            }
        }
    }

    // Método para eliminar un usuario (y sus libros asociados)
    public function eliminarUsuario()
    {
        if (isset($_POST['btn_eliminar']) && isset($_POST['id_usuario_eliminar'])) {
            $idUsuarioEliminar = $_POST['id_usuario_eliminar'];

            // Primero se eliminan los libros del usuario para evitar errores por claves foráneas
            $consultaLibros = $this->conexion->prepare("DELETE FROM libros WHERE id_usuario = ?");
            $consultaLibros->bind_param("i", $idUsuarioEliminar);
            if (!$consultaLibros->execute()) {
                echo "Error al eliminar libros: " . $this->conexion->error;
                return;
            }

            // Luego se elimina el propio usuario
            $consultaUsuario = $this->conexion->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
            $consultaUsuario->bind_param("i", $idUsuarioEliminar);
            if ($consultaUsuario->execute()) {
                // Si todo salió bien, redirigimos para refrescar la tabla
                header("Location: " . $GLOBALS['baseUrl'] . "/vista/admin/actualizar_role.php");
                exit;
            } else {
                echo "Error al eliminar usuario: " . $this->conexion->error;
            }
        }
    }

    // Método para obtener todos los usuarios con sus datos y rol actual
    public function obtenerUsuarios()
    {
        $sql = "SELECT u.id_usuario, u.nombre_usuario, u.nombre, u.apellido, u.role_id, r.role_name 
                FROM usuarios u 
                INNER JOIN role r ON u.role_id = r.role_id";
        return mysqli_query($this->conexion, $sql);
    }
}

// Creamos una instancia del gestor y ejecutamos acciones si las hay
$gestorAdmin = new GestorAdministracion($con);
$gestorAdmin->actualizarRol();
$gestorAdmin->eliminarUsuario();
?>

<?php include_once __DIR__ . '/../layout/header.php'; ?>
<title><?php echo isset($tituloPagina) ? $tituloPagina : "Biblioteca"; ?></title>

<div class="container-fluid">
    <div class="row vh-100">
        <?php include_once __DIR__ . '/../layout/sidebar.php'; ?>
        <div class="col-12 col-md-8 col-lg-9">
            <div class="container my-5">
                <h3 class="text-center text-dark py-2">Panel de Administración</h3>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Recorremos los usuarios obtenidos y los mostramos en la tabla
                            $listaUsuarios = $gestorAdmin->obtenerUsuarios();
                            while ($fila = mysqli_fetch_assoc($listaUsuarios)):
                            ?>
                            <tr>
                                <td><?php echo $fila['id_usuario']; ?></td>
                                <td><?php echo $fila['nombre_usuario']; ?></td>
                                <td><?php echo $fila['nombre']; ?></td>
                                <td><?php echo $fila['apellido']; ?></td>
                                <td><?php echo $fila['role_name']; ?></td>
                                <td>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                        <!-- Ocultamos los IDs que vamos a usar para eliminar o cambiar rol -->
                                        <input type="hidden" name="id_usuario" value="<?php echo $fila['id_usuario']; ?>">
                                        <input type="hidden" name="id_usuario_eliminar" value="<?php echo $fila['id_usuario']; ?>">

                                        <!-- Botón para eliminar usuario -->
                                        <button type="submit" class="btn btn-link text-danger p-0 mx-2"
                                            name="btn_eliminar"
                                            onclick="return confirm('¿Estás seguro que deseas eliminar este usuario?')"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar Usuario">
                                            <i class="fas fa-trash-alt fa-lg"></i>
                                        </button>

                                        <!-- Botón para cambiar rol, dependiendo si ya es admin o no -->
                                        <?php if ($fila['role_id'] != 1): ?>
                                            <button type="submit" class="btn btn-link text-success p-0 mx-2"
                                                name="rol" value="admin"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Asignar Admin">
                                                <i class="fas fa-user-shield fa-lg"></i>
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-link text-primary p-0 mx-2"
                                                name="rol" value="usuario"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Revocar Admin">
                                                <i class="fas fa-user-slash fa-lg"></i>
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>

<!-- Script de Bootstrap para tooltips -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Activamos los tooltips de Bootstrap
    window.onload = function() {
        var tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltipElements.forEach(function(el) {
            new bootstrap.Tooltip(el);
        });
    };
</script>
