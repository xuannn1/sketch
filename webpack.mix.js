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
   .sass('resources/assets/sass/app.scss', 'public/css/sass.css')
   .styles([
    'node_modules/bootstrap-markdown/css/bootstrap-markdown.min.css',
    'public/css/sass.css'
      ], 'public/css/app.css');
mix.scripts([
    'public/js/app.js',
    'resources/assets/js/bbcode_parser.js',
    'resources/assets/js/sosad.js'
      ], 'public/js/all.js');
if (mix.inProduction()) {
  mix.version();
}
