var path = require('path');
var LiveReloadPlugin = require('webpack-livereload-plugin');

var config = {
    entry: ['./src/index.tsx'],
    output: {
        path: path.resolve(__dirname, './dist'),
        filename: 'bundle.js',
    },
    resolve: {
        extensions: ['.ts', '.tsx', '.js', '.json'],
        modules: ['node_modules'],
    },
    module: {
        rules: [
            { test: /\.tsx?$/, use: ['awesome-typescript-loader'] }, // ts parser
            { test: /\.scss$/, use: [ // sass parser
                { loader: 'style-loader' },
                { loader: 'css-loader' },
                { loader: 'postcss-loader', options: { // for bootstrap
                    plugins: () => [ require('precss'), require('autoprefixer') ]
                }},
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
};

module.exports = (env, argv) => {
    switch (argv.mode) {
        case 'development':
            console.log('--- Development Mode ---');
            config.mode = 'development';
            config.devtool = 'source-map';
            config.module.rules.push({ enforce: 'pre', test: /\.js$/, loader: 'source-map-loader' });
            config.devServer = {
                contentBase: path.join(__dirname),
                compress: true,
                port: 2333,
                open: true,
                allowedHosts: [
                    'sosad.fun',
                    'wenzhan.org',
                ],
            };
            break;
        case 'production':
            console.log('--- Production Mode ---');
            config.mode = 'production';
            break;
        case 'default':
            config.mode = 'none';
    }

    return config;
};