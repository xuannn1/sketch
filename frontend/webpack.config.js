var path = require('path');
var webpack = require("webpack");
var OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");
var BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;
var HtmlWebpackPlugin = require("html-webpack-plugin");
var CleanWebpackPlugin = require("clean-webpack-plugin");
var merge = require("webpack-merge");
var MiniCssExtractPlugin = require('mini-css-extract-plugin');

function commonConfig (devMode) {
    return {
        entry: {
            // polyfill: 'babel-polyfill', // for ie8
            // './src/index.tsx',
            app: './src/test/index.tsx',
        },
        output: {
            path: path.resolve(__dirname, 'dist'),
            filename: devMode ? '[name].bundle.js' : '[name].bundle.min.js',
            chunkFilename: devMode ? '[name].chunk.js' : '[name].chunk.min.js',
        },
        resolve: {
            extensions: ['.ts', '.tsx', '.js', '.json'],
            modules: ['node_modules'],
            alias: {
                '@material-ui/core': '@material-ui/core/es',
                '@material-ui/icons': '@material-ui/icons/index.es',
            }
        },
        module: {
            rules: [
                { test: '/\.html$/', use: [
                    { loader: 'html-loader', options: {
                        attrs: ['img:src'],
                    }},
                ]},
                { test: /\.tsx?$/, use: [
                    { loader: 'babel-loader', options: {
                        exclude: 'node_modules',
                    }},
                    'ts-loader',
                ]},
                { test: /\.scss$/, use: [
                    devMode ? { loader: 'style-loader', options: {
                        singleton: true,
                    }} : MiniCssExtractPlugin.loader,
                    { loader: 'css-loader', options: {
                        minimize: true,
                        sourceMap: devMode ? true : false,
                    }},
                    { loader: 'postcss-loader', options: {
                        plugins: () => [ require('precss'), require('autoprefixer')],
                    }},
                    { loader: 'sass-loader' },
                ]},
                { test: /\.(png|jpg|gif|svg|eot|ttf|woff|woff2)$/, use: [
                    { loader: 'url-loader', options: {
                        name: "[name]-[hash:5].min.[ext]",
                        limit: 8192,
                        publicPath: "assets/",
                        outputPath: "dist/assets/",
                    }},
                ]},
            ]
        },
        plugins: [
            new HtmlWebpackPlugin({
                filename: "index.html",
                template: "index.html",
                minify: {
                    collapseWhitespace: true,
                    removeComments: true,
                },
            }),
        ],
    };
}

var devConfig = {
    mode: 'development',
    devtool: 'source-map',
    module: {
        rules: [
            { enforce: 'pre', test: /\.js$/, loader: 'source-map-loader' }, 
        ],
    },
    devServer: {
        contentBase: path.join(__dirname, 'dist'),
        compress: true,
        port: 2333,
        open: true,
        hot: true,
        overlay: true,
        allowedHosts: [
            'sosad.fun',
            'wenzhan.org',
        ],
        historyApiFallback: true,
    },
    plugins: [
        new webpack.HotModuleReplacementPlugin(),
        new webpack.NamedModulesPlugin(), // also for hot updates
    ],
};

var prodConfig = {
    mode: 'production',
    plugins: [
        new MiniCssExtractPlugin({
            filename: '[name].min.css',
            chunkFilename: '[id].min.css',
        }),
        new OptimizeCSSAssetsPlugin({}),
        new CleanWebpackPlugin(["dist"], {
            root: path.resolve(__dirname),
            verbose: true
        }),
    ]
};

module.exports = (env, argv) => {
    console.log('---', env, '---');
    var devMode = env !== 'production';
    var config = devMode ? devConfig : prodConfig;
    return merge(commonConfig(devMode), config);
};