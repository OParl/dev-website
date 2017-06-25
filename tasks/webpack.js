import path from 'path'
import webpack from 'webpack'
import process from 'process'
import friendlyErrors from 'friendly-errors-webpack-plugin'

const isProduction = (process.env.NODE_ENV === 'production');

const webpackDefaultPlugins = [
    new friendlyErrors(),
    new webpack.HotModuleReplacementPlugin(),
];

let config = {
    entry: {
        api: "./api.js",
        developers: "./developers.js",
        spec: "./spec.js"
    },
    output: {
        path: path.resolve(__dirname, '../public/js'),
        filename: "[name].js"
    },
    context: path.resolve(__dirname, '../resources/js'),

    plugins: isProduction
        ? [ new webpack.optimize.UglifyJsPlugin() ].concat(webpackDefaultPlugins)
        : webpackDefaultPlugins
};

function scripts() {
    return new Promise(resolve => webpack(config, (err, stats) => {

        if (err) console.log('Webpack', err);

        console.log(stats.toString({ /* stats options */ }));

        resolve()
    }))
}

export { config, scripts };