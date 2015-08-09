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
            'prism/plugins/line-numbers/prism-line-numbers.css',

            'select2/dist/css/select2.css'
        ], 'public/css/lib.css', 'vendor/_bower_components'
    );

    mix.scripts(
        [
            'app.js'
        ], 'public/js/app.js', 'resources/js'
    );

    mix.scripts(
        [
            'jquery/dist/jquery.js',

            'prism/prism.js',
            'prism/components/prism-javascript.js',
            'prism/plugins/line-numbers/prism-line-numbers.js',

            'bootstrap-sass-official/assets/javascripts/bootstrap/transition.js',
            'bootstrap-sass-official/assets/javascripts/bootstrap/affix.js',
            'bootstrap-sass-official/assets/javascripts/bootstrap/scrollspy.js',
            'bootstrap-sass-official/assets/javascripts/bootstrap/modal.js',

            'select2/dist/js/select2.js'
        ], 'public/js/lib.js', 'vendor/_bower_components/pusher/dist/pusher.js'
    );

    mix.scripts(
        [
            'pusher.js',
            '../../vendor/_bower_components/pusher/dist/pusher.js'
        ], 'public/js/pusher.js', 'resources/js'
    );

    mix.scripts(
        [
            'datetimepicker_tooltips_de.js',
            'admin.js'
        ], 'public/js/admin.js', 'resources/js'
    );

    mix.scripts(
        [
            'bootstrap-sass-official/assets/javascripts/bootstrap/collapse.js',
            'bootstrap-sass-official/assets/javascripts/bootstrap/dropdown.js',
            'bootstrap-sass-official/assets/javascripts/bootstrap/alert.js',

            'moment/moment.js',
            'moment/locale/de.js',

            'eonasdan-bootstrap-datetimepicker/src/js/bootstrap-datetimepicker.js'
        ], 'public/js/admin-lib.js', 'vendor/_bower_components/'
    );

    mix.copy('vendor/_bower_components/bootstrap-sass-official/assets/fonts', 'public/fonts');

    mix.copy('resources/assets/brand/icon/oparl-icon.png', 'public/img/favicon.png');
});
