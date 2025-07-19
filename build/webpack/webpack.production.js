import { merge } from 'webpack-merge';
import common from './webpack.common.js';
import TerserPlugin from 'terser-webpack-plugin';
import CssMinimizerPlugin from 'css-minimizer-webpack-plugin';

/**
 * Production configuration for Webpack.
 * This configuration is used for production builds.
 */
export default merge(common, {
    mode: 'production',
    /**
     * Minify JavaScript and CSS files for production builds.
     * https://webpack.js.org/configuration/optimization/
     */
    optimization: {
        minimize: true,
        minimizer: [
            new CssMinimizerPlugin(),
            new TerserPlugin({
                extractComments: false,
                terserOptions: {
                    format: {
                        comments: false
                    }
                }
            })
        ]
    }
});