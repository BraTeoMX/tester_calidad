@extends('layouts.app', ['pageSlug' => 'InspeccionEstampado', 'titlePage' => __('Inspeccion Estampado Despues del Horno')])

@section('content')
    <div class="content">
        <div class="card">
            <div class="card-header card-header-primary">
                <div class="row">
                    <div class="col-md-9">
                        <h3 class="card-title">Inspección Estampado Después del Horno</h3>
                    </div>
                    <div class="col-md-3 text-right">
                        Fecha: {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-primary">
                            <tr>
                                <th>Tipo de panel</th>
                                <th>Tipo de máquina</th>
                                <th>Tipo de técnica</th>
                                <th>Tipo de fibra</th>
                                <th>Valor de gráfica</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select class="form-control select2" name="categoriaTipoPanel" id="categoriaTipoPanel"></select>
                                </td>
                                <td>
                                    <select class="form-control select2" name="categoriaTipoMaquina" id="categoriaTipoMaquina"></select>
                                </td>
                                <td>
                                    <select class="form-control select2" name="tipoTecnicaScreen" id="tipoTecnicaScreen"></select>
                                </td>
                                <td>
                                    <select class="form-control select2" name="tipoFibraScreen" id="tipoFibraScreen"></select>
                                </td>
                                <td>
                                    <input type="text" class="form-control texto-blanco" name="valor_grafica" id="valor_grafica">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-body">
                {{-- Tabla con los Select2 en sus columnas correspondientes --}}
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-primary">
                            <tr>
                                <th>OP</th>
                                <th>Bulto</th>
                                <th>Cliente</th>
                                <th>Estilo</th>
                                <th>Color</th>
                                <th>Cantidad</th>
                                <th>Seleccionar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                {{-- Columna OP con Select2 --}}
                                <td>
                                    <select id="op-select" class="form-control"></select>
                                </td>

                                {{-- Columna Bulto con Select2 --}}
                                <td>
                                    <select id="bulto-select" class="form-control" disabled></select>
                                </td>

                                {{-- Columnas para mostrar los datos del bulto seleccionado --}}
                                <td id="cliente-cell"></td>
                                <td id="estilo-cell"></td>
                                <td id="color-cell"></td>
                                <td id="cantidad-cell"></td>
                                {{-- Columna con Checkboxes --}}
                                <td>
                                    <div class="form-check">
                                        <input type="checkbox" id="check-screen">
                                        <label for="check-screen">Screen</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" id="check-plancha">
                                        <label for="check-plancha">Plancha</label>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Tabla para Screen --}}
            <div class="card-body" id="table-screen" style="display: none;">
                <div class="table-responsive">
                    <p>Screen</p>
                    <table class="table">
                        <thead class="thead-primary">
                            <tr>
                                <th>Defecto</th>
                                <th>Acción Correctiva</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select class="form-control select2" id="defectoScreen"></select>
                                </td>
                                <td>
                                    <select class="form-control select2" id="accionCorrectivaScreen"></select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Tabla para Plancha --}}
            <div class="card-body" id="table-plancha" style="display: none;">
                <div class="table-responsive">
                    <p>Plancha</p>
                    <table class="table">
                        <thead class="thead-primary">
                            <tr>
                                <th>Piezas auditadas</th>
                                <th>Defecto</th>
                                <th>Acción Correctiva</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" class="form-control" name="piezas_auditadas">
                                </td>
                                <td>
                                    <select class="form-control select2" id="defectoPlancha"></select>
                                </td>
                                <td>
                                    <select class="form-control select2" id="accionCorrectivaPlancha"></select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        thead.thead-primary {
            background-color: #59666e54; /* Azul claro */
            color: #333;                 /* Color del texto */
        }
        .texto-blanco {
            color: white !important;
        }
        /* Ajusta Select2 dentro de las celdas de la tabla */
        td .select2-container {
            width: 100% !important;
        }

        /* Corrige el padding para que el Select2 no sobresalga */
        td .select2-selection {
            height: 100% !important;
            padding: 4px !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Asegurar que las celdas no se agranden demasiado */
        .table td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Si usas un tema oscuro, cambia los colores del Select2 */
        .select2-container--default .select2-selection--single {
            background-color: #1e1e1e; /* Color de fondo oscuro */
            color: #ffffff; /* Texto blanco */
            border: 1px solid #444; /* Borde más discreto */
        }

    </style>
     <style>
        /* Estilos base para el contenedor del checkbox */
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            cursor: pointer;
        }

        /* Ocultar el checkbox original */
        .form-check input[type="checkbox"] {
            display: none;
        }

        /* Crear el checkbox personalizado */
        .form-check label {
            position: relative;
            padding-left: 30px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-check label::before {
            content: "";
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            border: 2px solid #333;
            border-radius: 4px;
            background-color: #fff;
            transition: all 0.3s;
        }

        /* Icono de la palomita cuando está marcado */
        .form-check input[type="checkbox"]:checked + label::before {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        .form-check input[type="checkbox"]:checked + label::after {
            content: "✔";
            position: absolute;
            left: 5px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: white;
            font-weight: bold;
        }
    </style>

    <script>
        $(document).ready(function () {
            function cargarSelect2(selector, url, modelo) {
                $(selector).select2({
                    placeholder: "Seleccione una opción",
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            let results = $.map(data, function (item) {
                                return { id: item.id, text: item.nombre };
                            });

                            // Agregar la opción "OTRO" al inicio
                            results.unshift({ id: "otro", text: "OTRO" });

                            return { results: results };
                        },
                        cache: true
                    }
                });

                // Detectar cuando se selecciona "OTRO"
                $(selector).on("select2:select", function (e) {
                    let selectedValue = e.params.data.id;

                    if (selectedValue === "otro") {
                        let nuevoValor = prompt("Ingrese el nuevo valor para " + modelo + ":");

                        if (nuevoValor) {
                            nuevoValor = nuevoValor.toUpperCase(); // Convertir a mayúsculas

                            // Enviar el dato al backend por AJAX
                            $.ajax({
                                url: "/guardarNuevoValor",
                                type: "POST",
                                data: {
                                    nombre: nuevoValor,
                                    modelo: modelo,
                                    estatus: 1,
                                    _token: "{{ csrf_token() }}" // Necesario para Laravel
                                },
                                success: function (response) {
                                    if (response.success) {
                                        // Agregar el nuevo valor al select sin recargar
                                        let newOption = new Option(nuevoValor, response.id, true, true);
                                        $(selector).append(newOption).trigger('change');
                                    } else {
                                        alert("Error al guardar el nuevo valor.");
                                    }
                                },
                                error: function () {
                                    alert("Ocurrió un error. Intente de nuevo.");
                                }
                            });
                        }

                        // Resetear el select2 para que el usuario pueda seleccionar otro valor si cancela
                        $(selector).val(null).trigger('change');
                    }
                });
            }

            cargarSelect2("#categoriaTipoPanel", "/categoriaTipoPanel", "CategoriaTipoPanel");
            cargarSelect2("#categoriaTipoMaquina", "/categoriaTipoMaquina", "CategoriaTipoMaquina");
            cargarSelect2("#tipoTecnicaScreen", "/tipoTecnicaScreen", "Tipo_Tecnica");
            cargarSelect2("#tipoFibraScreen", "/tipoFibraScreen", "Tipo_Fibra");
        });
    </script>
    <script>
        $(document).ready(function () {
            function cargarSelect2(selector, url, modelo) {
                $(selector).select2({
                    placeholder: "Seleccione una opción",
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            let results = $.map(data, function (item) {
                                return { id: item.id, text: item.nombre };
                            });
    
                            // Agregar "OTRO" al inicio
                            results.unshift({ id: "otro", text: "OTRO" });
    
                            return { results: results };
                        },
                        cache: true
                    }
                });
    
                // Detectar selección de "OTRO"
                $(selector).on("select2:select", function (e) {
                    let selectedValue = e.params.data.id;
    
                    if (selectedValue === "otro") {
                        let nuevoValor = prompt("Ingrese el nuevo valor para " + modelo + ":");
    
                        if (nuevoValor) {
                            nuevoValor = nuevoValor.toUpperCase(); // Convertir a mayúsculas
    
                            // Enviar el dato al backend por AJAX
                            $.ajax({
                                url: "/guardarNuevoValorDA",
                                type: "POST",
                                data: {
                                    nombre: nuevoValor,
                                    modelo: modelo,
                                    estatus: 1,
                                    _token: "{{ csrf_token() }}" // CSRF Token para Laravel
                                },
                                success: function (response) {
                                    if (response.success) {
                                        // Agregar el nuevo valor al select sin recargar
                                        let newOption = new Option(nuevoValor, response.id, true, true);
                                        $(selector).append(newOption).trigger('change');
                                    } else {
                                        alert("Error al guardar el nuevo valor.");
                                    }
                                },
                                error: function () {
                                    alert("Ocurrió un error. Intente de nuevo.");
                                }
                            });
                        }
    
                        // Resetear el select2 si el usuario cancela
                        $(selector).val(null).trigger('change');
                    }
                });
            }
    
            // Cargar Select2 para Screen
            cargarSelect2("#defectoScreen", "/defectoScreen", "CatalogoDefectosScreen");
            cargarSelect2("#accionCorrectivaScreen", "/accionCorrectivaScreen", "CategoriaAccionCorrectScreen");
    
            // Cargar Select2 para Plancha
            cargarSelect2("#defectoPlancha", "/defectoPlancha", "CatalogoDefectosScreen");
            cargarSelect2("#accionCorrectivaPlancha", "/accionCorrectivaPlancha", "CategoriaAccionCorrectScreen");
        });
    </script>
    
    <script>
        $(document).ready(function() {

            /**
             * 1) Inicializa el select2 para la OP (prodid)
             */
            $('#op-select').select2({
                placeholder: 'Busca una OP...',
                minimumInputLength: 4,
                width: '100%', // IMPORTANTE: Hace que se ajuste a la celda
                dropdownAutoWidth: true,
                language: {
                    inputTooShort: function(args) {
                        var remainingChars = args.minimum - args.input.length;
                        return `Por favor, ingresa al menos ${remainingChars} caracter(es) más.`;
                    },
                    noResults: function() {
                        return 'No se encontraron resultados';
                    },
                    searching: function() {
                        return 'Buscando...';
                    }
                },
                ajax: {
                    url: '{{ route("search.ops.screen") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });

            /**
             * 2) Cuando seleccionamos una OP, habilitamos y cargamos el select2 de bultos
             */
            $('#op-select').on('select2:select', function(e) {
                var selectedOp = e.params.data.id;

                // Limpiar y deshabilitar el select de bultos antes de volverlo a llenar
                $('#bulto-select').html('').prop('disabled', true).val(null).trigger('change');

                // Limpiar celdas de la tabla
                $('#cliente-cell').text('');
                $('#estilo-cell').text('');
                $('#color-cell').text('');
                $('#cantidad-cell').text('');

                // Inicializa de nuevo el select2 para bultos, ahora con la OP seleccionada
                $('#bulto-select').select2({
                    placeholder: 'Selecciona un bulto...',
                    minimumInputLength: 0, // Permite que se muestren resultados sin escribir nada
                    width: '100%', // IMPORTANTE: Hace que se ajuste a la celda
                    dropdownAutoWidth: true,
                    language: {
                        // Puedes quitar la función inputTooShort ya que no será necesaria
                        noResults: function() {
                            return 'No se encontraron bultos para la OP seleccionada';
                        },
                        searching: function() {
                            return 'Buscando...';
                        }
                    },
                    ajax: {
                        url: '{{ route("search.bultos.op.screen") }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                op: selectedOp,  // Enviamos la OP seleccionada
                                q: params.term   // Para filtrar según lo que se escriba
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    }
                });

                // Habilitamos el select de bultos
                $('#bulto-select').prop('disabled', false).focus();
            });

            /**
             * 3) Cuando seleccionamos un bulto, consultamos sus datos y los mostramos
             */
            $('#bulto-select').on('select2:select', function(e) {
                var bultoId = e.params.data.id; // Este es el ID de la tabla JobAQLHistorial

                $.ajax({
                    url: '/get-bulto-details-screen/' + bultoId,
                    type: 'GET',
                    success: function(response) {
                        // Llenamos las celdas con los datos del bulto
                        $('#cliente-cell').text(response.cliente);
                        $('#estilo-cell').text(response.estilo);
                        $('#color-cell').text(response.color);
                        $('#cantidad-cell').text(response.cantidad);
                    },
                    error: function(xhr) {
                        alert('Error al obtener los detalles del bulto.');
                        console.error(xhr);
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const checkScreen = document.getElementById("check-screen");
            const checkPlancha = document.getElementById("check-plancha");
            const tableScreen = document.getElementById("table-screen");
            const tablePlancha = document.getElementById("table-plancha");

            // Mostrar/Ocultar la tabla de Screen
            checkScreen.addEventListener("change", function () {
                tableScreen.style.display = checkScreen.checked ? "block" : "none";
            });

            // Mostrar/Ocultar la tabla de Plancha
            checkPlancha.addEventListener("change", function () {
                tablePlancha.style.display = checkPlancha.checked ? "block" : "none";
            });
        });
    </script>
@endsection