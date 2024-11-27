<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger datos del formulario
    $taskName = $_POST['taskName'] ?? '';
    $taskDescription = $_POST['taskDescription'] ?? '';
    $taskStartDate = $_POST['taskStartDate'] ?? '';
    $taskEndDate = $_POST['taskEndDate'] ?? '';
    $taskProject = $_POST['taskProject'] ?? '';
    $taskWorkers = isset($_POST['taskWorkers']) ? implode(",", $_POST['taskWorkers']) : '';
    $taskStatus = $_POST['taskStatus'] ?? 'pendiente';

    // Validar datos antes de la consulta
    if (!empty($taskName) && !empty($taskDescription) && !empty($taskStartDate) && !empty($taskEndDate) && !empty($taskProject)) {
        // Preparar consulta para evitar inyección SQL
        $stmt = $conn->prepare("INSERT INTO tasks (nombre, descripcion, fecha_inicio, fecha_fin, proyecto_id, trabajador_id, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiss", $taskName, $taskDescription, $taskStartDate, $taskEndDate, $taskProject, $taskWorkers, $taskStatus);

        if ($stmt->execute()) {
            echo "Tarea agregada exitosamente.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Por favor, complete todos los campos.";
    }

    mysqli_close($conn);
}
?>
