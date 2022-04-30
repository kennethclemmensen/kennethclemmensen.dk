const { merge } = require('webpack-merge');
const common = require('./webpack.common.js');
const package = require('./package.json');
const path = require('path');
const mergeIntoSingleFilePlugin = require('webpack-merge-and-include-globally');
const cssFile = 'style.min.css';

module.exports = merge(common, {
    entry: './' + package.config.styleCssFile,
    output: {
        filename: cssFile,
        path: path.resolve(__dirname, 'public/wp-content/themes/kennethclemmensen/css/')
    },
    module: {
        rules: [
            { test: /\.css$/, use: 'css-loader' }
        ]
    },
    plugins: [
        new mergeIntoSingleFilePlugin({
            files: [{
                src: [
                    'node_modules/@fortawesome/fontawesome-free/css/all.min.css',
                    'node_modules/lightbox2/dist/css/lightbox.min.css',
                    package.config.cssCompiledFiles
                ],
                dest: cssFile
            }]
        })
    ]
});