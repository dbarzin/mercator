const mix = require('laravel-mix');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.js('resources/assets/js/app.js', 'public/js/custom-mix.js')
    .sass('resources/assets/scss/app.scss', 'public/css/custom-mix.css');
