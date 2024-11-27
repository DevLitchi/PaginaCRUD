<?php
header('Content-Type: application/json'); // Enviar respuesta en formato JSON

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger datos del formulario
    $projectName = trim($_POST['projectName'] ?? '');
    $projectDescription = trim($_POST['projectDescription'] ?? '');
    $projectStartDate = trim($_POST['projectStartDate'] ?? '');
    $projectEndDate = trim($_POST['projectEndDate'] ?? '');

    if (!empty($projectName) && !empty($projectDescription) && !empty($projectStartDate) && !empty($projectEndDate)) {
        // Preparar consulta para evitar inyección SQL
        $stmt = $conn->prepare("INSERT INTO proyectos (nombre, descripcion, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $projectName, $projectDescription, $projectStartDate, $projectEndDate);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Proyecto agregado exitosamente, agrega otro o sal del modal para interactuar con el.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al guardar el proyecto: ' . $stmt->error]);
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
