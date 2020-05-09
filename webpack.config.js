const packageConfig = require('./package.json');
const path = require('path');
const mergeIntoSingleFilePlugin = require('webpack-merge-and-include-globally');

module.exports = {
    entry: './' + packageConfig.config.appJsFile,
    output: {
        filename: 'compiled.min.js',
        path: path.resolve(__dirname, 'public/wp-content/themes/kennethclemmensen/js/dist/')
    },
    mode: 'production',
    performance: {
        maxAssetSize: 275000
    },
    plugins: [
        new mergeIntoSingleFilePlugin({
            files: [{
                src: [
                    packageConfig.config.jsLibrariesFiles
                ],
                dest: 'libraries.min.js'
            }]
        })
    ]
};