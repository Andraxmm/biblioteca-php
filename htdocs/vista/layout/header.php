<?php

    //Activa la sesión en caso de que no lo esté
    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }

   // Suprime la visualización de errores para evitar que se muestren al usuario final
    error_reporting(0);

    // Archivo de conexión a la bbdd
    include __DIR__ . '/../../config/connectiondb.php';

// Construimos la URL base (protocolo, host y, si corresponde, puerto)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$port = ($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
$baseUrl = $protocol . $_SERVER['HTTP_HOST'] . $port;

    //Verificamos que el usuario existe sino, nos redirige a index.php
    if (!isset($_SESSION['detsuid'])) {
        header("Location: " . $baseUrl . "/index.php");
        exit;
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="icon" href="/favicon.png" type="image/png">
    <!-- CDN de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <!-- Incluye FontAwesome para los iconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>

<body>
    <!-- Barra de navegación personalizada -->
    <nav class="navbar navbar-expand-lg" style="background-color:rgb(121, 88, 62);">
        <div class="container-fluid">
            <!-- Enlace con el nombre de la biblioteca -->
            <a class="navbar-brand mx-auto"  style="color: white; font-family: 'Poppins', sans-serif; font-weight: bold;">MI BIBLIOTECA</a>
        </div>
    </nav>
