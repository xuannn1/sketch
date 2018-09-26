var path = require('path');
var LiveReloadPlugin = require('webpack-livereload-plugin');

var config = {
    entry: ['./src/index.tsx'],
    output: {
        path: path.resolve(__dirname, './dist'),
        filename: 'bundle.js',
    },
    resolve: {
        extensions: ['.ts', '.tsx', '.js'],
        modules: ['node_modules'],
    },
    module: {
        rules: [
            { test: /\.tsx?$/, use: ['awesome-typescript-loader'] }, // ts parser
            { test: /\.scss$/, use: [ // sass parser
                { loader: 'style-loader' },
                { loader: 'css-loader' },
                { loader: 'sass-loader' },
            ] },
            { test: /\.(png|jpg|gif|svg|eot|ttf|woff|woff2)$/, use: [
                { loader: 'url-loader', options: { limit: 8192 } }
            ]}, // file parser
        ]
    },
    externals: { // for improving packing speed
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

module.exports = (env, argv) => {
    switch (argv.mode) {
        case 'development':
            config.devtool = 'source-map';
            config.module.rules.push({ enforce: 'pre', test: /\.js$/, loader: 'source-map-loader' }); // for dev
            break;
        case 'production':
            break;
    }

    return config;
};