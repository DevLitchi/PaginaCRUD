<?php
header('Content-Type: application/json'); // Enviar respuesta en formato JSON

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger datos del formulario
    $workerName = trim($_POST['workerName'] ?? '');
    $workerRole = trim($_POST['workerRole'] ?? '');
    $workerEmail = trim($_POST['workerEmail'] ?? '');
    $proyecto_id = trim($_POST['proyecto_id'] ?? '');

    // Validar que todos los campos tengan datos
    if (!empty($workerName) && !empty($workerRole) && !empty($workerEmail) && !empty($proyecto_id)) {
        // Preparar consulta para evitar inyección SQL
        $stmt = $conn->prepare("INSERT INTO trabajadores (nombre, puesto, email, proyecto_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $workerName, $workerRole, $workerEmail, $proyecto_id);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Trabajador agregado exitosamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al guardar el trabajador: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Todos los campos son obligatorios.']);
    }

    mysqli_close($conn);
} else {
    http_response_code(405); // Método no permitido
    echo json_encode(['error' => 'Método no permitido.']);
}
?>
