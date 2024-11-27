<?php
// Incluir la conexión a la base de datos
include 'db_connect.php';

// Obtener el ID del proyecto desde la URL
$projectId = $_GET['project_id'];

// Consulta para obtener los trabajadores asociados con el proyecto
$query = "
    SELECT trabajadores.nombre, trabajadores.puesto
    FROM trabajadores
    WHERE trabajadores.proyecto_id = $projectId
";

$result = mysqli_query($conn, $query);

// Comprobar si hay resultados
if (mysqli_num_rows($result) > 0) {
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li> 👤" . htmlspecialchars($row['nombre']) . " puesto en  " . htmlspecialchars($row['puesto']) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No hay trabajadores asociados a este proyecto.</p>";
}

mysqli_close($conn);
?>
