import path from 'path'
import webpack from 'webpack'
import process from 'process'
import FriendlyErrors from 'friendly-errors-webpack-plugin'

const isProduction = (process.env.NODE_ENV === 'production');

const webpackDefaultPlugins = [
    new webpack.DefinePlugin({
        'process.env': isProduction
    }),
    new webpack.NoEmitOnErrorsPlugin(),
    new webpack.HotModuleReplacementPlugin(),
];

if (process.env.NODE_ENV !== 'testing') {
    webpackDefaultPlugins.push(new FriendlyErrors({
        clearConsole: false
    }));
}

const webpackProdPlugins = [
    new webpack.optimize.UglifyJsPlugin()
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
        ? webpackProdPlugins.concat(webpackDefaultPlugins)
        : webpackDefaultPlugins,

    stats: {
        colors: true,
        reasons: true
    },

    cache: true,
    devtool: (isProduction) ? 'source-map' : false,

    resolve: {
        extensions: ['.js', '.vue', '.json'],
        alias: {
            'vue$': 'vue/dist/vue.esm.js',
            '@': path.resolve('resources/js')
        }
    },

    module: {
        loaders: [
            {
                test: /\.(js|vue)$/,
                loader: 'eslint-loader',
                enforce: 'pre',
                include: [],
                options: {
                    formatter: require('eslint-friendly-formatter')
                }
            },
            {
                test: /\.vue$/,
                loader: 'vue-loader',
                options: {}
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['env'],
                        plugins: ['transform-runtime'],
                        cacheDirectory: true,
                    }
                }
            },
            {
                test: /\.js$/,
                use: ["source-map-loader"],
                enforce: "pre"
            }
        ]
    }
};

function scripts() {
    return new Promise(resolve => webpack(config, (err, stats) => {

        if (err) console.log('Webpack', err);

        //console.log(stats.toString({}));

        resolve()
    }))
}

export { config, scripts };