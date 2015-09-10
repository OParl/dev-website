<?php namespace App\Providers;

use App\Http\ViewComposers\AdminHeader;
use App\Http\ViewComposers\Header;
use App\Http\ViewComposers\NewsArchive;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		/* @var \Illuminate\View\View v */
		$v = view();

		$v->composer('header',       Header::class);
    $v->composer('news.archive', NewsArchive::class);

		$v->composer('admin.header', AdminHeader::class);

    // add a @markdown(...) directive which formats value to markdown
		\Blade::directive('markdown', function ($expr) {
      return "<?php echo \\Parsedown::instance()->parse({$expr}) ?>";
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
		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'App\Services\Registrar'
		);
	}

}
