<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "administrador";

// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Comprobar la conexión
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
