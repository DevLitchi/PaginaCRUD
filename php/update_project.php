<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project_id = $_POST['project_id'];
    $projectName = trim($_POST['projectName']);
    $projectDescription = trim($_POST['projectDescription']);
    $projectEndDate = trim($_POST['projectEndDate']);
    $developers = $_POST['developers'] ?? [];
    $tasks = $_POST['tasks'] ?? [];

    // Actualizar datos básicos del proyecto
    $stmt = $conn->prepare("UPDATE proyectos SET nombre = ?, descripcion = ?, fecha_fin = ? WHERE id = ?");
    $stmt->bind_param("sssi", $projectName, $projectDescription, $projectEndDate, $project_id);
    $stmt->execute();

    // Actualizar desarrolladores relacionados
    $conn->query("DELETE FROM proyecto_desarrolladores WHERE proyecto_id = $project_id");
    foreach ($developers as $developer_id) {
        $conn->query("INSERT INTO proyecto_desarrolladores (proyecto_id, desarrollador_id) VALUES ($project_id, $developer_id)");
    }

    // Actualizar el estado de las tareas
    foreach ($tasks as $task_id) {
        $conn->query("UPDATE tareas SET estado = 'finalizada' WHERE id = $task_id");
    }

    echo "Proyecto actualizado correctamente.";
    header("Location: index.php");
    exit;
}
?>
