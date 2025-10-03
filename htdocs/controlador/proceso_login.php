<?php
// Iniciamos la sesión.
session_start();

// Archivo de conexión a la base de datos
include __DIR__ . '/../config/connectiondb.php';

class Login
{
    private $con;

    // Constructor para inicializar la conexión.
    public function __construct($con)
    {
        $this->con = $con;
    }

    // Método para construir la URL base absoluta.
    private function getBaseUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $port = ($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
        return $protocol . $_SERVER['HTTP_HOST'] . $port;
    }

    // Método principal para autenticar al usuario.
    public function loginUsuario($nombre_usuario, $contrasenia)
    {
        // Validamos que ambos campos estén completos.
        if (!$this->camposCompletos($nombre_usuario, $contrasenia)) {
            $_SESSION['login_error'] = "Por favor, complete todos los campos.";
            header("Location: " . $this->getBaseUrl() . "/index.php");
            exit;
        }

        // Intentamos obtener los datos del usuario.
        $usuario = $this->obtenerUsuario($nombre_usuario);

        // Si no se encuentra el usuario, mostramos un error y redirigimos.
        if (!$usuario) {
            $_SESSION['login_error'] = "No se ha encontrado el usuario";
            header("Location: " . $this->getBaseUrl() . "/index.php");
            exit;
        }

        // Verificamos la contraseña ingresada con la encriptada en la base de datos.
        if ($this->verificarContrasenia($contrasenia, $usuario['contrasenia'])) {
            // Se establecen las variables de sesión.
            $_SESSION['detsuid'] = $usuario['id_usuario'];
            $_SESSION['role'] = ($usuario['role_id'] == 1) ? 'admin' : 'usuario';
            header("Location: " . $this->getBaseUrl() . "/vista/dashboard/login_exitoso.php");
            exit;
        } else {
            // Si la contraseña no coincide, se muestra un error y se redirige.
            $_SESSION['login_error'] = "Contraseña incorrecta";
            header("Location: " . $this->getBaseUrl() . "/index.php");
            exit;
        }
    }

    // Verifica que los campos no estén vacíos.
    private function camposCompletos($nombre_usuario, $contrasenia)
    {
        return !empty($nombre_usuario) && !empty($contrasenia);
    }

    // Realiza la consulta para obtener los datos del usuario.
    private function obtenerUsuario($nombre_usuario)
    {
        $stmt = $this->con->prepare("SELECT id_usuario, contrasenia, role_id FROM usuarios WHERE nombre_usuario=?");
        $stmt->bind_param("s", $nombre_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return ($result->num_rows > 0) ? $result->fetch_assoc() : false;
    }

    // Compara la contraseña ingresada con la almacenada.
    private function verificarContrasenia($contrasenia, $contrasenia_hasheada)
    {
        return password_verify($contrasenia, $contrasenia_hasheada);
    }
}

// Verificamos si se envió el formulario de inicio de sesión.
if (isset($_POST['login'])) {
    $login = new Login($con);
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasenia = $_POST['contrasenia'];
    $login->loginUsuario($nombre_usuario, $contrasenia);
}
?>
