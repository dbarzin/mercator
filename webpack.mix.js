const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/js/app.js', 'public/js');
//   .sass('resources/sass/app.scss', 'public/css');

// mix.js('resources/js/app.js', 'public/js').vue();

// JQuery

mix.copy('node_modules/jquery/dist/jquery.min.map','public/js');
mix.copy('node_modules/jquery/dist/jquery.min.js','public/js');

// Bootstrap
mix.copy('node_modules/bootstrap/dist/css/bootstrap.min.css','public/css');
mix.copy('node_modules/bootstrap/dist/css/bootstrap.min.css.map','public/css');
mix.copy('node_modules/bootstrap/dist/js/bootstrap.bundle.min.js','public/js');
mix.copy('node_modules/bootstrap/dist/js/bootstrap.bundle.min.js.map','public/js');

/*
// bootstrap-datetimepicker
mix.copy('node_modules/bootstrap-datetimepicker/src/js/bootstrap-datetimepicker.js','public/js');

// popper
mix.copy('node_modules/@popperjs/core/dist/umd/popper.min.js','public/js');
mix.copy('node_modules/@popperjs/core/dist/umd/popper.min.js.map','public/js');

// coreui
mix.copy('node_modules/@coreui/coreui/dist/css/coreui.min.css','public/css');
mix.copy('node_modules/@coreui/coreui/dist/css/coreui.min.css.map','public/css');
mix.copy('node_modules/@coreui/coreui/dist/js/coreui.min.js','public/js');
mix.copy('node_modules/@coreui/coreui/dist/js/coreui.min.js.map','public/js');

mix.copy('node_modules/@coreui/coreui/dist/js/bootstrap.bundle.min.js','public/js');
mix.copy('node_modules/@coreui/coreui/dist/js/bootstrap.bundle.min.js.map','public/js');

// Fontawesome
mix.copy('node_modules/font-awesome/css/font-awesome.min.css','public/css');
// mix.copy('node_modules/@fortawesome/fontawesome-free/css/solid.min.css','public/css');
// mix.copy('node_modules/@fortawesome/fontawesome-free/webfonts/fa-solid-900.woff2','public/webfonts');
mix.copy('node_modules/font-awesome/fonts/fontawesome-webfont.woff2','public/webfonts');

// dataTables
mix.copy('node_modules/datatables.net/js/dataTables.min.js','public/js');
mix.copy('node_modules/datatables.net-select/js/dataTables.select.min.js','public/js');
mix.copy('node_modules/datatables.net-bs5/js/dataTables.bootstrap5.js','public/js');
mix.copy('node_modules/datatables.net-buttons/js/dataTables.buttons.min.js','public/js');
mix.copy('node_modules/datatables.net-searchbuilder/js/dataTables.searchBuilder.min.js','public/js');
mix.copy('node_modules/datatables.net-buttons/js/buttons.colVis.min.js','public/js');
mix.copy('node_modules/datatables.net-buttons/js/buttons.html5.min.js','public/js');
mix.copy('node_modules/datatables.net-buttons/js/buttons.print.min.js','public/js');

// JSZip
mix.copy('node_modules/jszip/dist/jszip.min.js','public/js');

// PDFMake
mix.copy('node_modules/pdfmake/build/pdfmake.min.js','public/js');
mix.copy('node_modules/pdfmake/build/pdfmake.min.js.map','public/js');
mix.copy('node_modules/pdfmake/build/vfs_fonts.js','public/js');

// select2
mix.copy('node_modules/select2/dist/js/select2.min.js','public/js');
mix.copy('node_modules/select2/dist/css/select2.min.css','public/css');

// Chartjs
mix.copy('node_modules/chartjs/chart.js','public/js');

// DropZone
mix.copy('node_modules/dropzone/dist/dropzone-min.js','public/js');
mix.copy('node_modules/dropzone/dist/dropzone-min.js.map','public/js');
mix.copy('node_modules/dropzone/dist/dropzone.css','public/css');
mix.copy('node_modules/dropzone/dist/dropzone.css.map','public/css');

// SweetAlert2
mix.copy('node_modules/sweetalert2/dist/sweetalert2.min.js','public/js');
mix.copy('node_modules/sweetalert2/dist/sweetalert2.css','public/css');

// CKEditor
mix.copy('node_modules/@ckeditor/ckeditor5-build-classic/build/ckeditor.js','public/js');
mix.copy('node_modules/@ckeditor/ckeditor5-editor-classic/theme/classiceditor.css','public/css');

// Moment
mix.copy('node_modules/moment/min/moment.min.js','public/js');
mix.copy('node_modules/moment/min/moment.min.js.map','public/js');
*/
