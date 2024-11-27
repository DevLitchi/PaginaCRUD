<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Depuración: Ver los valores enviados
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Validar y recoger datos del formulario
    $workerName = trim($_POST['workerName'] ?? '');
    $workerRole = trim($_POST['workerRole'] ?? '');
    $workerEmail = trim($_POST['workerEmail'] ?? '');
    $proyecto_id = trim($_POST['proyecto_id'] ?? ''); // Cambiado aquí

    // Validar que todos los campos tengan datos
    if (!empty($workerName) && !empty($workerRole) && !empty($workerEmail) && !empty($proyecto_id)) {
        // Preparar consulta para evitar inyección SQL
        $stmt = $conn->prepare("INSERT INTO trabajadores (nombre, puesto, email, proyecto_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $workerName, $workerRole, $workerEmail, $proyecto_id);

        if ($stmt->execute()) {
            echo "Trabajador agregado exitosamente.";
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
