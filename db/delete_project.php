
<?php
// Conexión a la base de datos
include 'db_connect.php';

// Obtener datos de la solicitud
$data = json_decode(file_get_contents("php://input"), true);
$projectId = $data['projectId'];

try {
    // Iniciar una transacción
    $conn->begin_transaction();

    // Eliminar las tareas relacionadas con el proyecto
    $deleteTasksQuery = "DELETE FROM tasks WHERE proyecto_id = ?";
    $stmt = $conn->prepare($deleteTasksQuery);
    $stmt->bind_param('i', $projectId);
    $stmt->execute();

    // Desasociar trabajadores del proyecto
    $updateWorkersQuery = "UPDATE trabajadores SET proyecto_id = NULL WHERE proyecto_id = ?";
    $stmt = $conn->prepare($updateWorkersQuery);
    $stmt->bind_param('i', $projectId);
    $stmt->execute();

    // Eliminar el proyecto
    $deleteProjectQuery = "DELETE FROM proyectos WHERE id = ?";
    $stmt = $conn->prepare($deleteProjectQuery);
    $stmt->bind_param('i', $projectId);
    $stmt->execute();

    // Confirmar la transacción
    $conn->commit();

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
