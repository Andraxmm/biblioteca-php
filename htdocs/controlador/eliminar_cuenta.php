<?php
// Iniciamos la sesión.
session_start();

// Archivo de conexión a la base de datos
include __DIR__ . '/../config/connectiondb.php';
$tituloPagina = "Biblioteca - Eliminar Cuenta"; // Título que aparece en la pestaña del navegador

class eliminarCuenta
{
    private $con;

    // Método para obtener la URL base del sitio, tomando en cuenta el protocolo y el puerto
    public function getBaseUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $port = ($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
        return $protocol . $_SERVER['HTTP_HOST'] . $port;
    }

    // Constructor que inicia sesión si no está iniciada y verifica si el usuario está logueado
    public function __construct($con)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->con = $con;

        // Si no hay sesión activa, redirige al inicio
        if (!isset($_SESSION['detsuid'])) {
            header("Location: " . $this->getBaseUrl() . "/index.php");
            exit;
        }
    }

    // Método que elimina la cuenta del usuario y sus libros asociados
    public function EliminarCuentaUsu()
    {
        // Solo se ejecuta si la petición viene por POST
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return;
        }

        // Si no se confirmó la eliminación, muestra un error
        if (!isset($_POST['confirm_delete'])) {
            $_SESSION['delete_account_error'] = "Por favor, confirma que quieres eliminar tu cuenta.";
            header("Location: " . $this->getBaseUrl() . $_SERVER['PHP_SELF']);
            exit;
        }

        $id_usu = $_SESSION['detsuid'];

        // Primero se eliminan los libros del usuario
        $borrar_libros = "DELETE FROM libros WHERE id_usuario = ?";
        $stmt = $this->con->prepare($borrar_libros);
        $stmt->bind_param("i", $id_usu);
        if (!$stmt->execute()) {
            $_SESSION['delete_account_error'] = "Error al eliminar los libros del usuario.";
            header("Location: " . $this->getBaseUrl() . $_SERVER['PHP_SELF']);
            exit;
        }

        // Después se elimina el usuario de la base de datos
        $eliminar_usuario = "DELETE FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->con->prepare($eliminar_usuario);
        $stmt->bind_param("i", $id_usu);
        if ($stmt->execute()) {
            $_SESSION['delete_account_success'] = "Tu cuenta se ha eliminado correctamente.";
        } else {
            $_SESSION['delete_account_error'] = "Error al eliminar la cuenta del usuario.";
        }

        // Finalmente redirige al inicio
        header("Location: " . $this->getBaseUrl() . "/index.php");
        exit;
    }
}

// Si se envió un formulario POST, se ejecuta la lógica para eliminar la cuenta
$cuenta = new eliminarCuenta($con);
$cuenta->EliminarCuentaUsu();
?>

<?php include __DIR__ . '/../vista/layout/header.php'; ?>
<title><?php echo isset($tituloPagina) ? $tituloPagina : "Biblioteca"; ?></title>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../vista/layout/sidebar.php'; ?>

        <div class="col-md-10">
            <div class="row justify-content-center" style="margin-top: 10vh; margin-bottom: 10vh;">
                <div class="col-md-6">
                    <div class="card p-4 fade-in" style="background-color:rgb(255, 255, 255); border-radius: 10px;">
                        <div class="card-header text-center">
                            <i class="bi bi-trash"></i> Eliminar Cuenta
                        </div>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
                            <?php if (isset($_SESSION['delete_account_error'])): ?>
                                <div id="error-message" class="alert alert-danger" role="alert">
                                    <?php echo $_SESSION['delete_account_error']; unset($_SESSION['delete_account_error']); ?>
                                </div>
                            <?php endif; ?>
                            <div class="text-center mb-4 mt-3">
                                <h3>¿Estás seguro de que quieres eliminar tu cuenta?</h3>
                                <p class="text-danger">Advertencia: Esta acción es irreversible.</p>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="confirm_delete" class="btn-eliminar"
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar tu cuenta? Esta acción es irreversible.');">
                                    Eliminar cuenta
                                </button>
                            </div>
                        </form>
                        <div class="text-center mt-4">
                            <hr>
                            <p class="small text-muted">Si has cambiado de opinión, puedes cancelar y volver al inicio.</p>
                            <a href="<?php echo $cuenta->getBaseUrl(); ?>/vista/dashboard/dashboard.php" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>

<?php include __DIR__ . '/../vista/layout/footer.php'; ?>

<script>
    // Script para que el mensaje de error desaparezca automáticamente después de unos segundos
    document.addEventListener('DOMContentLoaded', function () {
        const errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            setTimeout(function () {
                errorMessage.classList.add('fade-out');
                errorMessage.addEventListener('transitionend', function () {
                    errorMessage.remove();
                });
            }, 5000);
        }
    });
</script>

<style>
    /* Estilos para el botón de eliminar cuenta */
    .btn-eliminar {
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
        background: linear-gradient(135deg, #ff4d4d, #cc0000);
        color: white !important;
        border: none;
    }

    .btn-eliminar:hover {
        transform: translateY(-2px);
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
    }

    /* Clase para aplicar la transición al mensaje de error al desaparecer */
    .fade-out {
        opacity: 0;
        transition: opacity 1s ease-out;
    }
</style>
