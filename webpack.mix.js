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

mix.js('resources/assets/js/app.js', 'public/js')
    .js('resources/assets/js/party.js', 'public/js')
    .js('resources/assets/js/constituency.js', 'public/js')
    .js('resources/assets/js/constituencies.js', 'public/js')
    .js('resources/assets/js/candidate.js', 'public/js')
    .js('resources/assets/js/vote.js', 'public/js')
    .js('resources/assets/js/voter.js', 'public/js')
    .js('resources/assets/js/verify.js', 'public/js')
    .js('resources/assets/js/prevote.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css');
