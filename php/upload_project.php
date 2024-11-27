<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar y recoger datos del formulario
    $projectName = trim($_POST['projectName'] ?? '');
    $projectDescription = trim($_POST['projectDescription'] ?? '');
    $projectStartDate = trim($_POST['projectStartDate'] ?? '');
    $projectEndDate = trim($_POST['projectEndDate'] ?? '');

    // Validar que todos los campos tengan datos
    if (!empty($projectName) && !empty($projectDescription) && !empty($projectStartDate) && !empty($projectEndDate)) {
        // Preparar consulta para evitar inyección SQL
        $stmt = $conn->prepare("INSERT INTO proyectos (nombre, descripcion, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $projectName, $projectDescription, $projectStartDate, $projectEndDate);

        if ($stmt->execute()) {
            echo "Proyecto agregado exitosamente.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Por favor, completa todos los campos del formulario.";
    }

    mysqli_close($conn);
}
?>
