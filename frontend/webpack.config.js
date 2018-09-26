var path = require('path');
var LiveReloadPlugin = require('webpack-livereload-plugin');

var config = {
    entry: ['./src/index.tsx'],
    output: {
        path: path.resolve(__dirname, './dist'),
        filename: 'bundle.js',
    },
    devtool: 'source-map',
    resolve: {
        extensions: ['.ts', '.tsx', '.js'],
        modules: ['node_modules'],
    },
    module: {
        rules: [
            { test: /\.tsx?$/, use: ['awesome-typescript-loader'] },
            { enforce: 'pre', test: /\.js$/, loader: 'source-map-loader' },
            { test: /\.scss$/, use: [
                { loader: 'style-loader' },
                { loader: 'css-loader' },
                { loader: 'sass-loader' },
            ] },
        ]
    },
    externals: {
        'react': 'React',
        'react-dom': 'ReactDOM',
    },
    plugins: [
        new LiveReloadPlugin(),
    ],
    devServer: {
        contentBase: path.join(__dirname),
        compress: true,
        port: 2333,
        allowedHosts: [
            'sosad.fun',
            'wenzhan.org',
        ],
    },
};

module.exports = config;