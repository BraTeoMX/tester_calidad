
<?php $__env->startSection('content'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <div class="content">
        <div class="container-fluid">
            <h1 class="card-title" style="font-size: 280%;"><?php echo e(__('Segundas')); ?></h1>
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
                                <input type="text" class="form-control input-custom-style"
                                    placeholder="Selección Fecha.." data-input id="Fecha">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tarjeta Divisiones y Clientes -->
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card card-stats">
                        <div class="card-header card-header-success card-header-icon">
                            <div class="card-icon">
                                <span class="material-symbols-outlined">location_away</span>
                            </div>
                            <h3 class="card-title">Clientes por División</h3>
                            <button id="dropdownMultiLevelButton" data-dropdown-toggle="dropdownMultiLevelClienteDivcion"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-purple-500 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-purple-500 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                type="button">Seleccionar Cliente
                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>
                            <!-- Dropdown Multi-Level -->
                            <div id="dropdownMultiLevelClienteDivcion"
                                class="z-10 hidden bg-gray-600 rounded-lg shadow w-60 dark:bg-gray-700">
                                <div class="p-3">
                                    <label for="input-group-search" class="sr-only">Buscar</label>
                                    <div class="relative">
                                        <input type="text" id="input-group-search" onkeyup="filtrarDivisiones()"
                                            class="bg-gray-600 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Buscar división o cliente">
                                    </div>
                                </div>
                                <div id="spinnerClienteDivicion"></div>
                                <ul class="h-48 px-3 pb-3 overflow-y-auto text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdownMultiLevelButton">
                                    <!-- Aquí se cargarán las divisiones y sus clientes -->
                                </ul>
                                <div id="spinnerClienteDivicion"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tarjeta Tipos Defectos -->
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card card-stats">
                        <div class="card-header card-header-success card-header-icon">
                            <div class="card-icon">
                                <span class="material-symbols-outlined">pest_control</span>
                            </div>
                            <h3 class="card-title">Tipos Defectos</h3>
                            <!-- Botón Dropdown -->
                            <button id="dropdownToggleDefectos" data-dropdown-toggle="dropdownMenuDefectos"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-purple-500 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-purple-500 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                type="button">
                                Seleccionar Defecto
                                <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>
                            <!-- Dropdown menu -->
                            <div id="dropdownMenuDefectos"
                                class="z-10 hidden bg-gray-600 rounded-lg shadow w-60 dark:bg-gray-700">
                                <ul class="p-3 space-y-3 text-sm text-gray-700 dark:text-gray-200">
                                    <li>
                                        <div class="flex items-center">
                                            <input id="checkbox-defectos" type="checkbox" value="Segunda por Material"
                                                class="defectos-checkbox w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            Material
                                        </div>
                                    </li>
                                    <li>
                                        <div class="flex items-center">
                                            <input id="checkbox-defectos" type="checkbox" value="Segunda por Costura"
                                                class="defectos-checkbox w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            Costura
                                        </div>
                                    </li>
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
                            <button id="dropdownSearchButtonModulo" data-dropdown-toggle="dropdownSearchModulo"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-purple-500 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-purple-500 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                type="button">Seleccionar Modulos<svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true"
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
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
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
                            <button id="dropdownSearchButtonPlanta" data-dropdown-toggle="dropdownSearchPlanta"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-purple-500 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-purple-500 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                type="button">Seleccionar planta<svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true"
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
                                    <li class="py-1 px-2 hover:bg-gray-600 cursor-pointer">
                                        <input id="checkbox-planta" type="checkbox" value="Planta Ixtlahuaca"
                                            class="planta-checkbox w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">Planta
                                        Ixtlahuaca
                                    </li>
                                    <li class="py-1 px-2 hover:bg-gray-600 cursor-pointer">
                                        <input id="checkbox-planta" type="checkbox" value="Planta San Bartolo"
                                            class="planta-checkbox w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">Planta
                                        San Bartolo
                                    </li>
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
                            Grafics
                        </div>
                        <div class="card-body">
                            <blockquote class="blockquote mb-auto col-lg-auto col-md-auto col-sm-auto">
                                <button id="regresarBtn"
                                    style="display:none; background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                                    Regresar
                                </button>
                                <div id="SegundasGrafics"></div>
                            </blockquote>
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
                            Data info
                        </div>
                        <div class="card-body">
                            <blockquote class="blockquote mb-auto col-lg-auto col-md-auto col-sm-auto">
                                <div class="card-body">
                                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                        <table
                                            class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" id="TableSegundas" name="TableSegundas">
                                            <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3">
                                                        #
                                                    </th>
                                                    <th scope="col" class="px-6 py-3">
                                                        Plantas
                                                    </th>
                                                    <th scope="col" class="px-6 py-3">
                                                        Modulos
                                                    </th>
                                                    <th scope="col" class="px-6 py-3">
                                                        Clientes
                                                    </th>
                                                    <th scope="col" class="px-6 py-3">
                                                        Divisiones Clinetes
                                                    </th>
                                                    <th scope="col" class="px-6 py-3">
                                                        Tipo Segundas
                                                    </th>
                                                    <th scope="col" class="px-6 py-3">
                                                        Desc Segundas
                                                    </th>
                                                    <th scope="col" class="px-6 py-3">
                                                        Ticket
                                                    </th>
                                                    <th scope="col" class="px-6 py-3">
                                                        Cantidad
                                                    </th>
                                                    <th scope="col" class="px-6 py-3">
                                                        Fechas
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                        <nav class="flex items-center flex-column flex-wrap md:flex-row justify-between pt-4"
                                            aria-label="Table navigation">
                                            <span
                                                class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-4 md:mb-0 block w-full md:inline md:w-auto">Datos
                                                <span class="font-semibold text-gray-500 dark:text-gray-400"> </span> de
                                                <span class="font-semibold text-gray-500 dark:text-gray-400"> </span>
                                            </span>
                                            <ul class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8">

                                            </ul>
                                        </nav>
                                    </div>
                                    <div id="spinnerTable" class="spinner"></div>
                                </div>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div data-dial-init class="fixed end-6 bottom-6 group">
        <div id="speed-dial-menu-bottom-right" class="flex flex-col items-center hidden mb-4 space-y-2">
            <button type="button" data-tooltip-target="tooltip-download" data-tooltip-placement="left" class="flex justify-center items-center w-[52px] h-[52px] text-gray-500 hover:text-gray-900 bg-white rounded-full border border-gray-200 dark:border-gray-600 shadow-sm dark:hover:text-white dark:text-gray-400 hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 focus:outline-none dark:focus:ring-gray-400">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M14.707 7.793a1 1 0 0 0-1.414 0L11 10.086V1.5a1 1 0 0 0-2 0v8.586L6.707 7.793a1 1 0 1 0-1.414 1.414l4 4a1 1 0 0 0 1.416 0l4-4a1 1 0 0 0-.002-1.414Z"/>
                    <path d="M18 12h-2.55l-2.975 2.975a3.5 3.5 0 0 1-4.95 0L4.55 12H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2Zm-3 5a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Download</span>
            </button>
            <div id="tooltip-download" role="tooltip" class="absolute z-10 invisible inline-block w-auto px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Download
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
        </div>
        <button type="button" data-dial-toggle="speed-dial-menu-bottom-right" aria-controls="speed-dial-menu-bottom-right" aria-expanded="false" class="flex items-center justify-center text-white bg-blue-700 rounded-full w-14 h-14 hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800">
            <svg class="w-5 h-5 transition-transform group-hover:rotate-45" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
            </svg>
            <span class="sr-only">Open actions menu</span>
        </button>
    </div>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
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
        /* Estilo para el spinnerTable */
        .spinnerTable {
            border: 4px solid #f3f3f3;
            border-radius: 50%;
            border-top: 4px solid #3498db;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;

            /* Centrar el spinnerTable horizontal y verticalmente */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes  spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Ocultar el spinner inicialmente */
        #spinnerTable {
            display: none;
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

        @keyframes  spin {
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

        @keyframes  spin {
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
        .spinnerClienteDivicion {
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

        @keyframes  spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Ocultar el spinner inicialmente */
        #spinnerClienteDivicion {
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
                dateFormat: "Y-m-d",
                wrap: true,
                onChange: function(selectedDatesArray) {
                    // Convertir las fechas seleccionadas a un formato legible y almacenar en el array
                    selectedDates = selectedDatesArray.map(date => {
                        // Convierte cada fecha a formato "dd-mm-yyyy"
                        return flatpickr.formatDate(date, "Y-m-d");
                    });

                    console.log('Fechas seleccionadas:', selectedDates); // Mostrar en consola
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            let selectedDivisiones = [];
            let selectedClientes = [];
            $("#spinnerClienteDivicion").show();

            function cargarClientesYDivisiones() {
                if (obtenerSegundasCargado) {
                    $.ajax({
                        url: '/ObtenerClientes',
                        method: 'GET',
                        success: function(response) {
                            if (response.status === 'success') {
                                $('#dropdownMultiLevelClienteDivcion ul').empty();

                                const clientesDivisiones = response.ObtenerClientes;
                                Object.keys(clientesDivisiones).forEach(cliente => {
                                    // Cliente como encabezado con checkbox
                                    $('#dropdownMultiLevelClienteDivcion ul').append(`
                                    <li class="py-2 px-2 bg-gray-700 text-white font-bold">
                                        <input id="checkbox-cliente" type="checkbox" value="${cliente}" data-cliente="${cliente}" class="cliente-checkbox" >
                                        ${cliente}
                                    </li>
                                `);

                                    // Divisiones como subelementos
                                    clientesDivisiones[cliente].forEach(division => {
                                        $('#dropdownMultiLevelClienteDivcion ul')
                                            .append(`
                                        <li class="py-1 px-4 hover:bg-gray-600 cursor-pointer">
                                            <input id="checkbox-division" type="checkbox" value="${division}" data-cliente="${cliente}" class="division-checkbox w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            ${division}
                                        </li>
                                    `);
                                    });
                                });

                                // Evento para cambios en los checkboxes de clientes
                                $('.cliente-checkbox').on('change', function() {
                                    const cliente = $(this).val();

                                    if ($(this).is(':checked')) {
                                        selectedClientes.push({
                                            cliente
                                        });
                                    } else {
                                        selectedClientes = selectedClientes.filter(
                                            c => c.cliente !== cliente
                                        );
                                    }

                                    console.log('Clientes seleccionadas:', selectedClientes);
                                });

                                // Evento para cambios en los checkboxes de divisiones
                                $('.division-checkbox').on('change', function() {
                                    const division = $(this).val();

                                    if ($(this).is(':checked')) {
                                        selectedDivisiones.push({
                                            division
                                        });
                                    } else {
                                        selectedDivisiones = selectedDivisiones.filter(
                                            d => d.division !== division
                                        );
                                    }

                                    console.log('Divisiones seleccionadas:',
                                        selectedDivisiones);
                                });

                                $("#spinnerClienteDivicion").hide();
                            } else {
                                console.error('No se recibieron datos en la respuesta');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error al obtener clientes y divisiones:', error);
                            $("#spinnerClienteDivicion").hide();
                        }
                    });
                } else {
                    // Si ObtenerSegundas no ha terminado, esperar 100ms y volver a intentar
                    setTimeout(cargarClientesYDivisiones, 100);
                }
            }

            window.filtrarDivisiones = function() {
                const valorFiltro = $('#input-group-search').val().toLowerCase();
                $('#dropdownMultiLevelClienteDivcion ul li').each(function() {
                    const textoElemento = $(this).text().toLowerCase();
                    $(this).toggle(textoElemento.includes(valorFiltro));
                });
            };

            // Llamar a la función para cargar los datos
            cargarClientesYDivisiones();
        });
    </script>
    <script>
        $(document).ready(function() {
            let selectedModulos = []; // Array para almacenar módulos seleccionados
            $("#spinnerModul").show();

            function cargarModulos() {
                if (obtenerSegundasCargado) {
                    $.ajax({
                        url: '/ObtenerModulos',
                        method: 'GET',
                        success: function(response) {
                            if (response.status === 'success') {
                                $('#dropdownSearchModulo ul').empty();

                                response.ObtenerModulos.forEach(function(modulo) {
                                    $('#dropdownSearchModulo ul').append(`
                                <li class="py-1 px-2 hover:bg-gray-600 cursor-pointer">
                                    <input id="checkbox-modulo"
                                           type="checkbox"
                                           value="${modulo}"
                                           class="modulo-checkbox w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    ${modulo}
                                </li>
                            `);
                                });

                                $("#spinnerModul").hide();
                            } else {
                                console.error('No se recibieron datos en la respuesta');
                                $("#spinnerModul").hide();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error al obtener módulos:', error);
                            $("#spinnerModul").hide();
                        }
                    });
                } else {
                    // Si obtenerSegundasCargado no ha terminado, esperar 100ms y volver a intentar
                    setTimeout(cargarModulos, 100);
                }
            }
            // Evento de cambio para los checkboxes de módulo
            $(document).on('change', '.modulo-checkbox', function() {
                const modulo = $(this).val();
                if ($(this).is(':checked')) {
                    selectedModulos.push(modulo);
                } else {
                    selectedModulos = selectedModulos.filter(m => m !== modulo);
                }
                console.log('Módulos seleccionados:', selectedModulos);
            });

            // Llamar a cargarModulos al iniciar
            cargarModulos();
        });
    </script>
    <script>
        let obtenerSegundasCargado = false;
        let currentPage = 1; // Página actual de los datos
        const itemsPerPage = 10; // Elementos por página
        let paginatedData = []; // Array que almacenará los datos paginados
        let allData = []; // Array para almacenar todos los datos recibidos
        let selectedDates = []; // Array para almacenar las fechas seleccionadas
        let selectedDivisiones = []; // Array para almacenar divisiones seleccionadas
        let selectedModulos = []; // Array para almacenar módulos seleccionados
        let selectedPlantas = []; // Array para almacenar plantas seleccionadas
        let selectedClientes = []; // Array para almacenar clientes seleccionados
        let selectedDefectos = []; // Array para almacenar defectos seleccionados

        $(document).ready(function() {
            ObtenerSegundas();

            // Inicializar flatpickr para el elemento con id #Fecha
            flatpickr(".flatpickr", {
                mode: "range",
                dateFormat: "d-m-Y",
                wrap: true,
                onChange: function(selectedDatesArray) {
                    // Convertir las fechas seleccionadas a un formato legible y almacenar en el array
                    selectedDates = selectedDatesArray.map(date => {
                        // Convierte cada fecha a formato "dd-mm-yyyy"
                        return flatpickr.formatDate(date, "Y-m-d");
                    });
                    console.log('Fechas seleccionadas:', selectedDates); // Mostrar en consola
                    filtrarDatos();
                }
            });

            // Evento de cambio para los checkboxes de plantas
            $(document).on('change', '#checkbox-planta', function() {
                const value = $(this).val();
                if ($(this).is(':checked')) {
                    selectedPlantas.push(value);
                } else {
                    selectedPlantas = selectedPlantas.filter(item => item !== value);
                }
                filtrarDatos();
            });

            // Evento de cambio para los checkboxes de módulos
            $(document).on('change', '#checkbox-modulo', function() {
                const value = $(this).val();
                if ($(this).is(':checked')) {
                    selectedModulos.push(value);
                } else {
                    selectedModulos = selectedModulos.filter(item => item !== value);
                }
                filtrarDatos();
            });

            // Evento de cambio para los checkboxes de divisiones
            $(document).on('change', '#checkbox-division', function() {
                const value = $(this).val();
                if ($(this).is(':checked')) {
                    selectedDivisiones.push(value);
                } else {
                    selectedDivisiones = selectedDivisiones.filter(item => item !== value);
                }
                filtrarDatos();
            });

            // Evento de cambio para los checkboxes de clientes
            $(document).on('change', '#checkbox-cliente', function() {
                const value = $(this).val();
                if ($(this).is(':checked')) {
                    selectedClientes.push(value);
                } else {
                    selectedClientes = selectedClientes.filter(item => item !== value);
                }
                filtrarDatos();
            });

            // Evento de cambio para los checkboxes de defectos
            $(document).on('change', '#checkbox-defectos', function() {
                const value = $(this).val();
                if ($(this).is(':checked')) {
                    selectedDefectos.push(value);
                } else {
                    selectedDefectos = selectedDefectos.filter(item => item !== value);
                }
                filtrarDatos();
            });


        });

        function ObtenerSegundas() {
            $("#spinnerTable").show();
            $.ajax({
                url: '/ObtenerSegundas',
                method: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        obtenerSegundasCargado = true;
                        $("#spinnerTable").hide();
                        allData = response.data;

                        // Paginación inicial al recibir los datos
                        paginarDatos(allData);
                        renderizarTabla(paginatedData[currentPage - 1]); // Mostrar la primera página
                        mostrarBotonesPaginacion();
                    } else {
                        alert('No se pudieron obtener los datos correctamente.');
                        $("#spinnerTable").hide();
                    }
                },
                error: function() {
                    alert('Hubo un error al obtener los datos.');
                    $("#spinnerTable").hide();
                }
            });
        }

        // Función para dividir los datos en arrays de 10 elementos cada uno
        function paginarDatos(datos) {
            paginatedData = [];
            for (let i = 0; i < datos.length; i += itemsPerPage) {
                paginatedData.push(datos.slice(i, i + itemsPerPage));
            }
        }

        function renderizarTabla(datosPagina) {
            let tbody = $('table tbody');
            tbody.empty();

            // Asegurarse de que datosPagina es un array válido antes de iterar
            if (!Array.isArray(datosPagina) || datosPagina.length === 0) {
                tbody.append(`<tr><td colspan="10" class="text-center">No se encontraron datos</td></tr>`);
                return;
            }

            // Índice inicial para numerar los registros en la página actual
            const startIndex = (currentPage - 1) * itemsPerPage;

            datosPagina.forEach(function(dato, index) {
                // Formatear la cantidad
                var cantidadFormateada = dato.QTY;
                if (typeof cantidadFormateada === 'string') {
                    var puntoIndex = cantidadFormateada.indexOf('.');
                    if (puntoIndex !== -1) {
                        var parteDecimal = cantidadFormateada.substring(puntoIndex + 1);
                        if (parteDecimal.length > 1) {
                            parteDecimal = parteDecimal.substring(0, 1);
                        }
                        cantidadFormateada = cantidadFormateada.substring(0, puntoIndex + 1) + parteDecimal;
                    }
                }

                // Fila con contador de registro en el primer <td>
                let fila = `
            <tr class="border-b border-gray-200 dark:border-gray-700">
                <td class="px-6 py-4">${startIndex + index + 1}</td> <!-- Contador de registro -->
                <td class="px-6 py-4">${dato.PRODPOOLID}</td>
                <td class="px-6 py-4">${dato.OPRMODULEID_AT}</td>
                <td class="px-6 py-4 ">${dato.CUSTOMERNAME}</td>
                <td class="px-6 py-4">${dato.DIVISIONNAME}</td>
                <td class="px-6 py-4 ">${dato.TipoSegunda}</td>
                <td class="px-6 py-4">${dato.DescripcionCalidad}</td>
                <td class="px-6 py-4">${dato.PRODTICKETID}</td>
                <td class="px-6 py-4">${cantidadFormateada}</td>
                <td class="px-6 py-4">${dato.TRANSDATE}</td>
            </tr>
        `;
                tbody.append(fila);
            });

            // Actualizar los índices de navegación
            const endIndex = Math.min(startIndex + itemsPerPage, datosPagina.length * itemsPerPage);
            actualizarIndicesNav(startIndex + 1, endIndex, paginatedData.flat().length);
        }

        function mostrarBotonesPaginacion() {
            const totalPages = paginatedData.length;
            let paginationContainer = $('ul.inline-flex');
            paginationContainer.empty();
            // Agregar botón de 'Previous'
            paginationContainer.append(`
        <li>
            <a href="#" onclick="cambiarPagina(currentPage - 1, ${totalPages})" class="font-semibold flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-gray-800 border border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
        </li>
    `);

            // Botón para ir a la primera página "<<"
            paginationContainer.append(`
        <li>
            <a href="#" onclick="cambiarPagina(1, ${totalPages})" class="font-semibold flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-gray-800 border border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"><<</a>
        </li>
    `);

            // Botón para retroceder "<"
            paginationContainer.append(`
        <li>
            <a href="#" onclick="cambiarPagina(currentPage - 1, ${totalPages})" class="font-semibold flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-gray-800 border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"><</a>
        </li>
    `);
            // Botón para avanzar ">"
            paginationContainer.append(`
        <li>
            <a href="#" onclick="cambiarPagina(currentPage + 1, ${totalPages})" class="font-semibold flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-gray-800 border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">></a>
        </li>
    `);

            // Botón para ir a la última página ">>"
            paginationContainer.append(`
        <li>
            <a href="#" onclick="cambiarPagina(${totalPages}, ${totalPages})" class="font-semibold flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-gray-800 border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">>></a>
        </li>
    `);

            // Agregar botón de 'Next'
            paginationContainer.append(`
        <li>
            <a href="#" onclick="cambiarPagina(currentPage + 1, ${totalPages})" class="font-semibold flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-gray-800 border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
        </li>
    `);
        }

        function cambiarPagina(page) {
            const totalPages = paginatedData.length;
            if (page >= 1 && page <= totalPages) {
                currentPage = page;
                renderizarTabla(paginatedData[currentPage - 1]); // Renderizar la página actual
            }
        }

        function actualizarIndicesNav(startIndex, endIndex, totalItems) {
            $('span.text-sm.font-normal').html(`
        Datos <span class="font-semibold text-gray-900 dark:text-white">${startIndex}</span> de <span class="font-semibold text-gray-900 dark:text-white">${endIndex}</span> de un total de ${totalItems}
    `);
        }

        function filtrarDatos() {
            let datosFiltrados = allData.filter(dato => {
                const fechaValida = !selectedDates.length || (new Date(dato.TRANSDATE).getTime() >= new Date(
                    selectedDates[0]).getTime() && new Date(dato.TRANSDATE).getTime() <= new Date(
                    selectedDates[1]).getTime());
                const clienteValido = !selectedClientes.length || selectedClientes.includes(dato.CUSTOMERNAME);
                const divisionValida = !selectedDivisiones.length || selectedDivisiones.includes(dato.DIVISIONNAME);
                const defectoValida = !selectedDefectos.length || selectedDefectos.includes(dato.TipoSegunda);
                const moduloValido = !selectedModulos.length || selectedModulos.includes(dato.OPRMODULEID_AT);
                const plantaValida = !selectedPlantas.length || selectedPlantas.includes(dato.PRODPOOLID);

                return fechaValida && clienteValido && divisionValida && defectoValida && moduloValido &&
                    plantaValida;
            });

            // Volver a paginar y renderizar los datos filtrados
            paginarDatos(datosFiltrados);
            renderizarTabla(paginatedData[currentPage - 1]);
            mostrarBotonesPaginacion();
        }
    </script>
    <script>
        $(document).ready(function() {
            ObtenerSegundas();

            // Inicializar flatpickr para el elemento con id #Fecha
            flatpickr(".flatpickr", {
                mode: "range",
                dateFormat: "d-m-Y",
                wrap: true,
                onChange: function(selectedDatesArray) {
                    selectedDates = selectedDatesArray.map(date => flatpickr.formatDate(date, "Y-m-d"));
                    filtrarDatos(); // Aplicar filtro
                },
            });

            // Eventos de filtros (reutilizados para actualizar tabla y gráfica)
            $(document).on("change",
                "#checkbox-planta, #checkbox-modulo, #checkbox-division, #checkbox-cliente, #checkbox-defectos",
                function() {
                    const value = $(this).val();
                    const type = $(this).attr("id").replace("checkbox-", "selected");
                    if ($(this).is(":checked")) {
                        window[type].push(value);
                    } else {
                        window[type] = window[type].filter(item => item !== value);
                    }
                    filtrarDatos();
                });

            // Botón para retroceder a la vista anterior
            $(document).on("click", "#regresarBtn", function() {
                if (previousChartData) {
                    renderGrafica(previousChartData);
                    $('#regresarBtn').hide(); // Ocultar el botón cuando estamos en la gráfica principal
                }
            });
        });

        function ObtenerSegundas() {
            $("#spinnerTable").show();
            $.ajax({
                url: "/ObtenerSegundas",
                method: "GET",
                success: function(response) {
                    if (response.status === "success") {
                        obtenerSegundasCargado = true;
                        $("#spinnerTable").hide();
                        allData = response.data;

                        // Paginación inicial y primera renderización
                        paginarDatos(allData);
                        renderizarTabla(paginatedData[currentPage - 1]);
                        mostrarBotonesPaginacion();
                        renderGrafica(allData); // Cargar gráfica inicial
                    } else {
                        alert("No se pudieron obtener los datos correctamente.");
                        $("#spinnerTable").hide();
                    }
                },
                error: function() {
                    alert("Hubo un error al obtener los datos.");
                    $("#spinnerTable").hide();
                },
            });
        }

        function filtrarDatos() {
            let datosFiltrados = allData.filter(dato => {
                const fechaValida = !selectedDates.length || (new Date(dato.TRANSDATE).getTime() >= new Date(
                    selectedDates[0]).getTime() && new Date(dato.TRANSDATE).getTime() <= new Date(
                    selectedDates[1]).getTime());
                const clienteValido = !selectedClientes.length || selectedClientes.includes(dato.CUSTOMERNAME);
                const divisionValida = !selectedDivisiones.length || selectedDivisiones.includes(dato.DIVISIONNAME);
                const defectoValida = !selectedDefectos.length || selectedDefectos.includes(dato.TipoSegunda);
                const moduloValido = !selectedModulos.length || selectedModulos.includes(dato.OPRMODULEID_AT);
                const plantaValida = !selectedPlantas.length || selectedPlantas.includes(dato.PRODPOOLID);

                return fechaValida && clienteValido && divisionValida && defectoValida && moduloValido &&
                    plantaValida;
            });

            // Actualizar tabla y gráfica con los datos filtrados
            paginarDatos(datosFiltrados);
            renderizarTabla(paginatedData[currentPage - 1]);
            mostrarBotonesPaginacion();
            renderGrafica(datosFiltrados); // Actualizar gráfica
        }

        function renderGrafica(data) {
            previousChartData = data; // Guardar datos de la gráfica actual para retroceder

            const groupedByPlanta = groupBy(data, "PRODPOOLID");
            const seriesData = Object.keys(groupedByPlanta).map(planta => {
                const totalQty = groupedByPlanta[planta].reduce((sum, item) => sum + parseFloat(item.QTY), 0);
                return {
                    name: planta,
                    y: totalQty
                };
            });

            Highcharts.chart("SegundasGrafics", {
                chart: {
                    type: "column",
                    backgroundColor: "transparent" // Fondo transparente
                },
                title: {
                    text: "Segundas por Planta",
                    style: {
                        color: '#ffffff'
                    }
                },
                xAxis: {
                    type: "category",
                    title: {
                        text: "Plantas",
                        style: {
                            color: '#ffffff'
                        }
                    },
                    labels: {
                        style: {
                            color: '#ffffff'
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: "Cantidad (QTY)",
                        style: {
                            color: '#ffffff'
                        }
                    },
                    labels: {
                        style: {
                            color: '#ffffff'
                        }
                    }
                },
                tooltip: {
                    pointFormat: "Cantidad: <b>{point.y}</b>",
                    style: {
                        color: '#000000'
                    }
                },
                plotOptions: {
                    series: {
                        cursor: "pointer",
                        dataLabels: {
                            enabled: true,
                            format: "{point.y}",
                            style: {
                                color: '#ffffff'
                            }
                        },
                        point: {
                            events: {
                                click: function() {
                                    mostrarClientes(this.name, groupedByPlanta[this.name]);
                                    $('#regresarBtn').show(); // Mostrar el botón de regreso
                                },
                            },
                        },
                    },
                },
                series: [{
                    name: "Plantas",
                    colorByPoint: true,
                    data: seriesData
                }],
            });
        }

        function mostrarClientes(planta, plantaData) {
            const groupedByCliente = groupBy(plantaData, "CUSTOMERNAME");
            const seriesData = Object.keys(groupedByCliente)
                .map(cliente => {
                    const totalQty = groupedByCliente[cliente].reduce((sum, item) => sum + parseFloat(item.QTY), 0);
                    return {
                        name: cliente,
                        y: totalQty
                    };
                })
                .sort((a, b) => b.y - a.y); // Ordenar de mayor a menor por cantidad

            Highcharts.chart("SegundasGrafics", {
                chart: {
                    type: "column",
                    backgroundColor: "transparent" // Fondo transparente
                },
                title: {
                    text: `Clientes en Planta: ${planta}`,
                    style: {
                        color: '#ffffff'
                    }
                },
                xAxis: {
                    type: "category",
                    title: {
                        text: "Clientes",
                        style: {
                            color: '#ffffff'
                        }
                    },
                    labels: {
                        style: {
                            color: '#ffffff'
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: "Cantidad (QTY)",
                        style: {
                            color: '#ffffff'
                        }
                    },
                    labels: {
                        style: {
                            color: '#ffffff'
                        }
                    }
                },
                tooltip: {
                    pointFormat: "Cantidad: <b>{point.y}</b>",
                    style: {
                        color: '#000000'
                    }
                },
                plotOptions: {
                    series: {
                        cursor: "pointer",
                        dataLabels: {
                            enabled: true,
                            format: "{point.y}",
                            style: {
                                color: '#ffffff'
                            }
                        },
                        point: {
                            events: {
                                click: function() {
                                    mostrarDivisionesYProblemas(this.name, groupedByCliente[this.name]);
                                },
                            },
                        },
                    },
                },
                series: [{
                    name: "Clientes",
                    colorByPoint: true,
                    data: seriesData
                }],
            });
        }

        function mostrarDivisionesYProblemas(cliente, clienteData) {
            const groupedByDivision = groupBy(clienteData, "DIVISIONNAME");
            const seriesData = Object.keys(groupedByDivision).map(division => {
                const totalQty = groupedByDivision[division].reduce((sum, item) => sum + parseFloat(item.QTY), 0);
                return {
                    name: division,
                    y: totalQty
                };
            });

            Highcharts.chart("SegundasGrafics", {
                chart: {
                    type: "column",
                    backgroundColor: "transparent" // Fondo transparente
                },
                title: {
                    text: `Divisiones de Cliente: ${cliente}`,
                    style: {
                        color: '#ffffff'
                    }
                },
                xAxis: {
                    type: "category",
                    title: {
                        text: "Divisiones",
                        style: {
                            color: '#ffffff'
                        }
                    },
                    labels: {
                        style: {
                            color: '#ffffff'
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: "Cantidad (QTY)",
                        style: {
                            color: '#ffffff'
                        }
                    },
                    labels: {
                        style: {
                            color: '#ffffff'
                        }
                    }
                },
                tooltip: {
                    pointFormat: "Cantidad: <b>{point.y}</b>",
                    style: {
                        color: '#000000'
                    }
                },
                plotOptions: {
                    series: {
                        cursor: "pointer",
                        dataLabels: {
                            enabled: true,
                            format: "{point.y}",
                            style: {
                                color: '#ffffff'
                            }
                        },
                        point: {
                            events: {
                                click: function() {
                                    mostrarProblemas(this.name, groupedByDivision[this.name]);
                                },
                            },
                        },
                    },
                },
                series: [{
                    name: "Divisiones",
                    colorByPoint: true,
                    data: seriesData
                }],
            });
        }

        function mostrarProblemas(division, divisionData) {
            const groupedByDefecto = groupBy(divisionData, "TipoSegunda");
            const seriesData = Object.keys(groupedByDefecto).map(defecto => {
                const totalQty = groupedByDefecto[defecto].reduce((sum, item) => sum + parseFloat(item.QTY), 0);
                return {
                    name: defecto,
                    y: totalQty
                };
            });

            Highcharts.chart("SegundasGrafics", {
                chart: {
                    type: "column",
                    backgroundColor: "transparent" // Fondo transparente
                },
                title: {
                    text: `Tipos de Problema en División: ${division}`,
                    style: {
                        color: '#ffffff'
                    }
                },
                xAxis: {
                    type: "category",
                    title: {
                        text: "Tipos de Problema",
                        style: {
                            color: '#ffffff'
                        }
                    },
                    labels: {
                        style: {
                            color: '#ffffff'
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: "Cantidad (QTY)",
                        style: {
                            color: '#ffffff'
                        }
                    },
                    labels: {
                        style: {
                            color: '#ffffff'
                        }
                    }
                },
                tooltip: {
                    pointFormat: "Cantidad: <b>{point.y}</b>",
                    style: {
                        color: '#000000'
                    }
                },
                plotOptions: {
                    series: {
                        cursor: "pointer",
                        dataLabels: {
                            enabled: true,
                            format: "{point.y}",
                            style: {
                                color: '#ffffff'
                            }
                        },
                        point: {
                            events: {
                                click: function() {
                                    mostrarDescripcionCalidad(this.name, groupedByDefecto[this.name]);
                                },
                            },
                        },
                    },
                },
                series: [{
                    name: "Problemas",
                    colorByPoint: true,
                    data: seriesData
                }],
            });
        }

        function mostrarDescripcionCalidad(defecto, defectoData) {
            const groupedByDescripcion = groupBy(defectoData, "DescripcionCalidad");
            const seriesData = Object.keys(groupedByDescripcion).map(descripcion => {
                const totalQty = groupedByDescripcion[descripcion].reduce((sum, item) => sum + parseFloat(item.QTY),
                    0);
                return {
                    name: descripcion,
                    y: totalQty
                };
            });

            Highcharts.chart("SegundasGrafics", {
                chart: {
                    type: "column",
                    backgroundColor: "transparent" // Fondo transparente
                },
                title: {
                    text: `Descripción de Calidad para el Problema: ${defecto}`,
                    style: {
                        color: '#ffffff'
                    }
                },
                xAxis: {
                    type: "category",
                    title: {
                        text: "Descripción de Calidad",
                        style: {
                            color: '#ffffff'
                        }
                    },
                    labels: {
                        style: {
                            color: '#ffffff'
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: "Cantidad (QTY)",
                        style: {
                            color: '#ffffff'
                        }
                    },
                    labels: {
                        style: {
                            color: '#ffffff'
                        }
                    }
                },
                tooltip: {
                    pointFormat: "Cantidad: <b>{point.y}</b>",
                    style: {
                        color: '#000000'
                    }
                },
                plotOptions: {
                    series: {
                        cursor: "pointer",
                        dataLabels: {
                            enabled: true,
                            format: "{point.y}",
                            style: {
                                color: '#ffffff'
                            }
                        },
                    },
                },
                series: [{
                    name: "Descripción de Calidad",
                    colorByPoint: true,
                    data: seriesData
                }],
            });
        }
        function groupBy(data, key) {
            return data.reduce(function(result, item) {
                const groupKey = item[key];
                if (!result[groupKey]) result[groupKey] = [];
                result[groupKey].push(item);
                return result;
            }, {});
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        // Función para exportar toda la tabla a Excel
        function exportTableToExcel(tableID, filename) {
            // Obtenemos la tabla completa
            const table = document.getElementById(tableID);

            // Creamos un nuevo libro de Excel
            const wb = XLSX.utils.book_new();

            // Obtenemos todos los datos (sin paginación)
            const allRows = allData; // Usamos allData, que contiene todos los datos sin paginación

            // Agregar encabezado combinado "Reporte segundas"
            const totalCols = table.rows[0].cells.length; // Total de columnas
            const wsData = [];

            // Encabezado combinado
            wsData.push([{
                v: "Reporte segundas",
                s: {
                    font: { bold: true, sz: 16 },
                    alignment: { horizontal: "center", vertical: "center" },
                    border: getExcelBorderStyle() // Aseguramos que tenga borde
                }
            }]);
            wsData[0].length = totalCols; // Ajusta para abarcar todas las columnas

            // Agregar encabezados de la tabla
            const headers = [];
            for (let cell of table.rows[0].cells) {
                headers.push({
                    v: cell.innerText,
                    s: {
                        font: { bold: true },
                        alignment: { horizontal: "center", vertical: "center" },
                        border: getExcelBorderStyle() // Aseguramos que tenga borde
                    }
                });
            }
            wsData.push(headers);

            // Agregar filas completas de los datos a la hoja de Excel
            allRows.forEach((dato, index) => {
                const rowData = [
                    index + 1, // Contador de registros (número de fila)
                    dato.PRODPOOLID,
                    dato.OPRMODULEID_AT,
                    dato.CUSTOMERNAME,
                    dato.DIVISIONNAME,
                    dato.TipoSegunda,
                    dato.DescripcionCalidad,
                    dato.PRODTICKETID,
                    dato.QTY,
                    dato.TRANSDATE
                ].map(cellValue => {
                    return {
                        v: cellValue,
                        s: { border: getExcelBorderStyle() } // Aplicamos bordes a cada celda
                    };
                });
                wsData.push(rowData);
            });

            // Crear la hoja de Excel
            const ws = XLSX.utils.aoa_to_sheet(wsData, { cellStyles: true }); // cellStyles: true es crucial

            // Calcular el ancho de las columnas dinámicamente
            const wscols = [];
            for (let col = 0; col < totalCols; col++) {
                let maxLength = 10; // Ancho mínimo para cada columna
                // Revisar todas las filas para encontrar la longitud máxima del contenido en la columna
                for (let row = 0; row < wsData.length; row++) {
                    const cellValue = wsData[row][col] ? wsData[row][col].v : ''; // Obtener el valor de la celda
                    const cellLength = cellValue.toString().length; // Calcular la longitud
                    maxLength = Math.max(maxLength, cellLength); // Encontrar el valor máximo
                }
                wscols.push({ wch: maxLength + 2 }); // Añadir un pequeño espacio extra al ancho
            }
            ws["!cols"] = wscols; // Aplicar el ancho calculado

            // Unir las celdas del encabezado
            ws["!merges"] = [{ s: { r: 0, c: 0 }, e: { r: 0, c: totalCols - 1 } }]; // Combina desde la primera hasta la última columna

            // Agregar la hoja al libro
            XLSX.utils.book_append_sheet(wb, ws, "Reporte segundas");

            // Descargar el archivo Excel
            XLSX.writeFile(wb, filename);
        }

        // Función para definir los estilos de borde en Excel
        function getExcelBorderStyle() {
            return {
                top: { style: "thin" },
                bottom: { style: "thin" },
                left: { style: "thin" },
                right: { style: "thin" }
            };
        }

        // Evento del botón para generar el archivo Excel
        document.querySelector('[data-tooltip-target="tooltip-download"]').addEventListener("click", function () {
            exportTableToExcel("TableSegundas", "reporte_segundas.xlsx");
        });
    </script>







<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'Segundas', 'titlePage' => __('Segundas')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\Segundas\Segundas.blade.php ENDPATH**/ ?>