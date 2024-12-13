import $ from 'jquery'; // Importa jQuery
import 'select2'; // Habilita el método `select2` en jQuery
import 'select2/dist/css/select2.css'; // Incluye los estilos de select2 desde npm (opcional)

// Función reutilizable para inicializar select2
function initializeSelect2(selector: string, options: JQuery.Select2Options): void {
    const element = $(selector);
    if (element.length) {
        try {
            element.select2(options); // Inicializa select2 con las opciones proporcionadas
        } catch (error) {
            console.error(`Error inicializando select2 en ${selector}:`, error);
        }
    }
}

// Opciones de configuración para select2
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
function cargarOpcionesIniciales() {
    $.ajax({
        url: '/obtener-opciones-op',
        method: 'GET',
        success: function (data) {
            const opSelect = $('#op-seleccion-ts');
            opSelect.empty(); // Limpia cualquier opción previa

            // Agrega una opción por defecto
            opSelect.append(new Option('Selecciona una opción', ''));

            // Agrega las nuevas opciones desde la respuesta
            data.forEach((item: { prodid: string }) => {
                opSelect.append(new Option(item.prodid, item.prodid));
            });

            // Vuelve a inicializar select2
            opSelect.select2(select2Options);

            // Selecciona el valor basado en el parámetro de la URL
            const selectedValue = getParameterByName('op');
            if (selectedValue) {
                opSelect.val(selectedValue).trigger('change'); // Selecciona y actualiza
            }
        },
        error: function (error) {
            console.error('Error al cargar las opciones iniciales:', error);
        },
    });
}

// Llama a la función para cargar las opciones al inicio
$(document).ready(() => {
    cargarOpcionesIniciales();
});

// Actualiza la URL dinámicamente cuando cambia el valor
const opSelect = $('#op-seleccion-ts');
if (opSelect.length) {
    opSelect.on('change', function (this: HTMLElement) {
        const selectedValue = $(this).val() as string | null; // Obtiene el valor seleccionado
        if (selectedValue) {
            // Actualiza la URL con el valor seleccionado
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('op', selectedValue);
            window.history.pushState({}, '', currentUrl.toString());
        }
    });
}
