<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// Incluir la conexión a la base de datos
include 'db_connect.php';


// Obtener el ID del proyecto y el estado de la tarea desde la URL
$projectId = $_GET['project_id'];
$estado = $_GET['estado'];

// Consulta para obtener las tareas filtradas por estado
$query = "
    SELECT tasks.nombre AS task_name, trabajadores.nombre AS worker_name, trabajadores.puesto
    FROM tasks
    JOIN trabajadores ON tasks.trabajador_id = trabajadores.id
    WHERE tasks.proyecto_id = $projectId
    AND tasks.estado = '$estado'
";

$result = mysqli_query($conn, $query);

// Comprobar si hay resultados
if (mysqli_num_rows($result) > 0) {
    echo "<ul> ";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li><strong> 📒" . htmlspecialchars($row['task_name']) . "</strong><br> - Asignado a: " . htmlspecialchars($row['worker_name']) . "<br>- Puesto: " . htmlspecialchars($row['puesto']) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No hay tareas para este proyecto.</p>";
}

mysqli_close($conn);
