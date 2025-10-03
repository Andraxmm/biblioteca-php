<?php
class GestorSesion
{
    
    private function getBaseUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $port = ($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
        return $protocol . $_SERVER['HTTP_HOST'] . $port;
    }

    public function __construct()
    {
        // Inicia la sesión si no está activa
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Verifica si existe el identificador del usuario en la sesión
        if (empty($_SESSION['usuario_id'])) {
            $this->redirigirInicio();
        }
    }

    public function cerrarSesion()
    {
        // Limpia todas las variables de la sesión
        $_SESSION = [];

        // Destruye la sesión actual
        session_destroy();

        // Redirige a la página principal con un parámetro de mensaje indicando el cierre exitoso
        header("Location: " . $this->getBaseUrl() . "/index.php?mensaje=cierre_sesion_exitoso");
        exit();
    }

    private function redirigirInicio()
    {
        // Redirige a la página principal si no se encuentra el usuario
        header("Location: " . $this->getBaseUrl() . "/index.php");
        exit();
    }
}

// Instancia el objeto GestorSesion
$gestorSesion = new GestorSesion();

// Ejecuta el cierre de sesión
$gestorSesion->cerrarSesion();
?>
