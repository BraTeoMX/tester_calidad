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

// Inicializa select2 en el elemento con ID `#op-seleccion-ts`
initializeSelect2('#op-seleccion-ts', select2Options);

// Selecciona el elemento del DOM
const opSelect = $('#op-seleccion-ts'); // Usa jQuery para seleccionar el elemento

if (opSelect.length) {
    // Maneja el evento `change` de select2
    opSelect.on('change', function (this: HTMLElement) {
        const selectedValue = $(this).val() as string | null; // Obtiene el valor seleccionado (puede ser string o null)

        if (selectedValue) {
            // Actualiza la URL con el nombre correcto del parámetro
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('op', selectedValue); // Cambia a 'op' en lugar de 'op-seleccion-ts'
            window.history.pushState({}, '', currentUrl.toString());
        }
    });
}
