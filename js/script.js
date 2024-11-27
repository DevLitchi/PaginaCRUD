// Función para abrir el modal y cargar los detalles del proyecto
function showProjectDetails(proyecto_id) {
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('projectDetailsModal'));
    modal.show();

    // Limpiar las pestañas antes de cargar nuevos datos
    clearTabs();

    // Realizar solicitudes AJAX para obtener la información de cada pestaña
    loadDevelopers(proyecto_id);
    loadPendingTasks(proyecto_id);
    loadCompletedTasks(proyecto_id);
    calculateDaysRemaining(proyecto_id);
}

// Función para limpiar las pestañas
function clearTabs() {
    document.getElementById("developersTab").innerHTML = '';
    document.getElementById("pendingTasksTab").innerHTML = '';
    document.getElementById("completedTasksTab").innerHTML = '';
    document.getElementById("daysRemaining").innerText = '';
}

// Función para mostrar los detalles de los desarrolladores involucrados
function loadDevelopers(proyecto_id) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "get_workers.php?project_id=" + proyecto_id, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById("developersTab").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

// Función para mostrar las tareas pendientes
function loadPendingTasks(proyecto_id) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "get_tasks.php?project_id=" + proyecto_id + "&estado=pendiente", true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById("pendingTasksTab").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

// Función para mostrar las tareas finalizadas
function loadCompletedTasks(proyecto_id) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "get_tasks.php?project_id=" + proyecto_id + "&estado=finalizado", true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById("completedTasksTab").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

// Función para calcular los días restantes hasta la fecha de entrega
function calculateDaysRemaining(proyecto_id) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "get_project_details.php?project_id=" + proyecto_id, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const projectDetails = JSON.parse(xhr.responseText);
            const currentDate = new Date();
            const endDate = new Date(projectDetails.fecha_fin);
            const remainingDays = Math.ceil((endDate - currentDate) / (1000 * 3600 * 24));
            document.getElementById("daysRemaining").innerText = "Días restantes hasta la entrega final: " + remainingDays;
        }
    };
    xhr.send();
}
function deleteProject(proyectoId) {
    if (confirm('¿Estás seguro de que deseas eliminar este proyecto? Los trabajadores permanecerán pero sin asignaciones.')) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_project.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    alert('Proyecto eliminado con éxito.');
                    location.reload(); // Recargar la página
                } else {
                    alert('Error al eliminar el proyecto: ' + response.message);
                }
            }
        };
        xhr.send(JSON.stringify({ projectId: proyectoId }));
    }
}