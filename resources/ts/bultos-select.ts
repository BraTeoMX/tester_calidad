import $ from 'jquery'; // Importa jQuery
import 'select2'; // Habilita el método `select2` en jQuery
import 'select2/dist/css/select2.css'; // Incluye los estilos de select2 desde npm (opcional)

// Opciones para inicializar Select2
const select2Options: JQuery.Select2Options = {
    placeholder: 'Selecciona una opción',
    allowClear: true,
};

// Función para obtener el valor de un parámetro en la URL
function getParameterByName(name: string): string | null {
    const url = new URL(window.location.href);
    return url.searchParams.get(name);
}

// Función para cargar opciones al inicio
function cargarOpcionesBulto() {
    // Obtén el valor seleccionado del select OP
    const opSeleccionada = $('#op-seleccion-ts').val();

    if (!opSeleccionada) {
        console.error('No se ha seleccionado un valor para OP.');
        return;
    }

    $.ajax({
        url: '/obtener-opciones-bulto',
        method: 'GET',
        data: { op: opSeleccionada }, // Enviar el valor seleccionado como parámetro
        success: function (data) {
            const bultoSelect = $('#bulto');
            bultoSelect.empty(); // Limpia cualquier opción previa

            // Agrega una opción por defecto
            bultoSelect.append(new Option('Selecciona una opción', ''));

            // Agrega las nuevas opciones desde la respuesta
            data.forEach((item: { id: string; nombre: string }) => {
                bultoSelect.append(new Option(item.nombre, item.id));
            });

            // Inicializa select2
            bultoSelect.select2(select2Options);
        },
        error: function (error) {
            console.error('Error al cargar las opciones de bulto:', error);
        },
    });
}

// Llama a la función cuando cambie el valor del select OP
$('#op-seleccion-ts').on('change', function () {
    cargarOpcionesBulto();
});

// Llama a la función para cargar las opciones al inicio
$(document).ready(() => {
    cargarOpcionesBulto();
});
