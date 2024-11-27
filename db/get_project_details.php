<?php
// Incluir la conexión a la base de datos
include 'db_connect.php';

// Obtener el ID del proyecto desde la URL
$projectId = $_GET['project_id'];

// Consulta para obtener los detalles del proyecto
$query = "SELECT fecha_fin FROM proyectos WHERE id = $projectId";
$result = mysqli_query($conn, $query);

// Comprobar si hay resultados
if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode($row);
} else {
    echo json_encode(["fecha_fin" => null]);
}

mysqli_close($conn);
?>
