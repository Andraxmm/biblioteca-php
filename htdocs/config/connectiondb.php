<?php
// Intentamos conectar a la bbdd usando mysqli_connect.
$con = mysqli_connect("localhost:3306", "root", "", "bibliotecapersonal");

// Validamos si la conexión fue exitosa, sino muestra un mensaje de error
if (mysqli_connect_errno()) {
    echo "Connection Fail" . mysqli_connect_error();
}