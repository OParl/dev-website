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
            'prismjs/plugins/line-numbers/prism-line-numbers.css'
        ], 'public/css/lib.css', 'node_modules'
    );

    mix.browserify('spec.js', 'public/js/', 'resources/js');
    mix.browserify('api.js', 'public/js/', 'resources/js');

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

    // used zondicons need to copied to public/img/icons
    mix.copy('resources/assets/zondicons/book-reference.svg', 'public/img/icons/book-reference.svg');
    mix.copy('resources/assets/zondicons/download.svg', 'public/img/icons/download.svg');

    mix.copy('resources/assets/brand/icon/oparl-icon.png', 'public/img/favicon.png');

    // copy source code pro font files
    mix.copy('node_modules/source-code-pro/EOT/', 'public/fonts/');
    mix.copy('node_modules/source-code-pro/OTF/', 'public/fonts/');
    mix.copy('node_modules/source-code-pro/TTF/', 'public/fonts/');
    mix.copy('node_modules/source-code-pro/WOFF/', 'public/fonts/');
    mix.copy('node_modules/source-code-pro/WOFF2/', 'public/fonts/');

    // copy source sans pro font files
    mix.copy('node_modules/source-sans-pro/EOT/', 'public/fonts/');
    mix.copy('node_modules/source-sans-pro/OTF/', 'public/fonts/');
    mix.copy('node_modules/source-sans-pro/TTF/', 'public/fonts/');
    mix.copy('node_modules/source-sans-pro/WOFF/', 'public/fonts/');
    mix.copy('node_modules/source-sans-pro/WOFF2/', 'public/fonts/');
});
