const { merge } = require('webpack-merge');
const common = require('./webpack.common.js');
const package = require('../../package.json');
const path = require('path');
const mergeIntoSingleFilePlugin = require('webpack-merge-and-include-globally');
const cssFile = 'style.min.css';
const compiledCssFile = './public/wp-content/themes/kennethclemmensen/css/compiled/compiled.css';
const CopyPlugin = require('copy-webpack-plugin');

module.exports = merge(common, {
    entry: compiledCssFile,
    output: {
        filename: cssFile,
        path: path.resolve(__dirname, '../../public/wp-content/themes/kennethclemmensen/css/')
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
                    compiledCssFile
                ],
                dest: cssFile
            }]
        }),
        new CopyPlugin({
            patterns: [
                { from: 'node_modules/@fortawesome/fontawesome-free/webfonts', to: '../webfonts' }
            ]
        })
    ]
});