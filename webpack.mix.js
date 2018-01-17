let mix = require('laravel-mix');

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

 mix.extract([
    'jquery',
    'popper.js',
    'bootstrap'
], 'public/js/vendor.js')
.autoload({
    jquery: ['$', 'global.jQuery', 'jQuery', 'global.$', 'jquery', 'jQuery', 'global.jquery'],
    'popper.js': ['Popper', 'window.Popper'],
    bootstrap: []
})
.js('resources/assets/js/app.js', 'public/js')
.sass('resources/assets/sass/app.scss', 'public/css')
.version();