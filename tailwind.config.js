/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js",
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('flowbite-typography'),
        require('flowbite/plugin')
        ({
            charts: true,
            datatables: true,
            wysiwyg: true,
        }),
    ],
}