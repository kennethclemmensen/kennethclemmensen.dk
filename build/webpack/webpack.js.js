import path from 'path';
import mergeIntoSingleFilePlugin from 'webpack-merge-and-include-globally';
import { fileURLToPath } from 'url';
import TerserPlugin from 'terser-webpack-plugin';

export default {
    mode: 'production',
    optimization: {
        minimize: true,
        minimizer: [
            new TerserPlugin({
                extractComments: false,
                terserOptions: {
                    format: {
                        comments: false
                    }
                }
            })
        ]
    },
    entry: './public/wp-content/themes/kennethclemmensen/js/compiled/App.js',
    output: {
        filename: 'compiled.min.js',
        path: path.resolve(path.dirname(fileURLToPath(import.meta.url)), '../../public/wp-content/themes/kennethclemmensen/js/dist/')
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                resolve: {
                    fullySpecified: false
                }
            }
        ]
    },
    plugins: [
        new mergeIntoSingleFilePlugin({
            files: [{
                src: [
                    'node_modules/vue/dist/vue.global.prod.js'
                ],
                dest: 'libraries.min.js'
            }]
        })
    ]
};