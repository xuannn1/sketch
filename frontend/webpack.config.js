var path = require('path');
var LiveReloadPlugin = require('webpack-livereload-plugin');
var UglifyJsPlugin = require('uglifyjs-webpack-plugin');
var MiniCssExtractPlugin = require("mini-css-extract-plugin");
var OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");
var BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

const mode = process.env.NODE_ENV;
const devMode = mode !== 'production';

if (devMode) {
    process.env.NODE_ENV = 'development';
}

const config = {
    entry: [
        // 'babel-polyfill', // for ie8
        './src/index.tsx',
    ],
    output: {
        path: path.resolve(__dirname, 'dist'),
        filename: 'bundle.js',
    },
    resolve: {
        extensions: ['.ts', '.tsx', '.js', '.json'],
        modules: ['node_modules'],
        alias: {
            '@material-ui/core': '@material-ui/core/es',
            '@material-ui/icons': '@material-ui/icons/es',
        }
    },
    module: {
        rules: [
            { test: /\.tsx?$/, use: [
                'babel-loader',
                'ts-loader',
            ]},
            { test: /\.scss$/, use: [
                devMode ? 'style-loader' : MiniCssExtractPlugin.loader,
                'css-loader',
            { loader: 'postcss-loader', options: {
                    plugins: () => [ require('precss'), require('autoprefixer')],
                }},
                'sass-loader',
            ]},
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
        new MiniCssExtractPlugin({
            filename: 'style.css',
        }),
        new OptimizeCSSAssetsPlugin({}),
    ],
};

module.exports = (env, argv) => {
    if (devMode) {
        // development mode
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
        config.plugins.push(new LiveReloadPlugin());
    } else {
        // production mode
        config.mode = 'production';
        // config.plugins.push(new UglifyJsPlugin());
    }

    return config;
};