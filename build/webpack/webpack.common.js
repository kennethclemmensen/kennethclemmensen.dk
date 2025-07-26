import path from 'path';
import { fileURLToPath } from 'url';
import MiniCssExtractPlugin from 'mini-css-extract-plugin';
import MergeIntoSingleFilePlugin from 'webpack-merge-and-include-globally';
import CopyPlugin from 'copy-webpack-plugin';

/**
 * The common configuration for Webpack to compile TypeScript and Less files.
 * This configuration is used for both development and production builds.
 */
export default {
    /**
     * Entry configuration.
     * https://webpack.js.org/guides/entry-advanced/
     */
    entry: {
        default: [
            './public/wp-content/themes/kennethclemmensen/ts/App.ts',
            './public/wp-content/themes/kennethclemmensen/less/style.less'
        ]
    },
    /**
     * Output configuration.
     * https://webpack.js.org/concepts/output/
     */
    output: {
        filename: '[name].js',
        path: path.resolve(path.dirname(fileURLToPath(import.meta.url)), '../../public/wp-content/themes/kennethclemmensen/dist')
    },
    /**
     * Setup Webpack to handle TypeScript and Less files.
     * https://webpack.js.org/concepts/modules/
     */
    module: {
        rules: [
            {
                test: /\.ts$/,
                use: 'ts-loader'
            },
            {
                test: /\.less$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'less-loader'
                ]
            }
        ]
    },
    /**
     * Plugins to handle merging files, copying assets and extracting css.
     * https://webpack.js.org/configuration/plugins/
     */
    plugins: [
        new MergeIntoSingleFilePlugin({
            files: [{
                src: [
                    'node_modules/vue/dist/vue.global.prod.js'
                ],
                dest: 'libraries.js'
            }, {
                src: [
                    'node_modules/@fortawesome/fontawesome-free/css/all.min.css'
                ],
                dest: 'libraries.css'
            }]
        }),
        new CopyPlugin({
            patterns: [
                { from: 'node_modules/@fortawesome/fontawesome-free/webfonts', to: '../webfonts' }
            ]
        }),
        new MiniCssExtractPlugin({
            filename: '[name].css'
        })
    ],
    /**
     * Setup the resolve configuration to handle TypeScript and JavaScript files.
     * https://webpack.js.org/concepts/module-resolution
     */
    resolve: {
        extensions: ['.ts', '.js']
    }
};