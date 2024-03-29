import { merge } from 'webpack-merge';
import common from './webpack.common.js';
import path from 'path';
import mergeIntoSingleFilePlugin from 'webpack-merge-and-include-globally';
import { fileURLToPath } from 'url';
import CopyPlugin from 'copy-webpack-plugin';

export default () => {
    const cssFile = 'style.min.css';
    const compiledCssFile = './public/wp-content/themes/kennethclemmensen/css/compiled/style.css';
    return merge(common, {
        entry: compiledCssFile,
        output: {
            filename: cssFile,
            path: path.resolve(path.dirname(fileURLToPath(import.meta.url)), '../../public/wp-content/themes/kennethclemmensen/css/')
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
};