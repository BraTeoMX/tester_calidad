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
                            <button type="button" class="btn btn-info" onclick="openCreateModal()">
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
                                    <th>Estatus</th>
                                    <th>Planta</th>
                                    <th class="text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($turnos as $turno)
                                <tr id="row-{{ $turno->id }}">
                                    <td>{{ $turno->id }}</td>
                                    <td>{{ $turno->nombre }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm {{ $turno->estatus_badge_class }}" onclick="toggleStatus('{{ $turno->id }}')" title="Cambiar Estatus">
                                            {{ $turno->estatus_label }}
                                        </button>
                                    </td>
                                    <td>
                                        <span class="badge {{ $turno->planta_badge }}">{{ $turno->planta_label }}</span>
                                    </td>
                                    <td class="text-right">
                                        <button type="button" class="btn btn-icon btn-sm btn-info" onclick="editTurno('{{ $turno->id }}')">
                                            <i class="tim-icons icon-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-sm btn-danger" onclick="deleteTurno('{{ $turno->id }}')">
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

                <div class="form-group">
                    <label>Planta</label>
                    <select class="form-control" id="planta" name="planta" required style="color: black;">
                        <option value="" disabled selected>Seleccione una planta</option>
                        <option value="1">Ixtlahuaca</option>
                        <option value="2">San Bartolo</option>
                        <option value="0">Ambos</option>
                    </select>
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
                                    <input type="time" name="horarios[{{ $num }}][fin]" id="fin_{{ $num }}" class="form-control" style="color: black;" disabled>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="btn btn-dark" onclick="closeModal()">Cancelar</button>
                <button type="submit" class="btn btn-info">Guardar</button>
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
        border: 1px solid #155ee6ff;
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

    /* Asegurar que SweetAlert aparezca por encima del modal */
    .swal2-container {
        z-index: 10000 !important;
    }

    /* Fix for select in dark mode if needed, assuming similar to inputs */
    .custom-modal-content select.form-control {
        color: white !important;
        border-color: #2b3553;
    }

    .custom-modal-content select.form-control option {
        color: black;
        /* Options usually need black text on white bg context or browser default */
    }
</style>

<script>
    // Event Listeners para Validación de Horarios
    $(document).ready(function() {
        // Al cambiar cualquier input de inicio
        $('input[name*="[inicio]"]').on('change', function() {
            let inicioInput = $(this);
            let dia = inicioInput.attr('id').split('_')[1]; // obtener numero de dia
            let finInput = $('#fin_' + dia);
            let horaInicio = inicioInput.val();

            if (horaInicio) {
                // Habilitar fin
                finInput.prop('disabled', false);
                // Establecer mínimo para fin (aunque input type time no soporta min dinámico en todos los browsers igual que date, ayuda)
                // Para validación real usamos JS en el change del fin.
                finInput.attr('min', horaInicio);

                // Si ya había un valor en fin y es menor o igual, limpiarlo o validar
                if (finInput.val() && finInput.val() <= horaInicio) {
                    // Opción: Limpiar para obligar a re-seleccionar
                    finInput.val('');
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'warning',
                        title: 'La hora fin se ha reiniciado porque debe ser mayor a la hora de inicio.',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            } else {
                // Si se borra el inicio, limpiar y deshabilitar fin
                finInput.val('');
                finInput.prop('disabled', true);
            }
        });

        // Al cambiar cualquier input de fin
        $('input[name*="[fin]"]').on('change', function() {
            let finInput = $(this);
            let dia = finInput.attr('id').split('_')[1];
            let inicioInput = $('#inicio_' + dia);

            let horaInicio = inicioInput.val();
            let horaFin = finInput.val();

            if (horaFin && horaInicio) {
                if (horaFin <= horaInicio) {
                    // Hora inválida
                    finInput.val(''); // Limpiar valor incorrecto
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'La hora de fin debe ser posterior a la hora de inicio.',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            }
        });
    });

    function openCreateModal() {
        $('#turnoForm')[0].reset();
        $('#turno_id').val('');
        $('#planta').val(''); // Reset select
        $('#turnoModalLabel').text('Crear Nuevo Turno');

        // Resetear todos los campos 'fin' a disabled
        $('input[name*="[fin]"]').prop('disabled', true);

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
            $('#planta').val(data.planta); // Set Planta
            $('#turnoModalLabel').text('Editar Turno: ' + data.nombre);

            // Resetear primero todos a disabled
            $('input[name*="[fin]"]').prop('disabled', true);

            if (data.horario_semanal) {
                // Iterar 1 al 7
                for (let i = 1; i <= 7; i++) {
                    if (data.horario_semanal[i]) {
                        // Set Inicio
                        $('#inicio_' + i).val(data.horario_semanal[i].inicio);

                        // Si hay inicio, habilitar fin
                        if (data.horario_semanal[i].inicio) {
                            $('#fin_' + i).prop('disabled', false);
                            $('#fin_' + i).attr('min', data.horario_semanal[i].inicio);
                        }

                        // Set Fin
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

        // VALIDACIÓN CLIENT-SIDE EXTRA
        // Verificar que si hay inicio, haya fin seleccionada
        let isValid = true;
        $('input[name*="[inicio]"]').each(function() {
            let startVal = $(this).val();
            if (startVal) {
                let dia = $(this).attr('id').split('_')[1];
                let endVal = $('#fin_' + dia).val();

                if (!endVal) {
                    isValid = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Falta hora de fin',
                        text: 'Has seleccionado una hora de inicio para el ' + getNombreDia(dia) + ' pero falta la hora de fin.',
                    });
                    return false; // Break loop
                }
            }
        });

        if (!isValid) return;

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

    function getNombreDia(num) {
        const dias = {
            1: 'Lunes',
            2: 'Martes',
            3: 'Miércoles',
            4: 'Jueves',
            5: 'Viernes',
            6: 'Sábado',
            7: 'Domingo'
        };
        return dias[num] || 'Día ' + num;
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

    function toggleStatus(id) {
        Swal.fire({
            title: '¿Cambiar Estatus?',
            text: "¿Deseas cambiar el estatus de este turno? Esto afectará la visibilidad en el sistema.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cambiar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/turnos/' + id + '/toggle-status',
                    type: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Actualizado',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // Actualizar botón en la vista sin recargar
                            let btn = $('#row-' + id).find('button[onclick^="toggleStatus"]');
                            btn.removeClass('badge-info badge-dark').addClass(response.badge_class);
                            btn.text(response.label);
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'No se pudo actualizar el estatus', 'error');
                    }
                });
            }
        });
    }
</script>
@endsection