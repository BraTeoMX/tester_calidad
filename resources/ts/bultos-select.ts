import $ from 'jquery';
import 'select2';
import 'select2/dist/css/select2.css';

const select2Options: JQuery.Select2Options = {
    placeholder: 'Selecciona una opción',
    allowClear: true,
};

// Función para obtener un parámetro de la URL si lo necesitas
function getParameterByName(name: string): string | null {
    const url = new URL(window.location.href);
    return url.searchParams.get(name);
}

// Función para cargar opciones de bulto
function cargarOpcionesBulto() {
    // Ahora, en lugar de obtener el valor de otro select, obtendremos el valor de 'op' de la URL
    const opSeleccionada = getParameterByName('op');

    if (!opSeleccionada) {
        console.error('No se ha proporcionado una OP para cargar los bultos.');
        return;
    }

    $.ajax({
        url: '/obtener-opciones-bulto',
        method: 'GET',
        data: { op: opSeleccionada },
        success: function (data) {
            const bultoSelect = $('#bulto-seleccion');
            bultoSelect.empty(); // Limpia cualquier opción previa

            // Agrega una opción por defecto
            bultoSelect.append(new Option('Selecciona una opción', ''));

            // Agrega las nuevas opciones desde la respuesta
            data.forEach((item: { id: string; prodpackticketid: string }) => {
                bultoSelect.append(new Option(item.prodpackticketid));
            });

            // Inicializa select2 en el select de bulto
            bultoSelect.select2(select2Options);
        },
        error: function (error) {
            console.error('Error al cargar las opciones de bulto:', error);
        },
    });
}

// Como ya no dependemos del cambio de otro select, simplemente cargamos al inicio
$(document).ready(() => {
    cargarOpcionesBulto();
});
