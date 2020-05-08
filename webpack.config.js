const packageConfig = require('./package.json');
const path = require('path');
const mergeIntoSingleFilePlugin = require('webpack-merge-and-include-globally');

module.exports = {
    entry: './' + packageConfig.appJsFile,
    output: {
        filename: 'compiled.min.js',
        path: path.resolve(__dirname, 'public/wp-content/themes/kennethclemmensen/js/dist')
    },
    mode: 'production',
    performance: {
        maxAssetSize: 275000
    },
    plugins: [
        new mergeIntoSingleFilePlugin({
            files: {
                'libraries.min.js': [
                    packageConfig.jsLibrariesFiles
                ]
            }
        })
    ]
};