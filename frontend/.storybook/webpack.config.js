const path = require('path');
const TSDocgenPlugin = require('react-docgen-typescript-webpack-plugin');

module.exports = (baseConfig, env, defaultConfig) => {
    const config = defaultConfig;
    // typescript loader:
    config.module.rules.push({
        test: /\.(ts|tsx)$/,
        include: path.resolve(__dirname, "../"),
        loader: require.resolve('ts-loader'),
    });
    config.module.rules.push({
        test: /\.scss$/, 
        loaders: ["style-loader", "css-loader", "sass-loader"],
        include: path.resolve(__dirname, "../"),
    });
    config.plugins.push(new TSDocgenPlugin()); // optional
    config.resolve.extensions.push('.ts', '.tsx');
    return config;
};