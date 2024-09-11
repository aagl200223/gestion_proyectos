$(document).ready(function() {
    $('#login-form').on('submit', function(event) {
        event.preventDefault();
        const correo = $('#correo').val();
        const contrasena = $('#contrasena').val();
        
        $.ajax({
            url: '/gestion_nominas/backend/controllers/auth.php?action=login',
            type: 'POST',
            data: {
                correo: correo,
                contrasena: contrasena
            },
            success: function(response) {
                const data = JSON.parse(response);
                if (data.token) {
                    localStorage.setItem('token', data.token);
                    alert('Inicio de sesión exitoso');
                    // Cargar la interfaz de gestión de empleados
                    cargarInterfazGestion();
                } else {
                    alert(data.message);
                }
            },
            error: function() {
                alert('Error en el servidor');
            }
        });
    });

    function cargarInterfazGestion() {
        $('#app').html(`
            <h2>Gestión de Empleados</h2>
            <button id="crear-empleado" class="btn btn-success mb-3">Crear Empleado</button>
            <div id="empleados-list"></div>
        `);

        $('#crear-empleado').on('click', function() {
            $('#app').html(`
                <h2>Crear Empleado</h2>
                <form id="crear-empleado-form">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="puesto">Puesto</label>
                        <input type="text" class="form-control" id="puesto" required>
                    </div>
                    <div class="form-group">
                        <label for="salario">Salario</label>
                        <input type="number" class="form-control" id="salario" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Crear</button>
                </form>
            `);

            $('#crear-empleado-form').on('submit', function(event) {
                event.preventDefault();
                const nombre = $('#nombre').val();
                const puesto = $('#puesto').val();
                const salario = $('#salario').val();
                
                $.ajax({
                    url: '/gestion_nominas/backend/controllers/empleados.php?action=create',
                    type: 'POST',
                    data: {
                        nombre: nombre,
                        puesto: puesto,
                        salario: salario
                    },
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                    },
                    success: function(response) {
                        const data = JSON.parse(response);
                        if (data.message === 'Empleado creado!') {
                            alert('Empleado creado exitosamente');
                            cargarInterfazGestion();
                        } else {
                            alert(data.message);
                        }
                    },
                    error: function() {
                        alert('Error en el servidor');
                    }
                });
            });
        });

        cargarListaEmpleados();
    }

    function cargarListaEmpleados() {
        $.ajax({
            url: '/gestion_nominas/backend/controllers/empleados.php?action=read',
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            success: function(response) {
                const empleados = JSON.parse(response);
                let empleadosHtml = '<ul class="list-group">';
                empleados.forEach(empleado => {
                    empleadosHtml += `
                        <li class="list-group-item">
                            <h5>${empleado.nombre}</h5>
                            <p>Puesto: ${empleado.puesto}</p>
                            <p>Salario: ${empleado.salario}</p>
                            <button class="btn btn-danger eliminar-empleado" data-id="${empleado.id}">Eliminar</button>
                        </li>
                    `;
                });
                empleadosHtml += '</ul>';
                $('#empleados-list').html(empleadosHtml);

                $('.eliminar-empleado').on('click', function() {
                    const id = $(this).data('id');
                    $.ajax({
                        url: `/gestion_nominas/backend/controllers/empleados.php?action=delete&id=${id}`,
                        type: 'DELETE',
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('token')
                        },
                        success: function(response) {
                            const data = JSON.parse(response);
                            if (data.message === 'Empleado eliminado!') {
                                alert('Empleado eliminado exitosamente');
                                cargarListaEmpleados();
                            } else {
                                alert(data.message);
                            }
                        },
                        error: function() {
                            alert('Error en el servidor');
                        }
                    });
                });
            },
            error: function() {
                alert('Error en el servidor');
            }
        });
    }
});
