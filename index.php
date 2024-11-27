<?php
// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyectos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <!-- Topper -->
    <div class="topper d-flex justify-content-between align-items-center px-4 py-3">
        <a href="home.html" class="title-link">
            <h1 class="title">T-MIGHT</h1>
        </a>



        <div class="button-group">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">Agregar ➕</button>
            <button class="btn btn-danger" onclick="location.href='home.html';">Salir 📤</button>
        </div>

    </div>

    <!-- Contenido Principal -->
    <div class="container mt-4">
        <h2 class="text-center mb-4">Sistema de Administrador</h2>

        <section id="projects" class="row">
            <?php
            include './db/db_connect.php';

            $query = "SELECT * FROM proyectos";
            $result = mysqli_query($conn, $query);

            if (!$result) {
                die('Error en la consulta: ' . mysqli_error($conn));
            }

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "
                <div class='col-md-4 mb-4'>
                    <div class='card' style='border-radius: 10px;'>
                        <div class='card-body'>
                            <h5 class='card-title'>" . htmlspecialchars($row['nombre']) . "</h5>
                            <p><strong>Descripción:</strong> " . htmlspecialchars($row['descripcion']) . "</p>
                            <p><strong>📅Inicio:</strong> " . htmlspecialchars($row['fecha_inicio']) . "<strong><br>📅Entrega: </strong>" . htmlspecialchars($row['fecha_fin']) . "</p>
                            <div class='d-flex justify-content-between'>
                                <button class='btn btn-info' onclick='showProjectDetails(" . $row['id'] . ")'>Detalles</button>
                                <button class='btn btn-danger' onclick='deleteProject(" . $row['id'] . ")'>Eliminar</button>
                            </div>
                        </div>
                        <div class='card-footer text-muted'>
                            Proyecto # " . $row['id'] . "
                        </div>
                    </div>
                </div>
            ";
                }
            } else {
                echo "<p>No hay proyectos disponibles.</p>";
            }

            mysqli_close($conn);
            ?>
        </section>


        <footer class="bottom py-3 text-center text-white">
            <p class="m-0">© <?php echo date("Y"); ?> Gestión de Proyectos - Todos los derechos reservados.</p>
        </footer>

        <!-- Modal para Agregar Proyecto, Trabajador o Tarea -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="addModalLabel">Administrador</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h4>Seleccione una opción para agregar:</h4>
                        <div class="list-group">
                            <button class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                                Agregar Proyecto
                            </button>
                            <button class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#addWorkerModal">
                                Agregar Trabajador
                            </button>
                            <button class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                                Agregar Tarea
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Agregar Proyecto -->
        <div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addprojectDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="addprojectDetailsModalLabel">Agregar Proyecto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Formulario para agregar proyecto -->
                        <form id="projectForm">
                            <div class="mb-3">
                                <label for="projectName" class="form-label">Nombre del Proyecto</label>
                                <input type="text" class="form-control" id="projectName" name="projectName" required>
                            </div>
                            <div class="mb-3">
                                <label for="projectDescription" class="form-label">Descripción</label>
                                <textarea class="form-control" id="projectDescription" name="projectDescription" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="projectStartDate" class="form-label">Fecha de Inicio</label>
                                <input type="date" class="form-control" id="projectStartDate" name="projectStartDate" required>
                            </div>
                            <div class="mb-3">
                                <label for="projectEndDate" class="form-label">Fecha de Fin</label>
                                <input type="date" class="form-control" id="projectEndDate" name="projectEndDate" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar Proyecto</button>
                        </form>
                        <div id="responseMessage" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('projectForm').addEventListener('submit', async function(e) {
                e.preventDefault(); // Evitar que el formulario recargue la página

                const formData = new FormData(this); // Recoger los datos del formulario

                try {
                    const response = await fetch('./db/upload_project.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json(); // Asumimos que el servidor devuelve JSON
                    const messageDiv = document.getElementById('responseMessage');
                    if (response.ok) {
                        //Recaargar la página después de agregar un proyecto
                        messageDiv.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
                        this.reset(); // Reiniciar el formulario después del envío exitoso
                        //Esperar 2 segundos antes de recargar la página
                        setTimeout(() => window.location.reload(), 5000);

                    } else {
                        messageDiv.innerHTML = `<div class="alert alert-danger">${result.error}</div>`;
                    }
                } catch (error) {
                    document.getElementById('responseMessage').innerHTML = `
                <div class="alert alert-danger">Error al enviar los datos: ${error.message}</div>
            `;
                }
            });
        </script>




        <!-- Modal para Agregar Trabajador -->
        <div class="modal fade" id="addWorkerModal" tabindex="-1" aria-labelledby="addWorkerModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="addWorkerModalLabel">Agregar Trabajador</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Formulario para agregar trabajador -->
                        <form id="workerForm">
                            <div class="mb-3">
                                <label for="workerName" class="form-label">Nombre del Trabajador</label>
                                <input type="text" class="form-control" id="workerName" name="workerName" required>
                            </div>
                            <div class="mb-3">
                                <label for="workerRole" class="form-label">Rol del Trabajador</label>
                                <input type="text" class="form-control" id="workerRole" name="workerRole" required>
                            </div>
                            <div class="mb-3">
                                <label for="workerEmail" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="workerEmail" name="workerEmail" required>
                            </div>
                            <div class="mb-3">
                                <label for="projectAssociation" class="form-label">Asociar a Proyecto</label>
                                <select class="form-select" id="projectAssociation" name="proyecto_id" required>
                                    <option value="">Seleccione un Proyecto</option>
                                    <?php
                                    include './db/db_connect.php';
                                    $query = "SELECT id, nombre FROM proyectos";
                                    $result = mysqli_query($conn, $query);
                                    while ($project = mysqli_fetch_assoc($result)) {
                                        echo "<option value='" . htmlspecialchars($project['id']) . "'>" . htmlspecialchars($project['nombre']) . "</option>";
                                    }
                                    mysqli_close($conn);
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar Trabajador</button>
                        </form>
                        <div id="workerResponseMessage" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('workerForm').addEventListener('submit', async function(e) {
                e.preventDefault(); // Evitar que el formulario recargue la página

                const formData = new FormData(this); // Recoger los datos del formulario

                try {
                    const response = await fetch('./db/upload_workers.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json(); // Asumimos que el servidor devuelve JSON
                    const messageDiv = document.getElementById('workerResponseMessage');
                    if (response.ok) {
                        messageDiv.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
                        this.reset(); // Reiniciar el formulario después del envío exitoso
                    } else {
                        messageDiv.innerHTML = `<div class="alert alert-danger">${result.error}</div>`;
                    }
                } catch (error) {
                    document.getElementById('workerResponseMessage').innerHTML = `
                <div class="alert alert-danger">Error al enviar los datos: ${error.message}</div>
            `;
                }
            });
        </script>



        <!-- Modal para Agregar Tarea -->
        <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="addTaskModalLabel">Agregar Tarea</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Formulario para agregar tarea -->
                        <form id="taskForm">
                            <div class="mb-3">
                                <label for="taskName" class="form-label">Nombre de la Tarea</label>
                                <input type="text" class="form-control" id="taskName" name="taskName" required>
                            </div>
                            <div class="mb-3">
                                <label for="taskDescription" class="form-label">Descripción</label>
                                <textarea class="form-control" id="taskDescription" name="taskDescription" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="taskStartDate" class="form-label">Fecha de Inicio</label>
                                <input type="date" class="form-control" id="taskStartDate" name="taskStartDate" required>
                            </div>
                            <div class="mb-3">
                                <label for="taskEndDate" class="form-label">Fecha de Fin</label>
                                <input type="date" class="form-control" id="taskEndDate" name="taskEndDate" required>
                            </div>
                            <div class="mb-3">
                                <label for="taskAssociation" class="form-label">Asociar a Proyecto</label>
                                <select class="form-select" id="taskAssociation" name="taskProject" required>
                                    <option value="">Seleccione un Proyecto</option>
                                    <?php
                                    include './db/db_connect.php';
                                    $query = "SELECT id, nombre FROM proyectos";
                                    $result = mysqli_query($conn, $query);
                                    while ($project = mysqli_fetch_assoc($result)) {
                                        echo "<option value='" . htmlspecialchars($project['id']) . "'>" . htmlspecialchars($project['nombre']) . "</option>";
                                    }
                                    mysqli_close($conn);
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="taskWorkers" class="form-label">Seleccionar Trabajador</label>
                                <select class="form-select" id="taskWorkers" name="taskWorkers[]" multiple required>
                                    <?php
                                    include './db/db_connect.php';
                                    $query = "SELECT id, nombre FROM trabajadores";
                                    $result = mysqli_query($conn, $query);
                                    while ($worker = mysqli_fetch_assoc($result)) {
                                        echo "<option value='" . htmlspecialchars($worker['id']) . "'>" . htmlspecialchars($worker['nombre']) . "</option>";
                                    }
                                    mysqli_close($conn);
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" name="taskStatus" value="pendiente">
                            <button type="submit" class="btn btn-primary">Guardar Tarea</button>
                        </form>
                        <div id="taskResponseMessage" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('taskForm').addEventListener('submit', async function(e) {
                e.preventDefault(); // Evitar recargar la página

                const formData = new FormData(this); // Recoger los datos del formulario

                try {
                    const response = await fetch('./db/upload_tasks.php', {
                        method: 'POST',
                        body: formData,
                    });

                    const result = await response.json(); // Manejar la respuesta en formato JSON
                    const messageDiv = document.getElementById('taskResponseMessage');
                    if (response.ok) {
                        messageDiv.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
                        this.reset(); // Reiniciar el formulario tras el éxito
                    } else {
                        messageDiv.innerHTML = `<div class="alert alert-danger">${result.error}</div>`;
                    }
                } catch (error) {
                    document.getElementById('taskResponseMessage').innerHTML = `
                <div class="alert alert-danger">Error al enviar los datos: ${error.message}</div>
            `;
                }
            });
        </script>

        <!-- Modal con pestañas -->
        <div id="projectDetailsModal" class="modal fade" tabindex="-1" aria-labelledby="projectDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #07131a; color: white;">
                        <h5 class="modal-title" id="projectDetailsModalLabel">Detalles del Proyecto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Pestañas -->
                        <ul class="nav nav-tabs" id="projectTabs" role="tablist" style="background-color: white;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab-developers" data-bs-toggle="tab" data-bs-target="#developersTab" type="button" role="tab">Desarrolladores Involucrados</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-pending" data-bs-toggle="tab" data-bs-target="#pendingTasksTab" type="button" role="tab">Tareas Pendientes</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-completed" data-bs-toggle="tab" data-bs-target="#completedTasksTab" type="button" role="tab">Tareas Finalizadas</button>
                            </li>
                        </ul>
                        <div class="tab-content mt-3">
                            <div class="tab-pane fade show active" id="developersTab" role="tabpanel"></div>
                            <div class="tab-pane fade" id="pendingTasksTab" role="tabpanel"></div>
                            <div class="tab-pane fade" id="completedTasksTab" role="tabpanel"></div>
                        </div>
                        <p id="daysRemaining"></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Mordal con edicion -->

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="/js/script.js"></script>
</body>

</html>