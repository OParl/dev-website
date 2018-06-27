let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/spec.js', 'public/js')
    .js('resources/js/api.js', 'public/js')
    .js('resources/js/developers.js', 'public/js')
    .extract([
        'buefy',
        'vue',
        'vue-affix',
        'vue-clipboard2'
    ])
    .sass('resources/assets/sass/app.scss', 'public/css')
    .sass('resources/assets/sass/pdf.scss', 'public/css')
    .copy('resources/assets/brand/icon/oparl-icon.png', 'public/images/favicon.png')
    .copy('resources/assets/brand/wortmarke/oparl-wortmarke-rgb.svg', 'public/images/logos/oparl.svg')
    .copy('resources/assets/img/oparl-icon-dev-slackbot.png', 'public/images/logos/oparl-slackbot.png')
    .copy('resources/assets/img/cfg.svg', 'public/images/logos')
    .copy('resources/assets/img/okf.svg', 'public/images/logos')
    .version();
