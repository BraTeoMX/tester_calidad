@extends('layouts.app', ['pageSlug' => 'Segundas', 'titlePage' => __('Segundas')])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <h1 class="card-title" style="font-size: 280%;">{{ __('Segundas') }}</h1>
            <!-- Fila de Tarjetas -->
            <div class="row">
                <!-- Tarjeta Fecha -->
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card card-stats">
                        <div class="card-header card-header-danger card-header-icon">
                            <div class="card-icon">
                                <span class="material-symbols-outlined">calendar_month</span>
                            </div>
                            <h3 class="card-title">Fecha</h3>
                            <div class="flatpickr">
                                <b>Selección Fecha..</b>
                                <br>
                                <input type="text" class="form-control input-custom-style"
                                    placeholder="Selección Fecha.." data-input id="Fecha">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tarjeta Clientes -->
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card card-stats">
                        <div class="card-header card-header-success card-header-icon">
                            <div class="card-icon">
                                <span class="material-symbols-outlined">location_away</span>
                            </div>
                            <h3 class="card-title">Clientes</h3>
                            <b>Selección Cliente..</b>
                            <button id="dropdownSearchButtonCliente" data-dropdown-toggle="dropdownSearchCliente"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-purple-500 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-purple-500 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                type="button">Select clientes<svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg></button>
                            <!-- Dropdown menu -->
                            <div id="dropdownSearchCliente"
                                class="z-10 hidden bg-white  rounded-lg shadow w-60 dark:bg-gray-700">
                                <div class="p-3">
                                    <label for="input-group-search" class="sr-only">Search</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                            </svg>
                                        </div>
                                        <input type="text" id="input-group-search"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Search cliente">
                                    </div>
                                </div>
                                <div id="spinnerClient" class="spinnerClient"></div>
                                <ul class="h-48 px-3 pb-3 overflow-y-auto text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdownSearchButtonCliente">

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta Modulo -->
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card card-stats">
                        <div class="card-header card-header-warning card-header-icon">
                            <div class="card-icon">
                                <span class="material-symbols-outlined">view_module</span>
                            </div>
                            <h3 class="card-title">Modulo</h3>
                            <b>Selección Modulo..</b>
                            <button id="dropdownSearchButtonModulo" data-dropdown-toggle="dropdownSearchModulo"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-purple-500 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-purple-500 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                type="button">Select Modulos<svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg></button>
                            <!-- Dropdown menu -->
                            <div id="dropdownSearchModulo"
                                class="z-10 hidden bg-white  rounded-lg shadow w-60 dark:bg-gray-700">
                                <div class="p-3">
                                    <label for="input-group-search" class="sr-only">Search</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                            </svg>
                                        </div>
                                        <input type="text" id="input-group-search"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Search modulo">
                                    </div>
                                </div>
                                <div id="spinnerModul" class="spinner"></div>
                                <ul class="h-48 px-3 pb-3 overflow-y-auto text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdownSearchButtonModulo">

                                </ul>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Tarjeta Planta -->
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card card-stats">
                        <div class="card-header card-header-danger card-header-icon">
                            <div class="card-icon">
                                <span class="material-symbols-outlined">source_environment</span>
                            </div>
                            <h3 class="card-title">Planta</h3>
                            <b>Selección Planta..</b>
                            <br>
                            <button id="dropdownSearchButtonPlanta" data-dropdown-toggle="dropdownSearchPlanta"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-purple-500 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-purple-500 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                type="button">Select planta<svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg></button>
                            <!-- Dropdown menu -->
                            <div id="dropdownSearchPlanta"
                                class="z-10 hidden bg-white  rounded-lg shadow w-60 dark:bg-gray-700">
                                <div class="p-3">
                                    <label for="input-group-search" class="sr-only">Search</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                            </svg>
                                        </div>
                                        <input type="text" id="input-group-search"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Search planta">
                                    </div>
                                </div>
                                <div id="spinner" class="spinner"></div>
                                <ul class="h-48 px-3 pb-3 overflow-y-auto text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdownSearchButtonPlanta">

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="col-lg-auto col-md-auto col-sm-auto">
                <div class="card card-stats">
                    <div class="card">
                        <div class="card-header">
                            Graphic
                        </div>
                        <div class="card-body">
                            <blockquote class="blockquote mb-auto col-lg-auto col-md-auto col-sm-auto">
                                <div class="card-body">
                                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                        <table
                                            class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
                                                        Plantas
                                                    </th>
                                                    <th scope="col" class="px-6 py-3">
                                                        Modulos
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
                                                        Clientes
                                                    </th>
                                                    <th scope="col" class="px-6 py-3">
                                                        Divisiones Clinetes
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
                                                        Tipo Segundas
                                                    </th>
                                                    <th scope="col" class="px-6 py-3">
                                                        Desc Segundas
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
                                                        Ticket
                                                    </th>
                                                    <th scope="col" class="px-6 py-3">
                                                        Cantidad
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
                                                        Fechas
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="border-b border-gray-200 dark:border-gray-700">

                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="graficaPiramide" style="width: 100%; height: 600px;"></div>
                                </div>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <style>
        .input-custom-style {
            color: white !important;
            /* Forza el color del texto a blanco */
            font-weight: bold !important;
            /* Forza un borde visible */
            padding: 10px !important;
            /* Agrega padding para mayor visibilidad */
        }

        .input-custom-style::placeholder {
            color: rgba(255, 255, 255, 0.7) !important;
            /* Forza el placeholder a blanco con opacidad */
            font-weight: bold !important;
        }

        #dropdownSearchCliente {
            background-color: #374151 !important;
            /* Forza el fondo a negro */
        }

        #dropdownSearchModulo {
            background-color: #374151 !important;
            /* Forza el fondo a negro */
        }

        #dropdownSearchPlanta {
            background-color: #374151 !important;
            /* Forza el fondo a negro */
        }
    </style>
    <style>
        /* Estilo para el spinner */
        .spinner {
            border: 4px solid #f3f3f3;
            border-radius: 50%;
            border-top: 4px solid #3498db;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;

            /* Centrar el spinner horizontal y verticalmente */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Ocultar el spinner inicialmente */
        #spinner {
            display: none;
        }
    </style>
    <style>
        /* Estilo para el spinner */
        .spinnerModul {
            border: 4px solid #f3f3f3;
            border-radius: 50%;
            border-top: 4px solid #3498db;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;

            /* Centrar el spinner horizontal y verticalmente */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Ocultar el spinner inicialmente */
        #spinnerModul {
            display: none;
        }
    </style>
    <style>
        /* Estilo para el spinner */
        .spinnerClient {
            border: 4px solid #f3f3f3;
            border-radius: 50%;
            border-top: 4px solid #3498db;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;

            /* Centrar el spinner horizontal y verticalmente */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Ocultar el spinner inicialmente */
        #spinnerClient {
            display: none;
        }
    </style>
    <!-- DatePicker -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
    <script>
        $(document).ready(function() {
            let selectedDates = []; // Array para almacenar las fechas seleccionadas

            flatpickr(".flatpickr", {
                mode: "range",
                dateFormat: "d-m-Y",
                wrap: true,
                onChange: function(selectedDatesArray) {
                    // Convertir las fechas seleccionadas a un formato legible y almacenar en el array
                    selectedDates = selectedDatesArray.map(date => {
                        // Convierte cada fecha a formato "dd-mm-yyyy"
                        return flatpickr.formatDate(date, "d-m-Y");
                    });

                    console.log('Fechas seleccionadas:', selectedDates); // Mostrar en consola
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            let selectedPlantas = []; // Array para almacenar plantas seleccionadas
            $("#spinner").show();

            $.ajax({
                url: '/ObtenerPlantas',
                method: "GET",
                success: function(response) {
                    $('#dropdownSearchPlanta ul').empty();

                    response.ObtenerPlantas.forEach(function(plant) {
                        $('#dropdownSearchPlanta ul').append(
                            `<li class="py-1 px-2 hover:bg-gray-600 cursor-pointer">
                            <input id="checkbox-item-${plant}" type="checkbox" value="${plant}" class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            ${plant}
                        </li>`
                        );
                    });

                    // Agregar el evento de cambio para cada checkbox de planta
                    $('.planta-checkbox').on('change', function() {
                        const plant = $(this).val();
                        if ($(this).is(':checked')) {
                            selectedPlantas.push(plant);
                        } else {
                            selectedPlantas = selectedPlantas.filter(p => p !== plant);
                        }
                        console.log('Plantas seleccionadas:', selectedPlantas);
                    });

                    $("#spinner").hide();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    $("#spinner").hide();
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            let selectedModulos = []; // Array para almacenar módulos seleccionados
            $("#spinnerModul").show();

            function cargarModulos() {
                $.ajax({
                    url: '/ObtenerModulos',
                    method: 'GET',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#dropdownSearchModulo ul').empty();

                            response.ObtenerModulos.forEach(function(modulo) {
                                $('#dropdownSearchModulo ul').append(`
                                <li class="py-1 px-2 hover:bg-gray-600 cursor-pointer">
                                    <input id="checkbox-modulo-${modulo}" type="checkbox" value="${modulo}" class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    ${modulo}
                                </li>
                            `);
                            });

                            // Evento para cambios en cada checkbox de módulo
                            $('.modulo-checkbox').on('change', function() {
                                const modulo = $(this).val();
                                if ($(this).is(':checked')) {
                                    selectedModulos.push(modulo);
                                } else {
                                    selectedModulos = selectedModulos.filter(m => m !== modulo);
                                }
                                console.log('Módulos seleccionados:', selectedModulos);
                            });
                        } else {
                            console.error('No se recibieron datos en la respuesta');
                        }
                        $("#spinnerModul").hide();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al obtener módulos:', error);
                        $("#spinnerModul").hide();
                    }
                });
            }

            // Llamar a cargarModulos al iniciar
            cargarModulos();
        });
    </script>
    <script>
        $(document).ready(function() {
            let selectedClientes = []; // Array para almacenar clientes seleccionados
            $("#spinnerClient").show();

            function cargarClientes() {
                $.ajax({
                    url: '/ObtenerClientes',
                    method: 'GET',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#dropdownSearchCliente ul').empty();

                            response.ObtenerClientes.forEach(function(cliente) {
                                $('#dropdownSearchCliente ul').append(`
                                <li class="py-1 px-2 hover:bg-gray-600 cursor-pointer">
                                    <input id="checkbox-cliente-${cliente}" type="checkbox" value="${cliente}" class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    ${cliente}
                                </li>
                            `);
                            });

                            // Evento para cambios en cada checkbox de cliente
                            $('.cliente-checkbox').on('change', function() {
                                const cliente = $(this).val();
                                if ($(this).is(':checked')) {
                                    selectedClientes.push(cliente);
                                } else {
                                    selectedClientes = selectedClientes.filter(c => c !==
                                        cliente);
                                }
                                console.log('Clientes seleccionados:', selectedClientes);
                            });
                        } else {
                            console.error('No se recibieron datos en la respuesta');
                        }
                        $("#spinnerClient").hide();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al obtener clientes:', error);
                        $("#spinnerClient").hide();
                    }
                });
            }

            // Llamar a cargarClientes al iniciar
            cargarClientes();
        });
    </script>
     <script>
        $(document).ready(function () {
            // Llama a la función cuando la página esté lista
            ObtenerSegundas();
        });

        function ObtenerSegundas() {
            $.ajax({
                url: '/ObtenerSegundas',
                method: 'GET',
                success: function (response) {
                    if (response.status === 'success') {
                        let datosTerceras = response.data;

                        // Limpiar la tabla antes de renderizar
                        $('table tbody').empty();

                        // Renderizar los datos en la tabla
                        renderizarTabla(datosTerceras);
                    } else {
                        alert('No se pudieron obtener los datos correctamente.');
                    }
                },
                error: function () {
                    alert('Hubo un error al obtener los datos.');
                }
            });
        }

        function renderizarTabla(datos) {
            let tbody = $('table tbody'); // Selecciona el cuerpo de la tabla

            datos.forEach(function (dato) {
                // Extraer solo la parte numérica de OPRMODULEID_AT
                let moduloNumero = parseInt(dato.OPRMODULEID_AT.replace(/\D/g, ''), 10); // Eliminar letras y obtener un número

                // Determinar la planta en función del valor numérico de OPRMODULEID_AT
                let planta;
                if (moduloNumero >= 100 && moduloNumero < 200) {
                    planta = "Planta Ixtlahuaca";
                } else if (moduloNumero >= 200 && moduloNumero < 300) {
                    planta = "Planta San Bartolo";
                } else {
                    planta = "Desconocida"; // Asignar "Desconocida" si no cumple ninguna de las condiciones
                }

                // Crear una nueva fila con los datos de la planta
                let fila = `
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800">${planta}</td>
                        <td class="px-6 py-4">${dato.OPRMODULEID_AT}</td>
                        <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800">${dato.CUSTOMERNAME}</td>
                        <td class="px-6 py-4">${dato.DIVISIONNAME}</td>
                        <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800">${dato.TipoSegunda}</td>
                         <td class="px-6 py-4">${dato.DescripcionCalidad}</td>
                        <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800">${dato.PRODTICKETID}</td>
                         <td class="px-6 py-4">${dato.QTY}</td>
                        <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800">${dato.TRANSDATE}</td>
                    </tr>
                `;
                tbody.append(fila); // Agrega la fila al cuerpo de la tabla
            });
        }
    </script>

    <script>

    </script>
@endsection
