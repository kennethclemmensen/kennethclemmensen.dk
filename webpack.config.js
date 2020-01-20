const path = require('path');

module.exports = {
    entry: './public/wp-content/themes/kennethclemmensen/js/compiled/App.js',
    output: {
        filename: 'script.min.js',
        path: path.resolve(__dirname, 'public/wp-content/themes/kennethclemmensen/js/minified')
    },
    mode: 'production'
};