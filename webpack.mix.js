let mix = require('laravel-mix')

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

mix.js('resources/js/bundles/spec.js', 'public/js')
  .js('resources/js/bundles/api.js', 'public/js')
  .js('resources/js/bundles/endpoints.js', 'public/js')
  .extract([
    'axios',
    'buefy',
    'vue',
    'vue-affix',
    'vue-clipboard2'
  ])
  .sass('resources/assets/sass/app.scss', 'public/css')
  .sass('resources/assets/sass/pdf.scss', 'public/css')
  .combine([
    'node_modules/buefy/lib/buefy.css',
    'node_modules/prismjs/themes/prism.css',
    'node_modules/prism-themes/themes/prism-vs.css',
    'node_modules/swagger-ui/dist/swagger-ui.css'
  ], 'public/css/vendor.css')
  .copy('resources/assets/brand/icon/oparl-icon.png', 'public/images/favicon.png')
  .copy('resources/assets/brand/wortmarke/oparl-wortmarke-rgb.svg', 'public/images/logos/oparl.svg')
  .copy('resources/assets/img/oparl-icon-dev-slackbot.png', 'public/images/logos/oparl-slackbot.png')
  .copy('resources/assets/img/cfg.svg', 'public/images/logos')
  .copy('resources/assets/img/okf.svg', 'public/images/logos')
  .version()
