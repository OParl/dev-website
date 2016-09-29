<?php

namespace App\Providers;

use App\Http\ViewComposers\Header;
use App\Http\ViewComposers\Piwik;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /* @var \Illuminate\View\View v */
        $v = view();

        $v->composer('header', Header::class);
        $v->composer('piwik', Piwik::class);

        // add a @markdown(...) directive which formats value to markdown
        \Blade::directive('markdown', function ($expr) {
            return "<?php echo \\Parsedown::instance()->parse({$expr}) ?>";
        });

        \Blade::directive('press', function ($expr) {
            return<<<LPMARKUP
<?php
    \$expr = collect(explode("\n", $expr))->map(function (\$line) { return trim(\$line); })->implode("\n");
    \$letterpress = app(EFrane\Letterpress\Letterpress::class);
    \$markuped = \$letterpress->markdown(\$expr);
    \$typofixed = \$letterpress->typofix(\$markuped);

    echo \$typofixed;
?>
LPMARKUP;
        });
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
    }
}
