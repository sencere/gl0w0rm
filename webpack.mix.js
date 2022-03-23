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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

//mix.copy('resources/fonts', 'public/');
mix.copy('resources/js/p5.min.js', 'public/js');
mix.combine(['resources/js/app/sketch.js', 
    'resources/js/app/firefly.js',
    'resources/js/app/startButton.js',
    'resources/js/app/postBar.js'], 'public/js/merged.js');
