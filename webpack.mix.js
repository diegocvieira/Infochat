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
    .js('resources/assets/js/mobile-global.js', 'public/js')
    .sass('resources/assets/sass/global.scss', 'public/css')
    .sass('resources/assets/sass/mobile/mobile-global.scss', 'public/css')
    .copyDirectory('resources/assets/img', 'public/img')
    .copyDirectory('resources/assets/fonts', 'public/fonts')
    .copyDirectory('resources/assets/offline-developer', 'public/offline-developer');

if(mix.inProduction()) {
    mix.version(['public/js/global.js', 'public/js/mobile-global.js', 'public/css/global.css', 'public/css/mobile-global.css']);
}
