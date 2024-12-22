let mix = require('laravel-mix');                           // If you are new to this then please visit https://laravel.com/docs/5.5/mix
const webpack = require('webpack');

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

let plugin = 'resources/assets/plugins/';
const CompressionPlugin = require('compression-webpack-plugin');

mix.js('resources/assets/js/app.js', 'public/js/app.js')
    .combine([
        'resources/assets/js/custom.js',
        plugin + 'moment/moment-with-locales.min.js'
    ],'public/js/plugin.js')
    .webpackConfig({
        plugins: [
            new CompressionPlugin({
                asset: "[path].gz[query]",
                algorithm: "gzip",
                test: /\.js$|\.css$|\.html$|\.svg$/,
                threshold: 10240,
                minRatio: 0.8
            }),
            new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/)
        ]
    });

if (mix.inProduction()) {
    mix.version();
}
