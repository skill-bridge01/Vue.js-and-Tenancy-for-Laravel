let mix = require('laravel-mix')

require('./nova.mix')

let mix = require('laravel-mix')

mix.setPublicPath('dist')
    .js('resources/js/action.js', 'js')
    .webpackConfig({
        resolve: {
            alias: {
                '@/storage': path.resolve(__dirname, '../../nova/resources/js/storage'),
                '@': path.resolve(__dirname, '../../nova/resources/js/'),
            },
            modules: [
                path.resolve(__dirname, '../../nova/node_modules/'),
            ],
            symlinks: false
        },
    }).vue({ version: 3 })
  .css('resources/css/tool.css', 'css')
  .nova('vendor/DataExportAction');
