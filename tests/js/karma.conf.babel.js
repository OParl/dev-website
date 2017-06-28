import merge from 'webpack-merge';
import webpack from 'webpack';
import {config} from '../../tasks/webpack';

let webpackConfig = config;

webpackConfig.devtool = '#inline-sourcemap';
webpackConfig.plugins.definitions = merge([
    new webpack.DefinePlugin({
        'process.env': {
            NODE_ENV: '"testing"'
        }
    })
]);

delete webpackConfig.entry;
delete webpackConfig.output;

export default (config) => {
    config.set({
        browsers: ['PhantomJS'],
        frameworks: [
            'mocha',
            'sinon-chai',
            'phantomjs-shim',
        ],
        reporters: ['spec', 'coverage'],
        files: ['./index.js'],
        preprocessors: {
            './index.js': ['webpack', 'sourcemap']
        },
        webpack: webpackConfig,
        webpackMiddleware: {
            noInfo: true,
        },
        coverageReporter: {
            dir: './coverage',
            reporters: [
                {type: 'lcov', subdir: '.'},
                {type: 'text-summary'},
            ]
        },
    })
}