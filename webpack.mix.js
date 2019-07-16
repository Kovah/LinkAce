const mix = require('laravel-mix');

mix.options({
  processCssUrls: false
});

mix.autoload({ 'jquery': ['window.$', 'window.jQuery'] });

mix.disableNotifications();

mix.js('resources/assets/js/app.js', 'assets/dist/js');
mix.js('resources/assets/js/fontawesome.js', 'assets/dist/js');

mix.combine([
  'node_modules/jquery/dist/jquery.min.js',
  'node_modules/bootstrap/dist/js/bootstrap.min.js',
  'node_modules/selectize/dist/js/standalone/selectize.min.js'
], 'public/assets/dist/js/dependencies.js');

mix.sass('resources/assets/sass/app.scss', 'assets/dist/css')
  .sass('resources/assets/sass/app-dark.scss', 'assets/dist/css')
  .sass('resources/assets/sass/loader.scss', 'assets/dist/css');
