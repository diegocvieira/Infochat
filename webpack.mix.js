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

mix.js('resources/assets/js/global.js', 'public/js')
    .sass('resources/assets/sass/global.scss', 'public/css')
    .copyDirectory('resources/assets/img', 'public/img')
    .copyDirectory('resources/assets/fonts', 'public/fonts');

if(mix.inProduction()) {
    mix.version(['public/js/global.js', 'public/css/global.css']);
}
