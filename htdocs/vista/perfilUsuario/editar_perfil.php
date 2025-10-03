<?php
//Conexion a la base de datos
include __DIR__ . '/../../config/connectiondb.php';

// $baseUrl para construir URLs absolutas
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$port = ($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
$baseUrl = $protocol . $_SERVER['HTTP_HOST'] . $port;

// Clase para editar el perfil del usuario
class editarPerfil {
    private $con;
    private $id_usuario;
    private $datosUsuario;

    // Constructor de la clase
    public function __construct($con)
    {   
        // Iniciamos la sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->con = $con;

        // Verificamos si el usuario ha iniciado sesión
        if (!isset($_SESSION['detsuid'])) {
            header("Location: " . $GLOBALS['baseUrl'] . "/index.php");
            exit;
        }

        // Guardamos el ID del usuario y obtenemos sus datos
        $this->id_usuario = $_SESSION['detsuid'];
        $this->obtenerDatosUsuario();
    }

    // Método para obtener los datos del usuario desde la BBDD
    private function obtenerDatosUsuario()
    {
        $query = "SELECT nombre_usuario, contrasenia, nombre, apellido FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->con->prepare($query);
        $stmt->bind_param("i", $this->id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $this->datosUsuario = $result->fetch_assoc();
    }

    // Método para actualizar el perfil del usuario
    public function editarPerfil()
    {   
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Si el usuario deja el campo vacío, mantenemos el valor actual
            $nombre_usuario = !empty($_POST['nombre_usuario']) ? $_POST['nombre_usuario'] : $this->datosUsuario['nombre_usuario'];
            $nombre = !empty($_POST['nombre']) ? $_POST['nombre'] : $this->datosUsuario['nombre'];
            $apellido = !empty($_POST['apellido']) ? $_POST['apellido'] : $this->datosUsuario['apellido'];
            
            // Se revisa si se ingresa una nueva contraseña
            $contrasenia = !empty($_POST['contrasenia']) ? $_POST['contrasenia'] : null;
            $confirmar_contrasenia = isset($_POST['confirmar_contrasenia']) ? $_POST['confirmar_contrasenia'] : null;

            if ($contrasenia) {
                // Se verifica que se haya ingresado la confirmación de la contraseña
                if (empty($confirmar_contrasenia)) {
                    $mensaje_error = "Por favor, confirma la nueva contraseña.";
                    $this->redirigirConError($mensaje_error);
                }
                
                // Verificamos que la nueva contraseña coincida con la confirmación
                if ($contrasenia !== $confirmar_contrasenia) {
                    $mensaje_error = "Las contraseñas no coinciden.";
                    $this->redirigirConError($mensaje_error);
                }

                // Verificamos que la nueva contraseña sea diferente a la actual
                if (password_verify($contrasenia, $this->datosUsuario['contrasenia'])) {
                    $mensaje_error = "La nueva contraseña no puede ser igual a la actual.";
                    $this->redirigirConError($mensaje_error);
                }
            }

            // Preparamos la consulta de actualización según si se cambia la contraseña o no
            if ($contrasenia) {
                $contrasenia_hash = password_hash($contrasenia, PASSWORD_DEFAULT);
                $update_query = "UPDATE usuarios SET nombre_usuario = ?, nombre = ?, apellido = ?, contrasenia = ? WHERE id_usuario = ?";
                $stmt = $this->con->prepare($update_query);
                $stmt->bind_param("ssssi", $nombre_usuario, $nombre, $apellido, $contrasenia_hash, $this->id_usuario);
            } else {
                $update_query = "UPDATE usuarios SET nombre_usuario = ?, nombre = ?, apellido = ? WHERE id_usuario = ?";
                $stmt = $this->con->prepare($update_query);
                $stmt->bind_param("sssi", $nombre_usuario, $nombre, $apellido, $this->id_usuario);
            }

            $stmt->execute();

            // Verificamos si la actualización fue exitosa
            if ($stmt->affected_rows > 0) {
                header("Location: " . $GLOBALS['baseUrl'] . "/vista/perfilUsuario/editar_perfil.php?success=true");
                exit;
            } else {
                $mensaje_error = "Tu perfil no se ha actualizado. Por favor, inténtalo de nuevo.";
                $this->redirigirConError($mensaje_error);
            }
        }
    }

    // Método para redirigir con un mensaje de error
    private function redirigirConError($mensaje)
    {
        header("Location: " . $GLOBALS['baseUrl'] . "/vista/perfilUsuario/editar_perfil.php?error=true&mensaje=" . urlencode($mensaje));
        exit;
    }

    // Método para obtener los datos del usuario
    public function obtenerUsuario()
    {
        return $this->datosUsuario;
    }
}

// Instanciamos la clase y ejecutamos la edición
$actualizarPerfil = new editarPerfil($con);
$actualizarPerfil->editarPerfil();
$datosUsuario = $actualizarPerfil->obtenerUsuario();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Editar Perfil</title>
    <link rel="icon" href="/favicon.png" type="image/png">
    <!-- Cargamos Bootstrap y Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .btn-actualizar {
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
        .btn-actualizar:hover {
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
                    <h3 class="text-center text-dark py-2">Actualizar Perfil</h3>

                    <div class="card border-info">
                        <div class="card-body">
                            <!-- Mensaje de éxito -->
                            <?php if(isset($_GET['success']) && $_GET['success'] == 'true'): ?>
                                <div id="mensaje-exito" class="alert alert-success mt-2 d-flex justify-content-center" role="alert">
                                    Tu perfil se ha actualizado correctamente.
                                </div>
                            <?php endif; ?>
                            <!-- Mensaje de error -->
                            <?php if(isset($_GET['error']) && $_GET['error'] == 'true'): ?>
                                <div id="mensaje-error" class="alert alert-danger mt-2 d-flex justify-content-center" role="alert">
                                    <?php echo isset($_GET['mensaje']) ? $_GET['mensaje'] : ''; ?>
                                </div>
                            <?php endif; ?>
                            <!-- Formulario de edición -->
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
                                <div class="mb-3">
                                    <label for="nombre_usuario" class="form-label">Nombre Usuario</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-signature"></i>
                                        </span>
                                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="apellido" class="form-label">Apellido</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-signature"></i>
                                        </span>
                                        <input type="text" class="form-control" id="apellido" name="apellido" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="contrasenia" class="form-label">Nueva Contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" class="form-control" id="contrasenia" name="contrasenia" autocomplete="new-password">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="confirmar_contrasenia" class="form-label">Confirmar Contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" class="form-control" id="confirmar_contrasenia" name="confirmar_contrasenia" autocomplete="new-password">
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn-actualizar">Actualizar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Incluimos el footer -->
    <?php include_once __DIR__ . '/../layout/footer.php'; ?>

    <!-- Cargamos Bootstrap Bundle para la funcionalidad interactiva -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Manejo de mensajes de éxito y error con tiempo de desaparición
        document.addEventListener('DOMContentLoaded', function () {
            const mensajeExito = document.getElementById('mensaje-exito');
            const mensajeError = document.getElementById('mensaje-error');
            if (mensajeExito) {
                setTimeout(() => mensajeExito.classList.add('d-none'), 5000);
            }
            if (mensajeError) {
                setTimeout(() => mensajeError.classList.add('d-none'), 5000);
            }
        });
    </script>
</body>
</html>
<?php mysqli_close($con); ?>
