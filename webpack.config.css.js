const packageConfig = require('./package.json');
const path = require('path');
const mergeIntoSingleFilePlugin = require('webpack-merge-and-include-globally');

module.exports = {
    entry: './' + packageConfig.config.styleCssFile,
    output: {
        filename: 'style.min.css',
        path: path.resolve(__dirname, 'public/wp-content/themes/kennethclemmensen/css/')
    },
    module: {
        rules: [
            { test: /\.css$/, use: 'css-loader' }
        ]
    },
    mode: 'production',
    plugins: [
        new mergeIntoSingleFilePlugin({
            files: [{
                src: [
                    packageConfig.config.cssLibrariesFiles,
                    packageConfig.config.cssCompiledFiles
                ],
                dest: 'style.min.css'
            }]
        })
    ]
};