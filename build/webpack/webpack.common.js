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
    performance: {
        maxAssetSize: 500000
    }
};