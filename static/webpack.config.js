var webpack = require('webpack');
var CommonsChunkPlugin = require("webpack/lib/optimize/CommonsChunkPlugin");
var path = require('path');
module.exports = {
    entry: {
        index: './js/main.js',
        welcome: './js/welcome.js',
    },
    output: {
        path: './assets',
        publicPath: '/static/assets',
        filename: '[name].js',
        chunkFilename: '[name].[chunkhash].js',
    },
    module: {
        loaders: [
            {test: /\.css$/, loader: 'style-loader!css-loader'},
            {test: /\.(png|jpg)$/, loader: 'url-loader?limit=8192'},
            {
                test: /\.vue$/,
                loader: 'vue'
            },
            {test: require.resolve("jquery"), loader: "expose?$!expose?jQuery"}
        ]
    },
    babel: {
        presets: ['es2015'],
        plugins: ['transform-runtime']
    },

    externals: [
        require('webpack-require-http')
    ],
    plugins: [
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery",
            "window.jQuery": "jquery"
        }),
        new webpack.DefinePlugin({
            __BASE_URL__: JSON.stringify('http://localhost')
        }),
        new CommonsChunkPlugin('common/common.js')
    ]
}

if (process.env.NODE_ENV === 'production') {
    module.exports.plugins = [
        new webpack.DefinePlugin({
            'process.env': {
                NODE_ENV: '"production"'
            }
        }),
        new webpack.optimize.UglifyJsPlugin({
            compress: {
                warnings: false
            }
        }),
        new webpack.optimize.OccurenceOrderPlugin()
    ]
} else {
    module.exports.devtool = '#source-map'
}
