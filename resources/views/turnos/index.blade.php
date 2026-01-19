@extends('layouts.app', ['pageSlug' => 'turnos'])

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Gestión de Turnos</h4>
                        </div>
                        <div class="col-4 text-right">
                            <button type="button" class="btn btn-sm btn-primary" onclick="openCreateModal()">
                                Nuevo Turno
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="turnosTable">
                            <thead class="text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th class="text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($turnos as $turno)
                                <tr id="row-{{ $turno->id }}">
                                    <td>{{ $turno->id }}</td>
                                    <td>{{ $turno->nombre }}</td>
                                    <td class="text-right">
                                        <button type="button" class="btn btn-icon btn-sm btn-primary" onclick="editTurno({{ $turno->id }})">
                                            <i class="tim-icons icon-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-sm btn-danger" onclick="deleteTurno({{ $turno->id }})">
                                            <i class="tim-icons icon-trash-simple"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Personalizado -->
<div id="customModal" class="custom-modal-overlay" style="display: none;">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="title" id="turnoModalLabel" style="margin: 0;">Gestionar Turno</h5>
            <button type="button" class="close-modal-btn" onclick="closeModal()">&times;</button>
        </div>
        <form id="turnoForm" onsubmit="submitTurno(event)">
            <div class="custom-modal-body">
                <input type="hidden" id="turno_id" name="turno_id">

                <div class="form-group">
                    <label>Nombre del Turno</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre del turno" required style="color: black;">
                </div>

                <h4 class="mt-4">Horario Semanal</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Día</th>
                                <th>Inicio</th>
                                <th>Fin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $dias = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'];
                            @endphp
                            @foreach($dias as $num => $nombre)
                            <tr>
                                <td>{{ $nombre }}</td>
                                <td>
                                    <input type="time" name="horarios[{{ $num }}][inicio]" id="inicio_{{ $num }}" class="form-control" style="color: black;">
                                </td>
                                <td>
                                    <input type="time" name="horarios[{{ $num }}][fin]" id="fin_{{ $num }}" class="form-control" style="color: black;">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<style>
    .custom-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .custom-modal-content {
        background: #27293d;
        /* Color de fondo del tema */
        padding: 20px;
        border-radius: 8px;
        width: 90%;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
        border: 1px solid #e14eca;
        /* Color de borde acorde al tema (magenta/purple) */
    }

    .custom-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding-bottom: 10px;
    }

    .close-modal-btn {
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
    }

    .custom-modal-footer {
        margin-top: 20px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 10px;
    }

    /* Input adjustments for dark theme in modal */
    .custom-modal-content input.form-control {
        color: white !important;
        /* Asegurar texto blanco en inputs */
        border-color: #2b3553;
    }

    .custom-modal-content input.form-control:focus {
        border-color: #e14eca;
    }
</style>

<script>
    function openCreateModal() {
        $('#turnoForm')[0].reset();
        $('#turno_id').val('');
        $('#turnoModalLabel').text('Crear Nuevo Turno');

        // Custom show logic
        $('#customModal').fadeIn(200);
        // $('body').css('overflow', 'hidden'); // Prevent background scrolling
    }

    function closeModal() {
        $('#customModal').fadeOut(200);
        // $('body').css('overflow', 'auto');
    }

    function editTurno(id) {
        // Mostrar loading o similar
        $.get('/turnos/' + id + '/edit', function(data) {
            $('#turnoForm')[0].reset();
            $('#turno_id').val(data.id);
            $('#nombre').val(data.nombre);
            $('#turnoModalLabel').text('Editar Turno: ' + data.nombre);

            if (data.horario_semanal) {
                // Iterar 1 al 7
                for (let i = 1; i <= 7; i++) {
                    if (data.horario_semanal[i]) {
                        $('#inicio_' + i).val(data.horario_semanal[i].inicio);
                        $('#fin_' + i).val(data.horario_semanal[i].fin);
                    }
                }
            }

            // Custom show logic
            $('#customModal').fadeIn(200);

        }).fail(function() {
            Swal.fire('Error', 'No se pudo cargar la información del turno', 'error');
        });
    }

    function submitTurno(e) {
        e.preventDefault();

        let id = $('#turno_id').val();
        let url = id ? '/turnos/' + id : '/turnos';
        let method = id ? 'PUT' : 'POST';
        let formData = $('#turnoForm').serialize();

        $.ajax({
            url: url,
            type: method,
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                closeModal();
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload(); // Recargar para actualizar tabla
                });
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorMessage = 'Error al guardar';
                if (errors) {
                    errorMessage = Object.values(errors).flat().join('\n');
                }
                Swal.fire('Error', errorMessage, 'error');
            }
        });
    }

    // Cerrar modal al hacer clic fuera del contenido
    $(window).click(function(e) {
        if (e.target.id === 'customModal') {
            closeModal();
        }
    });


    function deleteTurno(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/turnos/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire(
                            '¡Eliminado!',
                            response.message,
                            'success'
                        ).then(() => {
                            $('#row-' + id).remove();
                        });
                    },
                    error: function() {
                        Swal.fire('Error', 'No se pudo eliminar el turno', 'error');
                    }
                });
            }
        });
    }
</script>
@endsection