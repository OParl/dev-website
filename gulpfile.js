var elixir = require('laravel-elixir');

require('laravel-elixir-vueify');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss');

    mix.styles(
        [
            'prismjs/themes/prism.css',
            'prismjs/plugins/line-numbers/prism-line-numbers.css',
            'keen-ui/dist/keen-ui.css'
        ], 'public/css/lib.css', 'node_modules'
    );

    mix.scripts(
        [
            'app.js'
        ], 'public/js/app.js', 'resources/js'
    );

    mix.browserify('spec.js', 'public/js/spec.js', 'resources/js');

    mix.scripts(
        [
            'jquery/dist/jquery.js',

            'prismjs/prism.js',
            'prismjs/components/prism-javascript.js',
            'prismjs/components/prism-sql.js',
            'prismjs/plugins/line-numbers/prism-line-numbers.js',

            'bootstrap-sass/assets/javascripts/bootstrap/transition.js',
            'bootstrap-sass/assets/javascripts/bootstrap/dropdown.js',
            'bootstrap-sass/assets/javascripts/bootstrap/affix.js',
            'bootstrap-sass/assets/javascripts/bootstrap/scrollspy.js',
            'bootstrap-sass/assets/javascripts/bootstrap/modal.js',
            'bootstrap-sass/assets/javascripts/bootstrap/collapse.js',
            'bootstrap-sass/assets/javascripts/bootstrap/tab.js'
        ], 'public/js/lib.js', 'node_modules'
    );

    mix.copy('node_modules/bootstrap-sass/assets/fonts', 'public/fonts');

    mix.copy('resources/assets/brand/icon/oparl-icon.png', 'public/img/favicon.png');
});
