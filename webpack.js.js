const { merge } = require('webpack-merge');
const common = require('./webpack.common.js');
const package = require('./package.json');
const path = require('path');
const mergeIntoSingleFilePlugin = require('webpack-merge-and-include-globally');

module.exports = merge(common, {
    entry: './' + package.config.appJsFile,
    output: {
        filename: 'compiled.min.js',
        path: path.resolve(__dirname, 'public/wp-content/themes/kennethclemmensen/js/dist/')
    },
    plugins: [
        new mergeIntoSingleFilePlugin({
            files: [{
                src: [
                    'node_modules/jquery/dist/jquery.min.js',
                    'node_modules/lightbox2/dist/js/lightbox.min.js',
                    'node_modules/vue/dist/vue.global.prod.js'
                ],
                dest: 'libraries.min.js'
            }]
        })
    ]
});