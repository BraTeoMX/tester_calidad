import $ from 'jquery';
import 'select2';
import 'select2/dist/css/select2.css';

const select2Options: JQuery.Select2Options = {
    placeholder: 'Selecciona una opción',
    allowClear: true,
};

// Función para cargar opciones de bulto basado en la OP seleccionada
function cargarOpcionesBulto(opSeleccionada: string | null) {
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

            // Inicializa o actualiza select2 en el select de bulto
            bultoSelect.select2(select2Options);
        },
        error: function (error) {
            console.error('Error al cargar las opciones de bulto:', error);
        },
    });
}

// Detectar cambios en el select de OP (op-seleccion-ts)
function configurarActualizacionBulto() {
    const opSelect = $('#op-seleccion-ts');
    if (opSelect.length) {
        opSelect.on('change', function () {
            const nuevaOpSeleccionada = $(this).val() as string | null; // Obtiene el valor seleccionado
            cargarOpcionesBulto(nuevaOpSeleccionada); // Llama a la función de carga con la nueva OP
        });
    }
}

// Inicializa el comportamiento en la carga de la página
$(document).ready(() => {
    // Carga inicial basada en el valor actual de la OP
    const opSeleccionadaInicial = $('#op-seleccion-ts').val() as string | null;
    cargarOpcionesBulto(opSeleccionadaInicial);

    // Configura el evento para actualizaciones dinámicas
    configurarActualizacionBulto();
});
