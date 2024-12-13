import $ from 'jquery';
import 'select2'; // Esto habilita el método `select2` en jQuery
import 'select2/dist/css/select2.css'; // Opcional, si quieres incluir estilos desde npm

const opSelect = $('#op'); // Usa jQuery para seleccionar el elemento

if (opSelect.length) {
    try {
        // Inicializa select2
        opSelect.select2({
            placeholder: 'Selecciona una opción',
            allowClear: true,
        });
    } catch (error) {
        console.error('Error inicializando select2:', error);
    }

    // Maneja el evento `change` de select2
    opSelect.on('change', function (this: HTMLElement) {
        const selectedValue = $(this).val(); // Obtiene el valor seleccionado

        // Actualiza la URL
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('op', selectedValue as string);
        window.history.pushState({}, '', currentUrl.toString());
    });
}
