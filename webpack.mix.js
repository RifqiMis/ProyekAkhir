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

mix.js([
   'resources/js/app.js',
   'resources/js/custom.js',
],
   'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .styles([
      'resources/css/style.css',
   ], 'public/css/style.css');

/**
 * Fix for instascan as detailed in https://github.com/webpack-contrib/css-loader/issues/447#issuecomment-285600014
 */
mix.webpackConfig({
   node: {
      fs: "empty"
   }
});

// mix.babel([
// 'resources/js/es6pg.js',
// ],
//    'public/js/all.js'
// );
