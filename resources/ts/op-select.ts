// Selecciona el elemento del DOM
const opSelect = document.getElementById('op') as HTMLSelectElement;

if (opSelect) {
    opSelect.addEventListener('change', (event) => {
        const selectedValue = opSelect.value; // Valor seleccionado en el select

        // Obtén la URL actual
        const currentUrl = new URL(window.location.href);

        // Actualiza el parámetro `op` en la URL
        currentUrl.searchParams.set('op', selectedValue);

        // Cambia el historial de la URL sin recargar la página
        window.history.pushState({}, '', currentUrl.toString());
    });
}
