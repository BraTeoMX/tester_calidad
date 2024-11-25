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
                <!-- Tarjeta Divisiones y Clientes -->
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card card-stats">
                        <div class="card-header card-header-success card-header-icon">
                            <div class="card-icon">
                                <span class="material-symbols-outlined">location_away</span>
                            </div>
                            <h3 class="card-title">Clientes por División</h3>
                            <b>Cliente/Divicion</b>
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
                                                    <th scope="col" class="px-6 py-3">
                                                        #
                                                    </th>
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

                                            </tbody>
                                        </table>
                                        <nav class="flex items-center flex-column flex-wrap md:flex-row justify-between pt-4"
                                            aria-label="Table navigation">
                                            <span
                                                class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-4 md:mb-0 block w-full md:inline md:w-auto">Datos
                                                <span class="font-semibold text-gray-900 dark:text-white"> </span> de
                                                <span class="font-semibold text-gray-900 dark:text-white"> </span>
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

        @keyframes spin {
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

        @keyframes spin {
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

            function ObtenerPlantas() {
                if (obtenerSegundasCargado) {
                    $.ajax({
                        url: '/ObtenerPlantas',
                        method: "GET",
                        success: function(response) {
                            if (response.status === "success") {
                                $('#dropdownSearchPlanta ul').empty();

                                response.ObtenerPlantas.forEach(function(plant) {
                                    $('#dropdownSearchPlanta ul').append(
                                        `<li class="py-1 px-2 hover:bg-gray-600 cursor-pointer">
                                        <input id="checkbox-item-${plant}"
                                               type="checkbox"
                                               value="${plant}"
                                               class="planta-checkbox w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        ${plant}
                                    </li>`
                                    );
                                });

                                $("#spinner").hide();
                            } else {
                                console.error("No se recibieron datos válidos");
                                $("#spinner").hide();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al obtener plantas:", error);
                            $("#spinner").hide();
                        }
                    });
                } else {
                    // Si obtenerSegundasCargado no ha terminado, esperar 100ms y volver a intentar
                    setTimeout(ObtenerPlantas, 100);
                }
            }

            // Evento de cambio para los checkboxes de planta
            $(document).on('change', '.planta-checkbox', function() {
                const plant = $(this).val();
                if ($(this).is(':checked')) {
                    selectedPlantas.push(plant);
                } else {
                    selectedPlantas = selectedPlantas.filter(p => p !== plant);
                }
                console.log('Plantas seleccionadas:', selectedPlantas);
            });

            // Llamar a la función para cargar las plantas al iniciar
            ObtenerPlantas();
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
                                        <input id="checkbox-modulo-${modulo}"
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
        $(document).ready(function() {
            let selectedDivisiones = [];
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
                                    // Cliente como encabezado
                                    $('#dropdownMultiLevelClienteDivcion ul').append(`
                            <li class="py-2 px-2 bg-gray-700 text-white font-bold">${cliente}</li>
                        `);

                                    // Divisiones como subelementos
                                    clientesDivisiones[cliente].forEach(division => {
                                        $('#dropdownMultiLevelClienteDivcion ul')
                                            .append(`
                                <li class="py-1 px-4 hover:bg-gray-600 cursor-pointer">
                                    <input id="checkbox-division-${division}"
                                           type="checkbox"
                                           value="${division}"
                                           data-cliente="${cliente}"
                                           class="division-checkbox w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    ${division}
                                </li>
                            `);
                                    });
                                });

                                // Evento para cambios en los checkboxes de divisiones
                                $('.division-checkbox').on('change', function() {
                                    const division = $(this).val();
                                    const cliente = $(this).data(
                                        'cliente'
                                        ); // Obtiene el cliente del atributo data-cliente

                                    if ($(this).is(':checked')) {
                                        selectedDivisiones.push({
                                            cliente,
                                            division
                                        });
                                    } else {
                                        selectedDivisiones = selectedDivisiones.filter(
                                            d => d.cliente !== cliente || d.division !==
                                            division
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
        let obtenerSegundasCargado = false;
        let currentPage = 1; // Página actual de los datos
        const itemsPerPage = 10; // Elementos por página
        let paginatedData = []; // Array que almacenará los datos paginados

        $(document).ready(function() {
            ObtenerSegundas();
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

                        // Paginación inicial al recibir los datos
                        paginarDatos(response.data);
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



            // Índice inicial para numerar los registros en la página actual
            const startIndex = (currentPage - 1) * itemsPerPage;

            datosPagina.forEach(function(dato, index) {
                let moduloNumero = parseInt(dato.OPRMODULEID_AT.replace(/\D/g, ''), 10);
                let planta = (moduloNumero >= 100 && moduloNumero < 200) ? "Planta Ixtlahuaca" :
                    (moduloNumero >= 200 && moduloNumero < 300) ? "Planta San Bartolo" : "Desconocida";
                    // Formatear la cantidad
               var cantidadFormateada = dato.QTY;
                            if (typeof cantidadFormateada === 'string') {
                                var puntoIndex = cantidadFormateada.indexOf('.');
                                if (puntoIndex !== -1) {
                                    var parteDecimal = cantidadFormateada.substring(
                                        puntoIndex + 1);
                                    if (parteDecimal.length > 1) {
                                        parteDecimal = parteDecimal.substring(0, 1);
                                    }
                                    cantidadFormateada = cantidadFormateada.substring(0,
                                        puntoIndex + 1) + parteDecimal;
                                }
                            }
                // Fila con contador de registro en el primer <td>
                let fila = `
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <td class="px-6 py-4">${startIndex + index + 1}</td> <!-- Contador de registro -->
                    <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800">${planta}</td>
                    <td class="px-6 py-4">${dato.OPRMODULEID_AT}</td>
                    <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800">${dato.CUSTOMERNAME}</td>
                    <td class="px-6 py-4">${dato.DIVISIONNAME}</td>
                    <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800">${dato.TipoSegunda}</td>
                    <td class="px-6 py-4">${dato.DescripcionCalidad}</td>
                    <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800">${dato.PRODTICKETID}</td>
                    <td class="px-6 py-4">${cantidadFormateada}</td>
                    <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800">${dato.TRANSDATE}</td>
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
    </script>
@endsection
