const packageConfig = require('./package.json');
const path = require('path');
const mergeIntoSingleFilePlugin = require('webpack-merge-and-include-globally');

module.exports = {
    entry: './' + packageConfig.config.appJsFile,
    output: {
        filename: 'js/dist/compiled.min.js',
        path: path.resolve(__dirname, 'public/wp-content/themes/kennethclemmensen/')
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
                dest: 'js/dist/libraries.min.js'
            }, {
                src: [
                    packageConfig.config.cssLibrariesFiles,
                    packageConfig.config.cssCompiledFiles
                ],
                dest: 'css/style.min.css'
            }]
        })
    ]
};