var elixir = require('laravel-elixir');

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
            'prism/plugins/line-numbers/prism-line-numbers.css'
        ], 'public/css/lib.css', 'vendor/_bower_components'
    );

    mix.scripts(
        [
            'jquery/dist/jquery.js',

            'prism/prism.js',
            'prism/components/prism-javascript.js',
            'prism/plugins/line-numbers/prism-line-numbers.js',

            'angular/angular.js',
            'angular-loading-bar/build/loading-bar.js',

            //'angular-route/angular-route.js',
            'angular-resource/angular-resource.js',
            'angular-ui/build/angular-ui.js'

            //'bootstrap-sass-official/assets/javascripts/bootstrap/modal.js',
            //'bootstrap-sass-official/assets/javascripts/bootstrap/tab.js'
        ], 'public/js/lib.js', 'vendor/_bower_components/'
    );

    mix.copy('vendor/_bower_components/bootstrap-sass-official/assets/fonts', 'public/fonts');
    mix.copy('vendor/_bower_components/font-awesome/fonts', 'public/fonts');
});
