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
            <form id="formInspeccion" method="POST" action="{{ route('inspeccionEstampadoHorno.store') }}">
                @csrf <!-- Token de seguridad obligatorio en Laravel -->
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
                                        <select class="form-control select2" name="tipo_panel" id="categoriaTipoPanel" required></select>
                                    </td>
                                    <td>
                                        <select class="form-control select2" name="tipo_maquina" id="categoriaTipoMaquina" required></select>
                                    </td>
                                    <td>
                                        <select class="form-control select2" name="tecnica_screen" id="tipoTecnicaScreen"></select>
                                        <div id="listaTipoTecnicaScreen" class="mt-2"></div>
                                    </td>
                                    <td>
                                        <select class="form-control select2" name="tipoFibraScreen" id="tipoFibraScreen"></select>
                                        <div id="listaTipoFibraScreen" class="mt-2"></div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control texto-blanco" name="valor_grafica" id="valor_grafica" required>
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
                                        <select id="op-select" name="op_select" class="form-control" required></select>
                                    </td>

                                    {{-- Columna Bulto con Select2 --}}
                                    <td>
                                        <select id="bulto-select" name="bulto_select" class="form-control" disabled required></select>
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
                                    <th>Tecnico</th>
                                    <th>Defecto</th>
                                    <th>Acción Correctiva</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select class="form-control select2" name="nombre_tecnico_screen" id="nombreTecnicoScreen" required></select>
                                    </td>
                                    <td>
                                        <select class="form-control select2" id="defectoScreen"></select>
                                        <div id="listaDefectoScreen" class="mt-2"></div>
                                    </td>
                                    <td>
                                        <select class="form-control select2" name="accion_correctiva_screen" id="accionCorrectivaScreen" required></select>
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
                                    <th>Tecnico</th>
                                    <th>Piezas auditadas</th>
                                    <th>Defecto</th>
                                    <th>Acción Correctiva</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select class="form-control select2" name="nombre_tecnico_plancha" id="nombreTecnicoPlancha"></select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="piezas_auditadas" required>
                                    </td>
                                    <td>
                                        <select class="form-control select2" id="defectoPlancha"></select>
                                        <div id="listaDefectoPlancha" class="mt-2"></div>
                                    </td>
                                    <td>
                                        <select class="form-control select2" name="accion_correctiva_plancha" id="accionCorrectivaPlancha" required></select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-body mt-4">
                    <button type="submit" class="btn-verde-xd">Guardar Inspección</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .btn-verde-xd {
            color: #fff !important;
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            box-shadow: 0 4px 6px rgba(50, 50, 93, .11), 0 1px 3px rgba(0, 0, 0, .08) !important;
            padding: 0.5rem 2rem;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 10px;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            cursor: pointer;
        }

        .btn-verde-xd:hover {
            color: #fff !important;
            background-color: #218838 !important;
            border-color: #1e7e34 !important;
        }

        .btn-verde-xd:focus,
        .btn-verde-xd.focus {
            box-shadow: 0 4px 6px rgba(50, 50, 93, .11), 0 1px 3px rgba(0, 0, 0, .08), 0 0 0 0.2rem rgba(40, 167, 69, 0.5) !important;
        }

        .btn-verde-xd:disabled,
        .btn-verde-xd.disabled {
            color: #ffffff !important; 
            background-color: #4bce67 !important; /* Verde más claro */
            border-color: #4bce67 !important;
            cursor: not-allowed !important; /* Cursor de "prohibido" */
            opacity: 0.6; /* Reduce opacidad */
            box-shadow: none !important; /* Elimina sombra */
        }

        .btn-verde-xd:not(:disabled):not(.disabled).active,
        .btn-verde-xd:not(:disabled):not(.disabled):active,
        .show>.btn-verde-xd.dropdown-toggle {
            color: #fff !important;
            background-color: #1e7e34 !important;
            border-color: #1c7430 !important;
        }

        .btn-verde-xd:not(:disabled):not(.disabled).active:focus,
        .btn-verde-xd:not(:disabled):not(.disabled).active:focus,
        .show>.btn-verde-xd.dropdown-toggle:focus {
            box-shadow: none, 0 0 0 0.2rem rgba(40, 167, 69, 0.5) !important;
        }
    </style>
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
            function cargarSelect2Simple(selector, url) {
                $(selector).select2({
                    placeholder: "Seleccione una opción",
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            let results = $.map(data, function (item) {
                                return { id: item.nombre, text: item.nombre }; // Aquí cambiamos item.id por item.nombre
                            });
                            return { results: results };
                        },
                        cache: true
                    }
                });
            }

            // Llamamos a la función sin la opción "OTRO" ni la funcionalidad para agregar nuevos registros.
            cargarSelect2Simple("#nombreTecnicoScreen", "/categoriaTecnicoScreen");
            cargarSelect2Simple("#nombreTecnicoPlancha", "/categoriaTecnicoScreen");
        });
    </script>
    <!-- Script general para los select comunes -->
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

                            results.unshift({ id: "otro", text: "OTRO" });

                            return { results: results };
                        },
                        cache: true
                    }
                });

                $(selector).on("select2:select", function (e) {
                    let selectedValue = e.params.data.id;

                    if (selectedValue === "otro") {
                        let nuevoValor = prompt("Ingrese el nuevo valor para " + modelo + ":");

                        if (nuevoValor) {
                            nuevoValor = nuevoValor.toUpperCase();

                            $.ajax({
                                url: "/guardarNuevoValor",
                                type: "POST",
                                data: {
                                    nombre: nuevoValor,
                                    modelo: modelo,
                                    estatus: 1,
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function (response) {
                                    if (response.success) {
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

                        $(selector).val(null).trigger('change');
                    }
                });
            }

            cargarSelect2("#categoriaTipoPanel", "/categoriaTipoPanel", "CategoriaTipoPanel");
            cargarSelect2("#categoriaTipoMaquina", "/categoriaTipoMaquina", "CategoriaTipoMaquina");
        });
    </script>

    <!-- Script exclusivo para tipoTecnicaScreen -->
    <script>
        $(document).ready(function () {
          // Array para rastrear los IDs de opciones seleccionadas (siempre como string)
          let opcionesSeleccionadas = [];
      
          function cargarTipoTecnicaScreen() {
            $("#tipoTecnicaScreen").select2({
              placeholder: "Seleccione una opción",
              ajax: {
                url: "/tipoTecnicaScreen",
                dataType: "json",
                delay: 250,
                cache: true,
                processResults: function (data) {
                  let results = $.map(data, function (item) {
                    // Forzamos el ID a string
                    return { id: String(item.id), text: item.nombre };
                  });
      
                  // Agregamos la opción "OTRO" al inicio (también como string)
                  results.unshift({ id: "otro", text: "OTRO" });
                  return { results: results };
                }
              }
            });
      
            // Evento de selección
            $("#tipoTecnicaScreen").on("select2:select", function (e) {
                let selectedValue = String(e.params.data.id); // forzamos a string
                let selectedText  = e.params.data.text;
        
                if (selectedValue === "otro") {
                    let nuevoValor = prompt("Ingrese el nuevo valor para Tipo_Tecnica:");
                    if (nuevoValor) {
                    nuevoValor = nuevoValor.toUpperCase();
                    $.ajax({
                        url: "/guardarNuevoValor",
                        type: "POST",
                        data: {
                        nombre: nuevoValor,
                        modelo: "Tipo_Tecnica",
                        estatus: 1,
                        _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                        if (response.success) {
                            // Si el backend retorna response.id como número, lo convertimos a string
                            let newId   = String(response.id);
                            let newText = nuevoValor;
                            // Creamos la nueva opción y la seleccionamos
                            let newOption = new Option(newText, newId, true, true);
                            $("#tipoTecnicaScreen").append(newOption).trigger("change");
        
                            // Agregamos la nueva opción al div
                            agregarOpcionLista(newId, newText);
                        } else {
                            alert("Error al guardar el nuevo valor.");
                        }
                        },
                        error: function () {
                        alert("Ocurrió un error. Intente de nuevo.");
                        }
                    });
                    }
                    // Limpiar el select después de "OTRO"
                    $("#tipoTecnicaScreen").val(null).trigger("change");
        
                } else {
                    // Antes de agregar, verificamos si ya existe en el array
                    if (opcionesSeleccionadas.includes(selectedValue)) {
                    alert("La opción ya fue seleccionada.");
                    } else {
                    agregarOpcionLista(selectedValue, selectedText);
                    }
                    // Limpiar la selección en el select
                    $("#tipoTecnicaScreen").val(null).trigger("change");
                }
                });
            }
      
            function agregarOpcionLista(id, nombre) {
                if (!opcionesSeleccionadas.includes(id)) {
                    opcionesSeleccionadas.push(id);

                    // Generamos un bloque con el texto y un input hidden
                    $("#listaTipoTecnicaScreen").append(`
                    <div id="opcion-${id}" class="mb-2 p-2 border rounded">
                        <span>${nombre}</span>
                        <button class="btn btn-danger btn-sm ms-2" onclick="eliminarOpcion('${id}')">Eliminar</button>

                        <!-- El input hidden que se enviará en el form -->
                        <input 
                        type="hidden" 
                        name="tipo_tecnica_screen[]" 
                        value="${nombre}" 
                        />
                    </div>
                    `);
                }
            }
      
            window.eliminarOpcion = function (id) {
                // Forzamos el id a string, por seguridad
                id = String(id);
                // Quitamos el id del array
                opcionesSeleccionadas = opcionesSeleccionadas.filter(item => item !== id);
                // Eliminamos el div de la lista
                $("#opcion-" + id).remove();
            };
      
          cargarTipoTecnicaScreen();
        });
    </script>
    <!-- Script exclusivo para tipoFibraScreen -->
    <script>
        $(document).ready(function () {
            // Array para rastrear los IDs de opciones seleccionadas (siempre como string)
            let opcionesSeleccionadasFibra = [];
    
            function cargarTipoFibraScreen() {
                $("#tipoFibraScreen").select2({
                    placeholder: "Seleccione una opción",
                    ajax: {
                        url: "/tipoFibraScreen",
                        dataType: "json",
                        delay: 250,
                        cache: true,
                        processResults: function (data) {
                            let results = $.map(data, function (item) {
                                // Forzamos el ID a string
                                return { id: String(item.id), text: item.nombre };
                            });
    
                            // Agregamos la opción "OTRO" al inicio (también como string)
                            results.unshift({ id: "otro", text: "OTRO" });
                            return { results: results };
                        }
                    }
                });
    
                // Evento de selección
                $("#tipoFibraScreen").on("select2:select", function (e) {
                    let selectedValue = String(e.params.data.id); // Forzamos a string
                    let selectedText = e.params.data.text;
    
                    if (selectedValue === "otro") {
                        let nuevoValor = prompt("Ingrese el nuevo valor para Tipo_Fibra:");
                        if (nuevoValor) {
                            nuevoValor = nuevoValor.toUpperCase();
                            $.ajax({
                                url: "/guardarNuevoValor",
                                type: "POST",
                                data: {
                                    nombre: nuevoValor,
                                    modelo: "Tipo_Fibra",
                                    estatus: 1,
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function (response) {
                                    if (response.success) {
                                        // Convertimos el ID a string por seguridad
                                        let newId = String(response.id);
                                        let newText = nuevoValor;
                                        // Creamos la nueva opción y la seleccionamos
                                        let newOption = new Option(newText, newId, true, true);
                                        $("#tipoFibraScreen").append(newOption).trigger("change");
    
                                        // Agregamos la nueva opción al div
                                        agregarOpcionListaFibra(newId, newText);
                                    } else {
                                        alert("Error al guardar el nuevo valor.");
                                    }
                                },
                                error: function () {
                                    alert("Ocurrió un error. Intente de nuevo.");
                                }
                            });
                        }
                        // Limpiar el select después de "OTRO"
                        $("#tipoFibraScreen").val(null).trigger("change");
    
                    } else {
                        // Antes de agregar, verificamos si ya existe en el array
                        if (opcionesSeleccionadasFibra.includes(selectedValue)) {
                            alert("La opción ya fue seleccionada.");
                        } else {
                            agregarOpcionListaFibra(selectedValue, selectedText);
                        }
                        // Limpiar la selección en el select
                        $("#tipoFibraScreen").val(null).trigger("change");
                    }
                });
            }
    
            function agregarOpcionListaFibra(id, nombre) {
                if (!opcionesSeleccionadasFibra.includes(id)) {
                    opcionesSeleccionadasFibra.push(id);
    
                    // Insertamos el bloque con el texto, input hidden con el ID, y un input para la cantidad.
                    // Se asigna la clase "cantidad-fibra" para gestionar los eventos en los inputs.
                    $("#listaTipoFibraScreen").append(`
                        <div id="opcionFibra-${id}" class="mb-2 p-2 border rounded">
                            <span>${nombre}</span>
                            
                            <!-- Campo hidden con el nombre de la fibra -->
                            <input type="hidden" name="tipo_fibra_screen[${id}][nombre]" value="${nombre}"/>
                            
                            <!-- Campo para la cantidad -->
                            <input 
                                type="number" 
                                name="tipo_fibra_screen[${id}][cantidad]" 
                                id="cantidad-${id}" 
                                class="ms-2 cantidad-fibra" 
                                value="1" 
                                min="1" 
                                style="width: 60px;" 
                            />
                            
                            <button class="btn btn-danger btn-sm ms-2" onclick="eliminarOpcionFibra('${id}')">Eliminar</button>
                        </div>
                    `);
                }
            }
    
            // Función para eliminar un bloque de fibra
            window.eliminarOpcionFibra = function (id) {
                id = String(id);
                opcionesSeleccionadasFibra = opcionesSeleccionadasFibra.filter(item => item !== id);
                $("#opcionFibra-" + id).remove();
                // Cada vez que se elimina, se revalida la suma total
                validarSumaTotal();
            };
    
            // Función que recorre todos los inputs de cantidad y devuelve la suma total
            function recalcTotalFibra() {
                let total = 0;
                $("#listaTipoFibraScreen .cantidad-fibra").each(function(){
                    total += parseFloat($(this).val()) || 0;
                });
                return total;
            }
    
            // Función para validar la suma total y ajustar el input actual si se excede
            function validarInputCantidad($input) {
                // Calculamos la suma de las cantidades de los otros inputs
                let sumOtros = 0;
                $(".cantidad-fibra").not($input).each(function(){
                    sumOtros += parseFloat($(this).val()) || 0;
                });
                let currentVal = parseFloat($input.val()) || 0;
                let maxPermitido = 100 - sumOtros; // Lo máximo que puede tener este input sin exceder el total 100
    
                if(currentVal > maxPermitido) {
                    // Si el valor actual excede lo permitido, lo ajustamos
                    $input.val(maxPermitido);
                    alert("El valor máximo permitido para este campo es " + maxPermitido + " para no sobrepasar el total de 100.");
                }
            }
    
            // Función para validar la suma total; si la suma es menor a 100 y hay más de un registro,
            // se ajusta el último input para que la suma total sea 100.
            function validarSumaTotal() {
                let total = recalcTotalFibra();
                let $todos = $("#listaTipoFibraScreen .cantidad-fibra");
                if($todos.length > 0) {
                    // Si hay más de un registro y la suma es menor a 100, se fuerza en el último el valor restante.
                    if($todos.length > 1) {
                        let $ultimo = $todos.last();
                        let sumOtros = 0;
                        $todos.not($ultimo).each(function(){
                            sumOtros += parseFloat($(this).val()) || 0;
                        });
                        let nuevoValor = 100 - sumOtros;
                        // Solo se ajusta si el valor actual del último es distinto al requerido
                        if(parseFloat($ultimo.val()) !== nuevoValor) {
                            $ultimo.val(nuevoValor);
                        }
                    } else {
                        // Si es un solo registro, se permite hasta 100.
                        let $solo = $todos.first();
                        if(parseFloat($solo.val()) > 100){
                            $solo.val(100);
                        }
                    }
                }
            }
    
            // Delegamos el evento "input" en los inputs de cantidad (ya que se crean dinámicamente)
            $(document).on("input", ".cantidad-fibra", function(){
                let $input = $(this);
                validarInputCantidad($input);
                // Se puede actualizar la suma total si se requiere (o mostrarla en algún lado)
                // Por ejemplo: console.log("Suma total: " + recalcTotalFibra());
            });
    
            // Cuando se pierda el foco (blur) en un input, si es el último, se ajusta automáticamente para completar 100.
            $(document).on("blur", ".cantidad-fibra", function(){
                let $todos = $("#listaTipoFibraScreen .cantidad-fibra");
                if($todos.length > 1 && $(this).is($todos.last())) {
                    validarSumaTotal();
                }
            });
    
            // Inicializamos el select
            cargarTipoFibraScreen();
        });
    </script>
    <!-- Script exclusivo para defectoScreen -->
    <script>
        $(document).ready(function () {
            let opcionesSeleccionadasDefectoScreen = [];
    
            function cargarDefectoScreen() {
                $("#defectoScreen").select2({
                    placeholder: "Seleccione una opción",
                    ajax: {
                        url: "/defectoScreen",
                        dataType: "json",
                        delay: 250,
                        cache: true,
                        processResults: function (data) {
                            let results = $.map(data, function (item) {
                                return { id: String(item.id), text: item.nombre };
                            });
    
                            // Agregamos la opción "OTRO"
                            results.unshift({ id: "otro", text: "OTRO" });
                            return { results: results };
                        }
                    }
                });
    
                // Manejo de selección de una opción
                $("#defectoScreen").on("select2:select", function (e) {
                    let selectedValue = String(e.params.data.id);
                    let selectedText = e.params.data.text;
    
                    if (selectedValue === "otro") {
                        let nuevoValor = prompt("Ingrese el nuevo valor para CatalogoDefectosScreen:");
                        if (nuevoValor) {
                            nuevoValor = nuevoValor.toUpperCase();
                            $.ajax({
                                url: "/guardarNuevoValorDA",
                                type: "POST",
                                data: {
                                    nombre: nuevoValor,
                                    modelo: "CatalogoDefectosScreen",
                                    estatus: 1,
                                    area: "screen",
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function (response) {
                                    if (response.success) {
                                        let newId = String(response.id);
                                        let newText = nuevoValor;
                                        let newOption = new Option(newText, newId, true, true);
    
                                        $("#defectoScreen")
                                            .append(newOption)
                                            .trigger("change");
    
                                        // Agregar a la lista
                                        agregarOpcionListaDefectoScreen(newId, newText);
                                    } else {
                                        alert("Error al guardar el nuevo valor.");
                                    }
                                },
                                error: function () {
                                    alert("Ocurrió un error. Intente de nuevo.");
                                }
                            });
                        }
                        // Limpia la selección en el select2
                        $("#defectoScreen").val(null).trigger("change");
                    } else {
                        // Si la opción ya existe, advertimos
                        if (opcionesSeleccionadasDefectoScreen.includes(selectedValue)) {
                            alert("La opción ya fue seleccionada.");
                        } else {
                            agregarOpcionListaDefectoScreen(selectedValue, selectedText);
                        }
                        $("#defectoScreen").val(null).trigger("change");
                    }
                });
            }
    
            function agregarOpcionListaDefectoScreen(id, nombre) {
                if (!opcionesSeleccionadasDefectoScreen.includes(id)) {
                    opcionesSeleccionadasDefectoScreen.push(id);
    
                    // Agregamos el bloque con hidden input y el input para cantidad
                    $("#listaDefectoScreen").append(`
                        <div id="opcionDefectoScreen-${id}" class="mb-2 p-2 border rounded">
                            <span>${nombre}</span>
                            
                            <!-- Campo hidden con el nombre del defecto -->
                            <input type="hidden" name="defecto_screen[${id}][nombre]" value="${nombre}"/>
    
                            <!-- Campo para la cantidad -->
                            <input 
                                type="number" 
                                name="defecto_screen[${id}][cantidad]"
                                id="cantidadDefectoScreen-${id}" 
                                class="ms-2" 
                                value="1" 
                                min="1" 
                                style="width: 60px;"
                            />
    
                            <button class="btn btn-danger btn-sm ms-2" onclick="eliminarOpcionDefectoScreen('${id}')">
                                Eliminar
                            </button>
                        </div>
                    `);
                }
            }
    
            // Función global para eliminar
            window.eliminarOpcionDefectoScreen = function (id) {
                id = String(id);
                // Removemos del array
                opcionesSeleccionadasDefectoScreen = opcionesSeleccionadasDefectoScreen.filter(item => item !== id);
                // Removemos el elemento del DOM
                $("#opcionDefectoScreen-" + id).remove();
            };
    
            // Inicializamos
            cargarDefectoScreen();
        });
    </script>

    <!-- Script exclusivo para defectoPlancha -->
    <script>
        $(document).ready(function () {
            let opcionesSeleccionadasDefectoPlancha = [];
    
            function cargarDefectoPlancha() {
                $("#defectoPlancha").select2({
                    placeholder: "Seleccione una opción",
                    ajax: {
                        url: "/defectoPlancha",
                        dataType: "json",
                        delay: 250,
                        cache: true,
                        processResults: function (data) {
                            let results = $.map(data, function (item) {
                                return { id: String(item.id), text: item.nombre };
                            });
    
                            // Agregamos la opción "OTRO"
                            results.unshift({ id: "otro", text: "OTRO" });
                            return { results: results };
                        }
                    }
                });
    
                // Manejo de selección
                $("#defectoPlancha").on("select2:select", function (e) {
                    let selectedValue = String(e.params.data.id);
                    let selectedText = e.params.data.text;
    
                    if (selectedValue === "otro") {
                        let nuevoValor = prompt("Ingrese el nuevo valor para CatalogoDefectosScreen:");
                        if (nuevoValor) {
                            nuevoValor = nuevoValor.toUpperCase();
                            $.ajax({
                                url: "/guardarNuevoValorDA",
                                type: "POST",
                                data: {
                                    nombre: nuevoValor,
                                    modelo: "CatalogoDefectosScreen",
                                    estatus: 1,
                                    area: "plancha",
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function (response) {
                                    if (response.success) {
                                        let newId = String(response.id);
                                        let newText = nuevoValor;
                                        let newOption = new Option(newText, newId, true, true);
    
                                        $("#defectoPlancha")
                                            .append(newOption)
                                            .trigger("change");
    
                                        // Agregar a la lista
                                        agregarOpcionListaDefectoPlancha(newId, newText);
                                    } else {
                                        alert("Error al guardar el nuevo valor.");
                                    }
                                },
                                error: function () {
                                    alert("Ocurrió un error. Intente de nuevo.");
                                }
                            });
                        }
                        // Limpia la selección en el select2
                        $("#defectoPlancha").val(null).trigger("change");
                    } else {
                        // Verificamos si la opción ya está seleccionada
                        if (opcionesSeleccionadasDefectoPlancha.includes(selectedValue)) {
                            alert("La opción ya fue seleccionada.");
                        } else {
                            agregarOpcionListaDefectoPlancha(selectedValue, selectedText);
                        }
                        $("#defectoPlancha").val(null).trigger("change");
                    }
                });
            }
    
            function agregarOpcionListaDefectoPlancha(id, nombre) {
                if (!opcionesSeleccionadasDefectoPlancha.includes(id)) {
                    opcionesSeleccionadasDefectoPlancha.push(id);
    
                    // Agregamos el bloque con hidden input y el input para cantidad
                    $("#listaDefectoPlancha").append(`
                        <div id="opcionDefectoPlancha-${id}" class="mb-2 p-2 border rounded">
                            <span>${nombre}</span>
                            
                            <!-- Campo hidden con el ID del defecto -->
                            <input type="hidden" name="defecto_plancha[${id}][nombre]" value="${nombre}"/>
    
                            <!-- Campo para la cantidad -->
                            <input 
                                type="number" 
                                name="defecto_plancha[${id}][cantidad]" 
                                id="cantidadDefectoPlancha-${id}" 
                                class="ms-2" 
                                value="1" 
                                min="1" 
                                style="width: 60px;"
                            />
    
                            <button class="btn btn-danger btn-sm ms-2" onclick="eliminarOpcionDefectoPlancha('${id}')">
                                Eliminar
                            </button>
                        </div>
                    `);
                }
            }
    
            // Función global para eliminar
            window.eliminarOpcionDefectoPlancha = function (id) {
                id = String(id);
                // Quitamos del array
                opcionesSeleccionadasDefectoPlancha = opcionesSeleccionadasDefectoPlancha.filter(item => item !== id);
                // Quitamos del DOM
                $("#opcionDefectoPlancha-" + id).remove();
            };
    
            // Inicializamos
            cargarDefectoPlancha();
        });
    </script>

    <!-- Script compartido para accionCorrectivaScreen y accionCorrectivaPlancha -->
    <script>
        function cargarSelect2(selector, url, modelo, area, inputName) {
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

                        results.unshift({ id: "otro", text: "OTRO" });

                        return { results: results };
                    },
                    cache: true
                }
            });

            $(selector).on("select2:select", function (e) {
                let selectedValue = e.params.data.id;
                let selectedText = e.params.data.text;

                if (selectedValue === "otro") {
                    let nuevoValor = prompt("Ingrese el nuevo valor para " + modelo + ":");

                    if (nuevoValor) {
                        nuevoValor = nuevoValor.toUpperCase();

                        $.ajax({
                            url: "/guardarNuevoValorDA",
                            type: "POST",
                            data: {
                                nombre: nuevoValor,
                                modelo: modelo,
                                estatus: 1,
                                area: area,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (response) {
                                if (response.success) {
                                    let newOption = new Option(nuevoValor, response.id, true, true);
                                    $(selector).append(newOption).trigger('change');

                                    // También agregamos el input hidden con el nuevo valor
                                    actualizarInputHidden(selector, inputName, nuevoValor);
                                } else {
                                    alert("Error al guardar el nuevo valor.");
                                }
                            },
                            error: function () {
                                alert("Ocurrió un error. Intente de nuevo.");
                            }
                        });
                    }

                    $(selector).val(null).trigger('change');
                } else {
                    // Actualizamos el input hidden con el nombre de la opción seleccionada
                    actualizarInputHidden(selector, inputName, selectedText);
                }
            });
        }

        // Función para actualizar el input hidden
        function actualizarInputHidden(selector, inputName, valor) {
            let hiddenInputId = `hidden-${selector.replace("#", "")}`;
            
            // Eliminamos el input hidden si ya existe
            $("#" + hiddenInputId).remove();

            // Creamos un nuevo input hidden con el nombre seleccionado
            $(selector).after(`
                <input type="hidden" id="${hiddenInputId}" name="${inputName}" value="${valor}">
            `);
        }

        $(document).ready(function () {
            cargarSelect2("#accionCorrectivaScreen", "/accionCorrectivaScreen", "CategoriaAccionCorrectScreen", "screen", "accion_correctiva_screen");
            cargarSelect2("#accionCorrectivaPlancha", "/accionCorrectivaPlancha", "CategoriaAccionCorrectScreen", "plancha", "accion_correctiva_plancha");
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
                        $('#cliente-cell').html(response.cliente + `<input type="hidden" name="cliente_seleccionado" value="${response.cliente}"/>`);
                        $('#estilo-cell').html(response.estilo + `<input type="hidden" name="estilo_seleccionado" value="${response.estilo}"/>`);
                        $('#color-cell').html(response.color + `<input type="hidden" name="color_seleccionado" value="${response.color}"/>`);
                        $('#cantidad-cell').html(response.cantidad + `<input type="hidden" name="cantidad_seleccionado" value="${response.cantidad}"/>`);
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
            const checkScreen    = document.getElementById("check-screen");
            const checkPlancha   = document.getElementById("check-plancha");
            const tableScreen    = document.getElementById("table-screen");
            const tablePlancha   = document.getElementById("table-plancha");
            const submitButton   = document.querySelector(".btn-verde-xd");
        
            // Función que habilita o deshabilita (y quita la validación "required") todos los campos dentro de una tabla
            function updateTableFields(table, enable) {
                // Selecciona todos los elementos de formulario (inputs, selects, textareas) dentro de la tabla
                const elements = table.querySelectorAll("input, select, textarea");
                elements.forEach(function(el) {
                    el.disabled = !enable; // Si no se quiere habilitar, se deshabilita
                    if (!enable) {
                        // Quita el atributo "required" para evitar bloqueos en la validación
                        el.required = false;
                    }
                });
            }
        
            // Función para actualizar el estado del botón de enviar
            function updateSubmitButton() {
                // Si alguno de los checkboxes está marcado, se habilita el botón, de lo contrario se deshabilita
                if (checkScreen.checked || checkPlancha.checked) {
                    submitButton.disabled = false;
                } else {
                    submitButton.disabled = true;
                }
            }
        
            // Inicialmente: ocultamos ambas tablas, deshabilitamos sus campos y deshabilitamos el botón de envío
            tableScreen.style.display = "none";
            tablePlancha.style.display = "none";
            updateTableFields(tableScreen, false);
            updateTableFields(tablePlancha, false);
            submitButton.disabled = true;
        
            // Evento para el checkbox de Screen
            checkScreen.addEventListener("change", function () {
                if (checkScreen.checked) {
                    // Si se marca, mostramos la tabla y habilitamos sus campos
                    tableScreen.style.display = "block";
                    updateTableFields(tableScreen, true);
                } else {
                    // Si se desmarca, ocultamos la tabla y deshabilitamos sus campos
                    tableScreen.style.display = "none";
                    updateTableFields(tableScreen, false);
                }
                updateSubmitButton();
            });
        
            // Evento para el checkbox de Plancha
            checkPlancha.addEventListener("change", function () {
                if (checkPlancha.checked) {
                    tablePlancha.style.display = "block";
                    updateTableFields(tablePlancha, true);
                } else {
                    tablePlancha.style.display = "none";
                    updateTableFields(tablePlancha, false);
                }
                updateSubmitButton();
            });
        });
    </script>
@endsection