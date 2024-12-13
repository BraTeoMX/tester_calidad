/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/*!***********************************!*\
  !*** ./resources/ts/op-select.ts ***!
  \***********************************/


// Selecciona el elemento del DOM
var opSelect = document.getElementById('op');
if (opSelect) {
  opSelect.addEventListener('change', function (event) {
    var selectedValue = opSelect.value; // Valor seleccionado en el select
    // Obtén la URL actual
    var currentUrl = new URL(window.location.href);
    // Actualiza el parámetro `op` en la URL
    currentUrl.searchParams.set('op', selectedValue);
    // Cambia el historial de la URL sin recargar la página
    window.history.pushState({}, '', currentUrl.toString());
  });
}
/******/ })()
;