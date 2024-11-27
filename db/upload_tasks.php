<?php
header('Content-Type: application/json'); // Respuesta en formato JSON

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger datos del formulario
    $taskName = trim($_POST['taskName'] ?? '');
    $taskDescription = trim($_POST['taskDescription'] ?? '');
    $taskStartDate = trim($_POST['taskStartDate'] ?? '');
    $taskEndDate = trim($_POST['taskEndDate'] ?? '');
    $taskProject = trim($_POST['taskProject'] ?? '');
    $taskWorkers = isset($_POST['taskWorkers']) ? implode(",", $_POST['taskWorkers']) : '';
    $taskStatus = trim($_POST['taskStatus'] ?? 'pendiente');

    // Validar que los campos no estén vacíos
    if (!empty($taskName) && !empty($taskDescription) && !empty($taskStartDate) && !empty($taskEndDate) && !empty($taskProject)) {
        // Preparar la consulta para evitar inyección SQL
        $stmt = $conn->prepare("INSERT INTO tasks (nombre, descripcion, fecha_inicio, fecha_fin, proyecto_id, trabajador_id, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiss", $taskName, $taskDescription, $taskStartDate, $taskEndDate, $taskProject, $taskWorkers, $taskStatus);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Tarea agregada exitosamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al guardar la tarea: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Todos los campos son obligatorios.']);
    }

    mysqli_close($conn);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido.']);
}
?>
