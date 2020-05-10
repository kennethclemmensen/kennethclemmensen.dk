const merge = require('webpack-merge');
const common = require('./webpack.common.js');
const packageConfig = require('./package.json');
const path = require('path');
const mergeIntoSingleFilePlugin = require('webpack-merge-and-include-globally');

module.exports = merge(common, {
    entry: './' + packageConfig.config.appJsFile,
    output: {
        filename: 'compiled.min.js',
        path: path.resolve(__dirname, 'public/wp-content/themes/kennethclemmensen/js/dist/')
    },
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
});