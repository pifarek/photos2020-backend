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

mix.options({
    processCssUrls: false
});

mix.js('resources/js/default.js', 'public/js');

mix.scripts([
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/bootstrap/dist/js/bootstrap.bundle.js',
    'node_modules/sweetalert2/dist/sweetalert2.all.js',
    'node_modules/bootstrap-select/dist/js/bootstrap-select.js',
    'node_modules/@fancyapps/fancybox/dist/jquery.fancybox.js',
    'public/js/default.js',
], 'public/js/build.js');

mix.sass('resources/sass/default.scss', 'public/css');

mix.styles([
    'node_modules/bootstrap/dist/css/bootstrap.min.css',
    'resources/css/bootstrap_theme.css',
    'node_modules/@fortawesome/fontawesome-free/css/all.css',
    'node_modules/sweetalert2/dist/sweetalert2.css',
    'node_modules/bootstrap-select/dist/css/bootstrap-select.css',
    'node_modules/jquery-ui/themes/base/core.css',
    'node_modules/jquery-ui/themes/base/datepicker.css',
    'node_modules/jquery-ui/themes/base/menu.css',
    'node_modules/jquery-ui/themes/base/autocomplete.css',
    'node_modules/jquery-ui/themes/base/theme.css',
    'node_modules/blueimp-file-upload/css/jquery.fileupload.css',
    'node_modules/@fancyapps/fancybox/dist/jquery.fancybox.css',
    'public/css/default.css',
], 'public/css/build.css');

// Copy Fonts
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/webfonts');

// Copy Jquery-UI icons
mix.copyDirectory('node_modules/jquery-ui/themes/base/images', 'public/css/images');
