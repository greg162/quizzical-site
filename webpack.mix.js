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


mix.setPublicPath('public_html')
.js(['resources/js/app.js'], 'js/app.js')
.copy('resources/home/bootstrap.min.css', 'public_html/css/home-bootstrap.css')
.copyDirectory('resources/img', 'public_html/img')
.sass('resources/sass/main.scss', 'css/app.css')
.copyDirectory('resources/home', 'public_html/home');


