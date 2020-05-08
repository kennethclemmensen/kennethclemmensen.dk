const packageConfig = require('./package.json');
const path = require('path');

module.exports = {
    entry: './' + packageConfig.appJsFile,
    output: {
        filename: packageConfig.jsCompiledFile,
        path: path.resolve(__dirname, packageConfig.jsDistFolder)
    },
    mode: 'production'
};