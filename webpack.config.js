const packageConfig = require('./package.json');
const path = require('path');

module.exports = {
    entry: './public/wp-content/themes/kennethclemmensen/js/compiled/App.js',
    output: {
        filename: packageConfig.jsMinifiedFile,
        path: path.resolve(__dirname, packageConfig.jsMinifiedFolder)
    },
    mode: 'production'
};