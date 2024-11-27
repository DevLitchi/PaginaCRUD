// Función para abrir el modal y cargar los detalles del proyecto
function showProjectDetails(proyecto_id) {
    const modal = new bootstrap.Modal(document.getElementById('projectDetailsModal'));
    modal.show();

    clearTabs();

    // Realizar solicitudes `fetch` para obtener la información de cada pestaña
    loadDevelopers(proyecto_id);
    loadTasks(proyecto_id, "pendiente", "pendingTasksTab");
    loadTasks(proyecto_id, "finalizado", "completedTasksTab");
    calculateDaysRemaining(proyecto_id);
}

// Función para limpiar las pestañas
function clearTabs() {
    document.getElementById("developersTab").innerHTML = '';
    document.getElementById("pendingTasksTab").innerHTML = '';
    document.getElementById("completedTasksTab").innerHTML = '';
    document.getElementById("daysRemaining").innerText = '';
}

// Función para mostrar los desarrolladores involucrados
async function loadDevelopers(proyecto_id) {
    try {
        const response = await fetch(`/db/get_workers.php?project_id=${proyecto_id}`);
        if (response.ok) {
            const html = await response.text();
            document.getElementById("developersTab").innerHTML = html;
        }
    } catch (error) {
        console.error("Error al cargar desarrolladores:", error);
    }
}

// Función para mostrar tareas (pendientes o finalizadas)
async function loadTasks(proyecto_id, estado, targetTabId) {
    try {
        const response = await fetch(`/db/get_tasks.php?project_id=${proyecto_id}&estado=${estado}`);
        if (response.ok) {
            const html = await response.text();
            document.getElementById(targetTabId).innerHTML = html;
        }
    } catch (error) {
        console.error(`Error al cargar tareas (${estado}):`, error);
    }
}

// Función para calcular días restantes
async function calculateDaysRemaining(proyecto_id) {
    try {
        const response = await fetch(`/db/get_project_details.php?project_id=${proyecto_id}`);
        if (response.ok) {
            const projectDetails = await response.json();
            const currentDate = new Date();
            const endDate = new Date(projectDetails.fecha_fin);
            const remainingDays = Math.ceil((endDate - currentDate) / (1000 * 3600 * 24));
            document.getElementById("daysRemaining").innerText = `Días restantes hasta la entrega final: ${remainingDays}`;
        }
    } catch (error) {
        console.error("Error al calcular días restantes:", error);
    }
}

// Función para eliminar un proyecto
async function deleteProject(proyectoId) {
    if (confirm('¿Estás seguro de que deseas eliminar este proyecto? Los trabajadores permanecerán pero sin asignaciones.')) {
        try {
            const response = await fetch('/db/delete_project.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ projectId: proyectoId }),
            });

            if (response.ok) {
                const result = await response.json();
                if (result.status === 'success') {
                    alert('Proyecto eliminado con éxito.');
                    location.reload();
                } else {
                    alert('Error al eliminar el proyecto: ' + result.message);
                }
            } else {
                alert('Error al eliminar el proyecto.');
            }
        } catch (error) {
            console.error("Error al eliminar el proyecto:", error);
        }
    }
}
